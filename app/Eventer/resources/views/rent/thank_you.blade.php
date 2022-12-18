@extends('layouts.app')

@section('content')
    @include('common.errors')

    <div class="container thank_you">
        <div class="jumbotron text-center">
            <h1 class="display-3">Hvala!</h1>
            <p class="lead">Potrditev rezervacije boste prejeli tudi na vaš elektronski naslov. <br>
              Obvestili vas bomo tudi ko bo oprema pripravljena na prevzem.</p>
            <hr>
            <p>
              Želite več informacij? <a href="https://www.11-11.si/nasveti-za-nakup">Kontaktirajte nas.</a>
            </p>
            <p class="lead">
              <a class="btn btn-primary btn-sm" href="{{ url('/') }}" role="button">Nazaj na začetek</a>
            </p>
        </div>
    </div>
 @endsection
