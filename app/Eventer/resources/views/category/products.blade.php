@extends('layouts.app')

@section('content')
    @include('common.errors')


    <?php
      if(!count($products)){
        echo "<span>V izbrani kategoriji, v izbranem terminu ni na voljo nobene proste dvorane.</span>";
      }
    ?>

    

    @if(count($products))
    <div class="container">
        <?php 
          $category = App\Category::find($category_id); 
        ?>
        <h3>{{ $category->name }}</h3>
        <p>{{ $category->description }}</p>
    </div>
    @endif
    <div class="container category_view">
        @foreach($products as $product)
                <a class="product_card" href='{{ url("/product/$product->id") }}'>
                  <div class="card_content">
                      <img class="prod-img" src='{{url("$product->image")}}' alt="Slika" width="250" height="250">
                      <div class="card-body">
                          <h4 class="card-title">{{ $product->name }}</h4>
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
