@extends('layouts.app')

@section('content')

    <div class="container category_add">
        @include('common.errors')

        @if($category == null)
            <div class="header">
                <div class="content">Dodajanje novih restavracij</div>
            </div>
            <form action="{{ url('category/save') }}" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}

                <div class="form-group col-md-4">
                    <label class="col-form-label text-md">Naziv</label>
                    <input type="text" class="form-control" name="name" required>
                </div>
                
                <div class="form-group col-md-4">
                    <label class="col-form-label text-md">Opis:</label>
                    <textarea class="form-control" name="description" required></textarea>
                </div>

                <div class="form-group col-md-4">
                    <label class="col-form-label text-md">Plačilo za rezervacijo (EUR):</label>
                    <input type="number" class="form-control" id="opens_at" name="amount" required value="5">
                </div>

                <div class="form-group col-md-4">
                    <label class="col-form-label text-md">Restavracija se odpre ob (polne ure):</label>
                    <input type="time" class="form-control" id="closes_at" name="opens_at" required>
                </div>

                <div class="form-group col-md-4">
                    <label class="col-form-label text-md">Restavracija se zapre ob (polne ure):</label>
                    <input type="time" class="form-control" name="closes_at" required>
                </div>

                <div class="form-group col-md-4">
                    <label class="col-form-label text-md">Naslov:</label>
                    <input type="text" class="form-control" name="street" required>
                </div>

                <div class="form-group col-md-4">
                    <label for="city" class="col-form-label text-md">Kraj</label>
                    <input id="city" type="text" class="form-control" name="city" required>
                </div>

                <div class="form-group col-md-4">
                    <label for="postal-code" class="col-form-label text-md">Pošta</label>
                    <input id="postal-code" type="text" class="form-control" name="postal_code" required>
                </div>

                <!--<div class="form-group row">
                    <label for="country-code" class="col-md-4 col-form-label text-md-right">{{ __('Country code') }}</label>

                    <div class="col-md-6">
                        <input id="country-code" type="text" class="form-control" value="{{ old('country_code') }}"  name="country_code" required autocomplete="country_code">
                    </div>
                </div>-->

                <div class="form-group col-md-4">
                    <label class="col-form-label text-md">Slika:</label>
                    <input type="file" class="form-control-file" name="category_image" required>
                </div>
                
                <div class="form-group submit-btn">
                    <button type="submit" class="btn">Dodaj</button>
                </div>
            </form>
        @else
            <div class="header">
                <div class="content">Posodobitev restavracije</div>
            </div>
            <form action='{{ url("category/save/$category->id") }}' method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}

                <div class="form-group col-md-4">
                    <label class="col-form-label text-md">Naziv</label>
                    <input type="text" name="name" class="form-control" value="{{ $category->name }}">
                </div>

                <div class="form-group col-md-4">
                    <label class="col-form-label text-md">Opis:</label>
                    <textarea class="form-control" name="description" required>{{ $category->description }}</textarea>
                </div>

                <div class="form-group col-md-4">
                    <label class="col-form-label text-md">Plačilo za rezervacijo (EUR):</label>
                    <input type="number" class="form-control" name="amount" required value="{{ $category->amount }}">
                </div>

                <div class="form-group col-md-4">
                    <label class="col-form-label text-md">Restavracija se odpre ob (polne ure):</label>
                    <input type="time" class="form-control" id="opens_at" name="opens_at" required value="{{ $category->opens_at }}">
                </div>

                <div class="form-group col-md-4">
                    <label class="col-form-label text-md">Restavracija se zapre ob (polne ure):</label>
                    <input type="time" class="form-control" id="closes_at" name="closes_at" required value="{{ $category->closes_at }}">
                </div>

                <div class="form-group col-md-4">
                    <label class="col-form-label text-md">Naslov:</label>
                    <input type="text" class="form-control" name="street" value="{{ $category->getAddress() }}" required>
                </div>

                <div class="form-group col-md-4">
                    <label for="city" class="col-form-label text-md">Kraj</label>
                    <input id="city" type="text" class="form-control" name="city" value="{{ $category->getCity() }}" required>
                </div>

                <div class="form-group col-md-4">
                    <label for="postal-code" class="col-form-label text-md">Pošta</label>
                    <input id="postal-code" type="text" class="form-control" name="postal_code" value="{{ $category->getPostalCode() }}" required>
                </div>

                <div class="form-group col-md-4">
                    <label class="col-form-label text-md">Slika:</label>
                    <input type="file" class="form-control-file" name="category_image">
                </div>

                <div class="form-group col-md-4">
                    <label class="col-form-label text-md">Trenutna slika:</label>
                    <img src="{{ url($category->category_image) }}" class="form-control" alt="Slika produkta" height="100px" width="100px">
                </div>

                <div class="form-group submit-btn">
                    <button type="submit" class="btn">Posodobi</button>
                </div>
            </form>
            <form action='{{ url("category/delete/$category->id") }}' method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="form-group submit-btn">
                    <button type="submit" class="btn">Izbriši</button>
                </div>
            </form>
        @endif
    </div>
    <script>
        const opens_at = document.getElementById('opens_at');
        const closes_at = document.getElementById('closes_at');

        opens_at.addEventListener('input', (e) => {
            let hour = e.target.value.split(':')[0]
            e.target.value = `${hour}:00`
        })

        closes_at.addEventListener('input', (e) => {
            let hour = e.target.value.split(':')[0]
            e.target.value = `${hour}:00`
        })
    </script>
 @endsection
