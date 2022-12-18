@extends('layouts.app')
@section('content')

    <div class="cart">
        @include('common.errors')

        @if($rent == null)
            <div>
               Košarica je prazna.
            </div>
        @else

            <div class="container">
                <h4>Podatki o restavraciji:</h4>
                <p><b>Naziv:</b> {{ $category->name }}<br>
                <b>Ulica: </b> {{ $category->getAddress()  }} <br>
                <b>Kraj: </b>{{ $category->getPostalCode() }} {{ $category->getCity() }}<br>
            </div>

            <div class="container">
                <h4>Podatki o stranki:</h4>
                <p><b>Ime:</b> {{ $customer->name }}<br>
                <b>Priimek: </b> {{ $customer->surname }}<br>
                <b>Email:</b> {{ $customer->email }}<br>
                <b>Tel:</b> {{ $customer->phone_number }}<br>
                <b>Ulica: </b> {{ App\Address::find($customer->address_id)->street  }} <br>
                <b>Kraj: </b>{{ App\Address::find($customer->address_id)->postal_code . ' ' . App\Address::find($customer->address_id)->city  }}<br>
            </div>

            </div>
            <div class="container vsebina_kosarice">
                <table class="table">
                    <thead>
                        <tr>
                            <!--<th scope="col">#</th>-->
                            <th scope="col">Restavracija</th>
                            <th scope="col">Termin</th>
                            <?php
                              if(count($products) > 1){
                            ?>
                                <th scope="col" style="text-align:center;">Odstrani</th>
                            <?php
                              }
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach($products as $product){
                            //if only one product, naredimo kr remove rent
                            echo '<tr>';
                               // echo '<th scope="row">' . $row_num . '</th>';
                                echo '<td>' . $product->name .'</td>';
                                echo '<td>' . date_format(date_create($rent->rental_from), "H:00 d.m.Y") . ' - ' . date_format(date_create($rent->rental_to), "H:00 d.m.Y") . '</td>';
                                if(count($products) > 1){
                                    echo '<td style="text-align: center;"><a href="/removefromcart/'.$product->id.'"><span class="fa fa-times-circle" ></span></a></td>';
                                }
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
                </div>
                <div class="container">
                <div class="checkout_bottom">
                    <div>
                        Opomba:
                        <p>
                            Na spletu plačate samo akontacijo, ki znaša {{ ceil($category->amount) }} EUR in vam bo po končani rezervaciji povrnjena.<br>
                        </p>
                    </div>
                </div>
                <div class="finish_button_wrap">
                    @if(Auth::user()->isAgent())
                        <button class="btn custom-submit-btn"  id="confirmBtn">Potrdi rezervacijo</button>
                    @else
                        <button class="btn custom-submit-btn" id="confirmBtn">Nadaljuj na plačilo in potrdi rezervacijo</button>
                        <center><p>Plačilo rezervacije v višini {{ ceil($category->amount) }}€. Plačilo je možno s karticami Visa, Maestro, Mastercard,...</p></center>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <script>
        $(document).ready(function () {
            $('#confirmBtn').on('click', function(){
                if('{{ Auth::user()->isAgent() }}' == true){
                    Swal.fire({
                        title: 'Ali ste prepričani, da želite oddati naročilo?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#26aa01',
                        cancelButtonColor: '#6a0505',
                        confirmButtonText: 'Da',
                        cancelButtonText: 'Ne, prekliči',
                    }).then((result) => {
                        if(result.isConfirmed) {
                            location.href = '{{ url("/payment/$rent_id") }}';
                        }
                    });
                }else{
                    location.href = '{{ url("/payment/$rent_id") }}';
                }
            });
        });
    </script>

    
 @endsection
