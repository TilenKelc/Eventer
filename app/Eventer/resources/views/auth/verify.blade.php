@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card text-center">
                <div class="card-header"><b>{{ __('Potrditi morate vaš e-poštni naslov!') }}</b></div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('Nova povezava za potrditev registracije je bila poslana na vaš e-poštni naslov.') }}
                        </div>
                    @endif

                    {{ __('Pred uporabe rezervacijskega sistema v polnem obsegu, preverite elektronsko pošto in potrdite vaš e-poštni naslov.') }}
                    <br><br>
                    {{ __('Če našega sporočila niste prejeli ali pa ga ne najdete') }}, <a href="{{ route('verification.resend') }}">{{ __('kliknite tukaj') }}</a> {{ __(' in poslali vam ga bomo ponovno. Povezava za potrditev je veljavna 1 uro.') }}.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
