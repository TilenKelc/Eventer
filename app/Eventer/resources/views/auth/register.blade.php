@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Registracija') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Ime') }}*</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="surname" class="col-md-4 col-form-label text-md-right">{{ __('Priimek') }}*</label>

                            <div class="col-md-6">
                                <input id="surname" type="text" class="form-control @error('surname') is-invalid @enderror" name="surname" value="{{ old('surname') }}" required autocomplete="surname" autofocus>

                                @error('surname')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-mail naslov') }}*</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="phone_number" class="col-md-4 col-form-label text-md-right">{{ __('Telefonska številka') }}*</label>

                            <div class="col-md-6">
                                <input id="phone_number" type="text" class="form-control" value="{{ old('phone_number') }}"  name="phone_number" required autocomplete="tel">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Geslo') }}*</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Ponovi geslo') }}*</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="street" class="col-md-4 col-form-label text-md-right">{{ __('Naslov') }}*</label>

                            <div class="col-md-6">
                                <input id="street" type="text" class="form-control" value="{{ old('street') }}"  name="street" required autocomplete="street">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="city" class="col-md-4 col-form-label text-md-right">{{ __('Kraj') }}*</label>

                            <div class="col-md-6">
                                <input id="city" type="text" class="form-control" value="{{ old('city') }}"  name="city" required autocomplete="city">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="postal-code" class="col-md-4 col-form-label text-md-right">{{ __('Pošta') }}*</label>

                            <div class="col-md-6">
                                <input id="postal-code" type="text" class="form-control" value="{{ old('postal_code') }}"  name="postal_code" required autocomplete="postal_code">
                            </div>
                        </div>

                        <!--<div class="form-group row">
                            <label for="country-code" class="col-md-4 col-form-label text-md-right">{{ __('Country code') }}</label>

                            <div class="col-md-6">
                                <input id="country-code" type="text" class="form-control" value="{{ old('country_code') }}"  name="country_code" required autocomplete="country_code">
                            </div>
                        </div>-->

                        <div class="form-group form-check text-center" >
                            <input type="checkbox" class="form-check-input" id="agreement" required="required">
                            <label class="form-check-label" for="agreement">Strinjam se z obdelavo osebnih podatkov in <a href="/pravila-in-pogoji-poslovanja">splošnimi pogoji poslovanja</a>.</label>
                          </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn custom-submit-btn">
                                    {{ __('Ustvari uporabniški račun') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    hr.style10 {
        border-top: 1px dotted #8c8b8b;
        border-bottom: 1px dotted #fff;
    }
</style>
@endsection
