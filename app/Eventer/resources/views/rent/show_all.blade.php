@extends('layouts.app')

@section('content')
<div class="container">
    @include('common.errors')        
    <table id="rentals-table">
        <thead>
            <tr>
                <th>Številka izposoje</th>
                <th>Oprema</th>
                <th>Datum od</th>
                <th>Datum do</th>
                <th>Skupna cena</th>
                <th>Status</th>
                <th>Pogodba o najemu</th>
                <!--<th>Pogodba o vračilu</th>-->
                <th>Datum ustvarjanja</th>
                <th>Pregled izposoje</th>
            </tr>
        </thead>
    </table>
</div>
<style>
    hr.style10 {
        border-top: 1px dotted #8c8b8b;
        border-bottom: 1px dotted #fff;
    }
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>

<?php $user_id = Auth::id(); ?>
<script>
    $('#rentals-table').DataTable({
        language: {
               "url": "//cdn.datatables.net/plug-ins/1.12.1/i18n/sl.json"
        },
        processing: false,
        serverSide: false,
        orderClasses: false,
        ajax: '{{ url("/user/get_rent/$user_id") }}',
        columns: [
            { data: 'id' },
            { data: 'equipment_ids' },
            { data: 'rental_from' },
            { data: 'rental_to' },
            { data: 'total_price' },
            { data: 'status' },
            { data: 'contract_filepath' },
            //{ data: 'return_confirmation_filepath' },
            { data: 'created_at' },
            { data: 'edit', orderable: false }
        ]
    });
</script>
@endsection
