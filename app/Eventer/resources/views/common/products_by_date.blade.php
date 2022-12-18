@extends('layouts.app')

@section('content')
    @include('common.errors')


    <?php
      if(!count($products)){
        echo "<span>V izbranem terminu ni na voljo noben kos opreme iz naše ponudbe.</span>";
      }
    ?>

    @if(count($products))

    <center><h3>Vsa oprema na voljo v izbranem terminu:</h3></center>
    <!--<div class="container">
        <!--
        <div class="dropdown velikosti">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Velikosti
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href='{url("/products/bydate")}}'>Vse velikosti</a>
                if($sizes)
                  foreach($sizes as $size)
                      <a class="dropdown-item" href='{url("/products/bydate/$size->size")}}'>{$size->size}}</a>
                  endforeach
                endif
            </div>
        </div>
    </div>-->
    @endif
    <div class="container category_view">
        @foreach($products as $product)

                <a class="product_card" href='{{ url("/product/$product->id") }}'>
                  <div class="card_content">
                      <img class="prod-img" src='{{url("$product->image")}}' alt="Slika" width="250" height="250">
                      <div class="card-body">
                          <h4 class="card-title">{{ $product->name }}</h4>
                          <!--<span class="velikost">Velikost: { App\Size::find($product->size_id)->size  }}</span>-->
                          <span class="cena">Cena izposoje/dan: {{ $product->price_per_day }}€</span>
                          <span class="opis"><?php echo nl2br($product->details) ?></span>
                      </div>
                  </div>
                </a>


        @endforeach
    </div>

    <script>
        $(".clickable").click(function() {
            window.location = $(this).find("a").attr("href");
            return false;
        });
    </script>
 @endsection
