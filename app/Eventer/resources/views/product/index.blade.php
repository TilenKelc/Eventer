@extends('layouts.app')

@section('content')

    <div>
        @include('common.errors')

        <table id="products-table">
            <thead>
                <tr>
                    <th>Naziv</th>
                    <th>Datum ustvarjanja</th>
                    <th>Datum posodabljanja</th>
                    <th>Uredi</th>
                    <th>Izbriši</th>
                </tr>
            </thead>
        </table>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>

    <script>
        $('#products-table').DataTable({
            language: {
               "url": "//cdn.datatables.net/plug-ins/1.12.1/i18n/sl.json"
            },
            processing: false,
            serverSide: false,
            orderClasses: false,
            ajax: '/product/all',
            columns: [
                { data: 'name', name: 'name' },
                { data: 'created_at', name: 'created_at' },
                { data: 'updated_at', name: 'updated_at' },
                { data: 'edit', orderable: false },
                { data: 'delete', orderable: false }
            ]
        });
        function confirmAction() {
            return confirm("Ali ste prepričani, da želite izbrisati ta artikel?");
        }
    </script>
 @endsection
