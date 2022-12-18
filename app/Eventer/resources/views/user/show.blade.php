@extends('layouts.app')
 
@section('content')
 
    <div>
        @include('common.errors')
        <table id="rentals-table">
            <thead>
                <tr>
                    <th>Ime in priimek stranke</th>
                    <th>Email</th>
                    <th>Telefonska številka</th>
                    <th>Naslov</th>
                    <th>Uredi</th>
                </tr>
            </thead>
        </table>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
    
    <script>
        $('#rentals-table').DataTable({
            language: {
               "url": "//cdn.datatables.net/plug-ins/1.12.1/i18n/sl.json"
            },
            processing: false,
            serverSide: false,
            orderClasses: false,
            ajax: '/user/get/all',
            columns: [
                { data: 'name', name: 'name' },
                { data: 'email', name: 'email' },
                { data: 'phone_number', name: 'phone_number' },
                { data: 'address_id', name: 'address_id' },
                { data: 'edit', orderable: false },
            ],
        });
    </script>
 @endsection