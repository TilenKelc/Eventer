@extends('layouts.app')

@section('content')

    <div>
        @include('common.errors')
        <table id="rentals-table">
          <thead>
              <tr>
                  <th>Ime in priimek stranke</th>
                  <th>Email</th>
                  <th>Restavracija</th>
                  <th>Mize</th>
                  <th>Datum</th>
                  <th>Termin</th>
                  <th>Status</th>
                  <th>Potrjeno</th>
                  <th>Datum ustvarjanja</th>
                  @if($status == 'canceled')
                      <th>Preklicano</th>
                  @else
                      <th>Uredi</th>
                  @endif
              </tr>
          </thead>
      </table>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>

    <style>
        .list{
            margin: 0;
        }
    </style>
    <script>
        let dataTablesArray = [
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'category_id' },
            { data: 'products', name: 'products'},
            { data: 'date_from', name: 'date_from' },
            { data: 'date_to', name: 'date_to' },
            { data: 'status' },
            { data: 'ready_for_take_over', name: 'ready_for_take_over'}
        ];

        let status = '{{ $status }}';
        dataTablesArray.push({ data: 'created_at' });

        if(status == "canceled"){
          dataTablesArray.push({ data: 'canceled_timestamp' });
        } else {
          dataTablesArray.push({ data: 'edit', orderable: false });
        }

        $('#rentals-table').DataTable({
            language: {
               "url": "//cdn.datatables.net/plug-ins/1.12.1/i18n/sl.json"
            },
            processing: false,
            serverSide: false,
            orderClasses: false,
            ajax: '/rent/get/{{ $status }}',
            columns: dataTablesArray,
        });
    </script>
 @endsection
