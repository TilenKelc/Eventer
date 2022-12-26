@extends('layouts.app')

@section('content')
    <div>
        @include('common.errors')
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

            <div>
                <h5>Podatki o restavraciji:</h5>
                <p><b>Naziv:</b> {{ $category->name }}<br>
                <b>Ulica: </b> {{ $category->getAddress()  }} <br>
                <b>Kraj: </b>{{ $category->getPostalCode() }} {{ $category->getCity() }}<br>
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

            <h5>Seznam miz:</h5>
            <ul>
                @foreach ($products as $product)
                    <li>{{ $product->name }}</li>
                @endforeach
            </ul>

            <div>Datum: <b>{{ date('d.m.Y',strtotime($rent->rental_from)) }}</b></div>
            <div>Termin: <b>{{ date('H:00',strtotime($rent->rental_from)) }} - {{ date('H:00',strtotime($rent->rental_to)) }}</b></div>
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

            @if($rent->status == 'successfully_paid')
                <h3>Upravljanje izposoje:</h3>
                <br>
                <div class="preklic_najema">
                  Prekliči rezervacijo.
                </div>
                <div><button type="submit" class="btn custom-submit-btn" name="action" value="cancel">Prekliči</button></div>
                
                @if(Auth::user()->isStaff())
                    <div>
                    <br><br>
                        <label>Potrjeno?</label>
                        <div>
                            @if($rent->ready_for_take_over)
                                <span class="btn custom-submit-btn">Da</span>
                            @else
                                <button type="submit" class="btn custom-submit-btn" name="action" value="ready">Da</button>
                            @endif
                        </div>
                        <br><br>
                    </div>
                @else
                    <div>
                    <br><br>
                        <label>Potrjeno?</label>
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
                <h3>Upravljanje izposoje:</h3>
                @if($customer->isAgent())
                    <div>
                      <p>Vrnite znesek rezervacije stranki.</p>
                        <button type="submit" class="btn custom-submit-btn" name="action" value="refund">Refund</button>
                    </div>
                @else
                    <div>
                        @if($customer->isAgent())
                            <p>Sredstva so že bila vrnjena stranki.</p>
                        @else
                          <p>Nobena akcija ni na voljo.</p>
                        @endif
                    </div>
                @endif
            @endif
        </form>
    </div>
 @endsection
