@extends('layouts.app')

@section('content')
<div class="container">
    @include('common.errors')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Urejanje uporabnika') }}</div>

                <div class="card-body">
                    <form method="POST" action='{{ url("/user/save/$user->id") }}'>
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{ $user->name }}" required autocomplete="name" autofocus>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="surname" class="col-md-4 col-form-label text-md-right">{{ __('Surname') }}</label>

                            <div class="col-md-6">
                                <input id="surname" type="text" class="form-control" name="surname" value="{{ $user->surname }}" required autocomplete="surname" autofocus>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ $user->email }}" disabled>
                            </div>
                        </div>

                        <hr class="style18">

                        <div class="form-group row">
                            <label for="street" class="col-md-4 col-form-label text-md-right">{{ __('Street name') }}</label>

                            <div class="col-md-6">
                                <input id="street" type="text" class="form-control" value="<?php if($user->address_id != null) { echo $user->getAddress()->street; } ?>"  name="street" required autocomplete="street">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="city" class="col-md-4 col-form-label text-md-right">{{ __('City name') }}</label>

                            <div class="col-md-6">
                                <input id="city" type="text" class="form-control" value="<?php if($user->address_id != null) { echo $user->getAddress()->city; } ?>"  name="city" required autocomplete="city">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="postal-code" class="col-md-4 col-form-label text-md-right">{{ __('Postal code') }}</label>

                            <div class="col-md-6">
                                <input id="postal-code" type="text" class="form-control" value="<?php if($user->address_id != null) { echo $user->getAddress()->postal_code; } ?>" name="postal_code" required autocomplete="postal_code">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="country-code" class="col-md-4 col-form-label text-md-right">{{ __('Country code') }}</label>

                            <div class="col-md-6">
                                <input id="country-code" type="text" class="form-control" value="<?php if($user->address_id != null) { echo $user->getAddress()->country_code; } ?>" name="country_code" required autocomplete="country_code">
                            </div>
                        </div>

                        <hr class="style18">

                        <div class="form-group row">
                            <label for="phone-num" class="col-md-4 col-form-label text-md-right">{{ __('Telefonska številka') }}</label>

                            <div class="col-md-6">
                                <input id="phone-num" type="text" class="form-control" value="{{ $user->phone_number }}" name="phone_num" required>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn custom-submit-btn">
                                    {{ __('Posodobi') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @if(Auth::check() && Auth::user()->isStaff())
        <div class="container">
            <table id="rentals-table">
                <thead>
                    <tr>
                        <th>Številka izposoje</th>
                        <th>Oprema</th>
                        <th>Datum od</th>
                        <th>Datum do</th>
                        <th>Skupna cena</th>
                        <th>Status</th>
                        <th>Pogodba o prevzemu</th>
                        <th>Pogodba o vračilu</th>
                        <th>Datum ustvarjanja</th>
                        <th>Pregled izposoje</th>
                    </tr>
                </thead>
            </table>
        </div>
    @endif
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>

<script>
    if('{{ Auth::user()->isStaff() }}' == '1'){
        $('#rentals-table').DataTable({
            language: {
                "url": "//cdn.datatables.net/plug-ins/1.12.1/i18n/sl.json"
            },
            processing: false,
            serverSide: false,
            orderClasses: false,
            ajax: '{{ url("/user/get_rent/$user->id") }}',
            columns: [
                { data: 'id' },
                { data: 'equipment_ids' },
                { data: 'rental_from' },
                { data: 'rental_to' },
                { data: 'total_price' },
                { data: 'status' },
                { data: 'contract_filepath' },
                { data: 'return_confirmation_filepath' },
                { data: 'created_at' },
                { data: 'edit', orderable: false }
            ]
        });
    }
</script>
@endsection
