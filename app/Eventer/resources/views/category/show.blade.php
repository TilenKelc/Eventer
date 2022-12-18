@extends('layouts.app')

@section('content')
    @include('common.errors')
    <div class="container">
        <h3>Restavracije</h3>

        @foreach($categories->chunk(3) as $row)
        <div class="row no-gutters categories_wrap">
            @foreach($row as $category)
              <a href="{{url("/category/$category->id")}}" class="card_single">
                <img class="card-img-top" src='{{url("$category->category_image")}}' alt="Slika" width="250" height="250">
                <div class="card-body">
                    <h5 class="card-title">{{ $category->name }}</h5>
                    <span class="btn custom-submit-btn">Več</span>
                </div>
              </a>
            @endforeach
        </div>
        @endforeach
    </div>
@endsection
