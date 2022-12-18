@extends('layouts.app')
 
@section('content')
 
    <div class="container agent_add">
        @include('common.errors')

        @if ($agent == null)
            <div class="header">
                <div class="content">Dodajanje novih agentov</div>
            </div>
            <form action="{{ url('agent/save') }}" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}
    
                <div class="form-group col-md-4">
                    <label class="col-form-label text-md">Ime:</label>
                    <input type="text" class="form-control" name="name" required>
                </div>

                <div class="form-group col-md-4">
                    <label class="col-form-label text-md">Priimek:</label>
                    <input type="text" class="form-control" name="surname" required>
                </div>

                <div class="form-group col-md-4">
                    <label class="col-form-label text-md">Email:</label>
                    <input type="text" class="form-control" name="email" >
                </div>

                <div class="form-group col-md-4">
                    <label class="col-form-label text-md">Geslo:</label>
                    <input type="password" class="form-control" name="password" required>
                </div>

                <!--<label>Seznam strank:</label>
                <input type="text" name="customer_list"><br>-->
                <div class="form-group col-md-4 submit-btn">
                    <button type="submit" class="btn">Add</button>
                </div> 
            </form>            
        @else
            <div class="header">
                <div class="content">Urejanje podatkov agentov</div>
            </div>
            <form action='{{ url("agent/save/$agent->id") }}' method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}

                <div class="form-group col-md-4">
                    <label class="col-form-label text-md">Ime:</label>
                    <input type="text" class="form-control" name="name" value="{{ $agent->name }}">
                </div>

                <div class="form-group col-md-4">
                    <label class="col-form-label text-md">Priimek:</label>
                    <input type="text" class="form-control" name="surname" value="{{ $agent->surname }}">
                </div>

                <div class="form-group col-md-4">
                    <label class="col-form-label text-md">Email:</label>
                    <input type="text" class="form-control" name="email" value="{{ $agent->email }}">
                </div>

                <div class="form-group col-md-4">
                    <label class="col-form-label text-md">Geslo:</label>
                    <input type="password" class="form-control" name="password"><br>
                </div>

                <!--
                <label>Seznam strank:</label>
                <input type="text" name="customer_list" value="{{ $agent->customer_list }}"><br>
                -->
                <div class="form-group col-md-4 submit-btn">
                    <button type="submit" class="btn">Add</button>
                </div>
            </form>           
            <form action='{{ url("agent/delete/$agent->id") }}' method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="form-group submit-btn">
                    <button type="submit" class="btn">Izbriši</button>
                </div>
            </form>
        @endif
    </div>
 @endsection