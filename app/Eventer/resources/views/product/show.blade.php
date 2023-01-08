@extends('layouts.app')

@section('content')

    <div class="product_view">
        @include('common.errors')
        <div class="container breadcrumbs">
            <a href='{{ url("/category/$category->id") }}'>{{ $category->name }}</a> > {{ $product->name }}
        </div>
        <div class="container">
            <div class="row product_upper">
                <div class="col-sm">
                    <img src='{{url("$product->image")}}'>
                </div>
                <div class="col-sm right_content_wrap">
                  <div class="upper_part">
                    <h2 class="product_name">{{ $product->name }}</h2>

                    <?php
                    $check = true;
                    if(session()->get("rent_id")){
                        $rent = App\Rent::find(session()->get('rent_id'));
                        $product_ids = App\Reservation::whereIn('id', json_decode($rent->reservation_ids))->pluck('product_id');
                        $cart_product = App\Product::find($product_ids[0]);
                        if($cart_product->category_id != $category->id){
                            $check = false;
                        }
                    }
                    ?>
                    @if($check)
                        @if(session()->get("rental_to") != null && session()->get("rental_from"))
                            @if($already_in_cart)
                                <div class="addto">
                                    <a href="{{ url('cart') }}" type="button" class="btn already_in_cart"><span class="fa fa-calendar-check-o"></span>Zaključi rezervacijo</a>
                                </div>

                            @else
                                <div class="addto">
                                    <?php $url = url("/reservation/add/$product->id"); ?>
                                    <button type="button" class="btn put_in_cart" onclick="location.href='<?= $url ?>'"><span class="fa fa-calendar-plus-o"></span>Dodaj v košarico</button>
                                </div>
                            @endif
                        @else
                            <div class="addto">
                                <?php $url = url("/reservation/show/$product->id"); ?>
                                <button type="button" class="btn pick_date" onclick="location.href='<?= $url ?>'"><span class="fa fa-calendar"></span>Izberi termin</button>
                            </div>
                        @endif
                    @else
                        <div class="addto">
                            Dodaste lahko samo mize v isti restavraciji
                        </div>
                    @endif
                  </div>

                  <!--<div class="bottom_part">
                    <div class="opis"><b>Opis:</b><br>?php echo nl2br($product->details); ?></div>
                    <div><a href="{ $product->about_url }}" class="more_about">Več o izdelku</a></div>
                  </div>-->






                </div>
            </div>
            <div class="row product_bottom">
            
            <!--if(count($recommended_products) > 0)
              <h2>Priporočeno za vas:</h2>
                <div class="col carousel">
                    foreach($recommended_products as $product)
                        ?php $product_link = url('/product/' . $product->id); ?>
                        <div class="product-link" onclick="location.href='{ $product_link }}'">
                            <div class="related_card">
                                <img class="" src='{url("$product->image")}}'>
                                <div class="card-body">
                                    <h5 class="card-title">{ $product->name }}</h5>
                                    <p class="card-text">Velikost: { App\Size::find($product->size_id)->size  }}</h5></p>
                                </div>
                            </div>
                        </div>
                    endforeach
                </div>
            endif-->
            </div>
        </div>
    </div>

    <style>
        .product-link:hover{
            cursor: pointer;
        }
    </style>

    <script type="text/javascript" src="//code.jquery.com/jquery-1.11.0.min.js"></script>
    <script type="text/javascript" src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function(){
            $('.carousel').slick({
                // centerMode: true,
                // centerPadding: '60px',
                slidesToShow: 3,
                responsive: [
                    {
                    breakpoint: 768,
                    settings: {
                        arrows: false,
                        centerMode: true,
                        centerPadding: '40px',
                        slidesToShow: 3
                    }
                    },
                    {
                    breakpoint: 480,
                    settings: {
                        arrows: false,
                        centerMode: true,
                        centerPadding: '40px',
                        slidesToShow: 1
                    }
                    }
                ]
            });
        });

        $(function () {
          $('[data-toggle="tooltip"]').tooltip()
        })
    </script>

   

 @endsection
