@extends('layouts.app')

@section('content')
<div class="container home">
    <div class="row justify-content-center">
      <div class="banner_img">
        <img src="{{URL::asset('/image/izposoja-koles-banner-novo-2.jpg')}}" alt="Izposoja kolesa"/>
      </div>

      <p>
        Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
      </p>

      <h2 class="subtitle">Poiščite produkt za vas</h2>
      <div class="kartice_odlocitev">
        <a href="javascript:void(0)" id="dateSearchBtn" class="kartica">
          <img src="{{URL::asset('/image/Glede na datum.jpg')}}" alt="Tip"/>
          <div class="text">
            <h3>Glede na datum razpoložljivosti</h3>
            <span class="btn custom-submit-btn">Poiščite</span>
          </div>
        </a>
        <a href="/category/index" class="kartica">
          <img src="{{URL::asset('/image/Glede na tip kolesa.jpg')}}" alt="Tip"/>
          <div class="text">
            <h3>Glede na tip</h3>
            <span class="btn custom-submit-btn">Poiščite</span>
          </div>
        </a>
      </div>

    </div>
</div>
@endsection
