@extends('layouts.app')

@section('content')
    @include('common.errors')

    <div class="container thank_you">
        <div class="jumbotron text-center">
            <h1 class="display-3">Hvala!</h1>
            <p class="lead">Potrditev rezervacije je bila uspešna.<br>
            <hr>
            <p class="lead">
              <a class="btn btn-primary btn-sm" href="{{ url('/') }}" role="button">Nazaj na začetek</a>
            </p>
        </div>
    </div>
 @endsection
