@extends('layouts.app')

@section('content')

    <div>
        @include('common.errors')
        <table id="rentals-table">
            <thead>
                <tr>
                    <th>Ime in priimek najemnika</th>
                    <th>Email</th>
                    <th>Status izposoje</th>
                    <th>Oprema (šifra)</th>
                    <th>Datum od</th>
                    <th>Datum do</th>
                    <th>Skupna cena</th>
                    <th>Pripravljeno</th>
                    @if($status == 'in_progress' || $status == 'completed' || $status == 'all')
                        <!--<th>Pogodba o prevzemu</th>-->
                        <th>Pogodba o najemu</th>
                    @endif
                    @if($status == 'completed' || $status == 'all')
                        <th>Potrdilo</th>
                    @endif
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
            { data: 'status', name: 'status' },
            { data: 'products', name: 'products'},
            { data: 'date_from', name: 'date_from' },
            { data: 'date_to', name: 'date_to' },
            { data: 'total_price', name: 'total_price' },
            { data: 'ready_for_take_over', name: 'ready_for_take_over'}
        ];

        let status = '{{ $status }}';
        if(status == 'in_progress' || status == 'completed' || status == 'all'){
            dataTablesArray.push({ data: 'contract_filepath', name: 'contract_filepath'});
        }

        if(status == 'completed' || status == 'all'){
            dataTablesArray.push({ data: 'return_confirmation_filepath', name: 'return_confirmation_filepath'});
        }
        dataTablesArray.push({ data: 'created_at' });

        if(status == "canceled"){
          dataTablesArray.push({ data: 'canceled_timestamp' });
        } else {
          dataTablesArray.push({ data: 'edit', orderable: false });
        }


        var order_value = [[0, 'desc']];

        if(status == "all"){
          order_value = [[10, 'desc']];
        }

        if(status == "pending" || status == "successfully_paid"){
          order_value = [[8, 'desc']];
        }

        if(status == "in_progress"){
          order_value = [[9, 'desc']];
        }

        if(status == "completed"){
          order_value = [[5, 'desc']];
        }

        // special views      
        if(status == "today_out" || status == "week_out"){
          order_value = [[4, 'asc']];
        }

        if(status == "today_in"){
          order_value = [[5, 'desc']];
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
            order: order_value,
        });
    </script>
 @endsection
