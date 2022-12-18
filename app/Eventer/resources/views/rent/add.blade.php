@extends('layouts.app')

@section('content')
    <div>
        @include('common.errors')
        <!--
        @if($rent == null)
            <form action="{{ url('rent/save') }}" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}

                @if($user->isAgent())
                    <label>Customer:</label>
                    <input type="text" id="customer" placeholder="Ime in priimek" />
                    <input type="text" name="customer_id" id="customer_id" hidden/>
                    <div id="customersList"></div><br>
                @endif

                <label>Oprema:</label>
                <select name="equipment">
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select><br>

                <label>Najem od:</label>
                <input type="date" name="rental_from"><br>

                <label>Najem do:</label>
                <input type="date" name="rental_to"><br>

                <button type="submit">Add</button>
            </form>
        @else-->

        <form action='{{ url("rent/save/$rent->id") }}' method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}

            <div>
                <?php
                $status = "";
                switch($rent->status){
                    case 'pending':
                        $status = "V stanju čakanja";
                        break;
                    case 'successfully_paid':
                        $status = "Uspešno plačano";
                        break;
                    case 'in_progress':
                        $status = "V teku";
                        break;
                    case 'completed':
                        $status = "Zaključeno";
                        break;
                    case 'canceled':
                        $status = "Preklicano";
                        break;
                }
                ?>
                <h3>Status izposoje: {{ $status }}</h3>

            </div>

            <div><h5>Podatki o stranki:</h5>
              <p><b>Ime:</b> {{ $customer->name }}<br>
              <b>Priimek: </b> {{ $customer->surname }}<br>
              <b>Email:</b> {{ $customer->email }}<br>
              <b>Tel:</b> {{ $customer->phone_number }}<br>
              <b>Ulica: </b> {{ App\Address::find($customer->address_id)->street  }} <br>
              <b>Kraj: </b>{{ App\Address::find($customer->address_id)->city  }}<br>
              <b>Pošta: </b>{{ App\Address::find($customer->address_id)->postal_code  }}
            </div>

            <h5>Seznam opreme:</h5>
            <ul>
                @foreach ($products as $product)
                    <li>{{ $product->name . ' (' . $product->product_id .')' }}</li>
                @endforeach
            </ul>

            <div>Izposoja od: <b>{{ date('Y-m-d',strtotime($rent->rental_from)) }}</b></div>
            <div>Izposoja do: <b>{{ date('Y-m-d',strtotime($rent->rental_to)) }}</b></div>
            <br><br>

            @if(Auth::user()->isStaff())
            <?php
              if($rent->status == "pending" || $rent->status == "successfully_paid"){
                ?>
                <div style="position: relative;">
                  <a href='{{ url("rent/remove/$rent->id") }}' class="izbrisi_rent">(Izbriši rent)</a>
                </div>
                <?php
              }
            ?>
            @endif

            <h3>Upravljanje izposoje:</h3>



            @if($rent->status == 'successfully_paid')
                <?php
                $date = date('Y-m-d H:i:s', strtotime($rent->rental_from . ' + 12 hour'));
                //$date2 = date('Y-m-d H:i:s', strtotime($rent->rental_from . ' + 12 hour'));

                $d1= new DateTime(null, new DateTimeZone('Europe/Ljubljana'));
                $d2= new DateTime($date);
                $diff = $d1->diff($d2);
                $hours = $diff->h + ($diff->days * 24);

                $cancel = true;
                if($d1 > $d2){
                    $cancel = false;
                }
                ?>
                <br>
                <div class="preklic_najema">
                  Prekliči rezervacijo.
                  (preklic rezervacije manj kot 72 ur pred začetkom izposoje ni mogoč).
                </div>
                <?php
                if((($hours - 2) > 72) && $cancel){
                    echo '<div><button type="submit" class="btn custom-submit-btn" name="action" value="cancel">Prekliči</button></div>';
                }else{

                    echo '<div><button class="btn custom-submit-btn" style="cursor:not-allowed;" disabled>Prekliči</button></div>';
                }
                ?>
                @if(Auth::user()->isStaff())
                    <div>
                    <br><br>
                        <label>Pripravljeno na prevzem?</label>
                        <div>
                            @if($rent->ready_for_take_over)
                                <span class="btn custom-submit-btn">Da</span>
                            @else
                                <button type="submit" class="btn custom-submit-btn" name="action" value="ready">Da</button>
                            @endif
                        </div>
                        <br><br>
                    </div>
                    <div>
                      <h5>Ob prevzemu upreme:</h5>
                        <button type="submit" name="action" class="btn custom-submit-btn" value="rent_contract">Izdaj pogodbo</button>
                    </div>
                @else
                    <div>
                    <br><br>
                        <label>Pripravljeno na prevzem?</label>
                        <div>
                            @if($rent->ready_for_take_over)
                                <span class="btn custom-submit-btn">Da</span>
                            @else
                                <span class="btn custom-submit-btn">Ne</span>
                            @endif
                        </div>
                        <br><br>
                    </div>
                @endif
            @elseif($rent->status == 'in_progress' && Auth::user()->isStaff())
                <div>
                   <h5>Ko stranka vrne opremo:</h5>
                    <button type="submit" class="btn custom-submit-btn" name="action" value="return_contract">Izdaj potrdilo o vračilu opreme</button>
                </div>
            @elseif($rent->status == 'completed' && Auth::user()->isStaff())
                @if($rent->return_confirmation_issued && !$customer->isAgent())
                    <div>
                      <p>V primeru, da je bila oprema ustrezno vrnjena, znesek akontacije vrnete stranki s klikom na spodnji gumb.</p>
                        <button type="submit" class="btn custom-submit-btn" name="action" value="refund">Refund</button>
                    </div>
                @else
                    <div>
                        @if(!$customer->isAgent())
                            <p>Sredstva so že bila vrnjena stranki.</p>
                        @else
                          <p>Nobena akcija ni na voljo.</p>
                        @endif
                    </div>
                @endif
            @endif
            @if(!Auth::user()->isStaff())
                <div>
                    <h5>Pogodba o izposoji</h5>
                    @if($rent->contract_filepath != null)
                        <a href='{{ $rent->contract_filepath }}' target='_blank'><i class='fa fa-file-pdf-o' aria-hidden='true'></i></a>
                    @else
                        Dokument še ni na voljo
                    @endif
                </div>
                <div>
                    <h5>Potdilo o vračilu</h5>
                    @if($rent->return_confirmation_filepath != null)
                        <a href='{{ $rent->return_confirmation_filepath }}' target='_blank'><i class='fa fa-file-pdf-o' aria-hidden='true'></i></a>
                    @else
                        Dokument še ni na voljo
                    @endif
                </div>
            @endif
        </form>
        <!--@endif-->
    </div>
    <!--
    <script>
        /*
        $(document).ready(function () {
            $.noConflict();

            $('#customer').keyup(function(){
                var query = $(this).val();
                if(query != ''){
                    var _token = $('input[name="_token"]').val();
                    $.ajax({
                        url:" route('autocomplete.fetch') ",
                        method:"POST",
                        data:{query:query, _token:_token},
                        success:function(data){
                            $('#customersList').fadeIn();
                            $('#customersList').html(data);
                        }
                    });
                }else{
                    $('#customersList').fadeOut();
                }
            });

            $(document).on('click', '.najdenrezultat', function(){
                let customer = $(this).text();
                customer = customer.replace(/\s\s+/g, ' ');
                let customer_data = customer.split('|||')[0];
                let customer_id = customer.split('|||')[1];

                $('#customer').val(customer_data);
                $('#customer_id').val(customer_id);
                $('#customerList').fadeOut();
            });
        });
        */
    </script>
    -->
 @endsection
