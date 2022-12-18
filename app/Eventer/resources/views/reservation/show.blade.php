@extends('layouts.app')

@section('content')
    <?php 
    $category = App\Category::find($product->category_id); 
    $current_date = date('Y-m-d');
    $current_time = date('H');
    ?>

    <div class="container reservations_calendar">
        @include('common.errors')
        <center><h2>Izberite želeni termin</h2></center>
        <div id="calendar"></div>

        <div class="confirm_dates">
           <center>
                <button style="button" id="submitDateBtn">
                    <span class="fa fa-calendar-check-o"></span>
                    Potrdi izbiro termina
                    <span class="fa fa-calendar-check-o"></span>
                </button>
          </center>
        </div>
    </div>

    <style>

    .fc-today-button{
      display: none !important;
    }
        td.fc-day.fc-day-past, .restricted {
            background-color: #EEEEEE;
        }
        .hideClass{
            /*display: none;*/
        }
        .restricted-event{
            background-color: #a20606;
            border-color: #a20606;
        }
        .button-style{
            background-color: #a20606;
            width: 100%;
            max-width: 310px;
            color: #fff;
            text-transform: uppercase;
            padding: 8px;
            font-weight: 600;
            border: none;
            font-size: 18px;
        }
        .button-style:hover{
            background-color: #6a0505;
        }
    </style>

    <script src='https://cdn.jsdelivr.net/npm/moment@2.27.0/min/moment.min.js'></script>
    <!-- <script src="{{ asset('js/fullcalendar.min.js') }}" defer></script> -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/moment@5.5.0/main.global.min.js'></script>

    <script>
        $(document).ready(function () {
            let clicked = false;
            let search_from = null;
            let search_to = null;

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            let events = [];
            /*events = [
                {
                    'start': "2022-12-18 08:00",
                    'end': "2022-12-18 10:00",
                    className: 'hideClass restricted-event',
                    editable: false,
                }
            ]*/
            $.ajax({
                url: '{{ route("reservation.full") }}',
                data: {
                    product_id: "{{ $product->id }}"
                },
                type: "GET",
                success: function (response) {
                    //console.log(response)
                    response.forEach(element => {
                        let start = moment(element["date_from"]).format('Y-MM-DD HH:00');
                        let end = moment(element["date_to"]).format('Y-MM-DD HH:00');

                        events.push({
                            'start': start,
                            'end': end,
                            className: 'hideClass restricted-event'
                        });
                    });
                }
            }).done(function(){
                getCalendar();
            });

            function getCalendar(){
                var calendar = document.getElementById('calendar');
                var calendar = new FullCalendar.Calendar(calendar, {
                    locale: 'sl',
                    selectable: true,
                    nowIndicator: true,
                    longPressDelay: 0.1,
                    slotDuration: '00:60:00',

                    initialView: 'timeGridWeek',
                    allDaySlot: false,
                    slotMinTime: "{{ $category->opens_at }}",
                    slotMaxTime: '{{ $category->closes_at }}',

                    firstDay: 1, // monday
                    height: 'auto',
                    selectOverlap: false,
                    eventOverlap: false,
                    events: events,
                    
                    selectAllow: function(select) {
                        let startDate = select.start;
                        let endDate = select.end;
                        endDate.setSeconds(endDate.getSeconds() - 1);  // celotni dan
                        if(startDate.getDate() === endDate.getDate()) {
                            let selectedDate = moment(select.start).format('Y-MM-DD');
                            let currentDate = "{{ $current_date }}";

                            if(selectedDate >= currentDate){
                                if(selectedDate === currentDate){
                                    let selectedTime = moment(select.start).format('HH');
                                    let currentTime = "{{ $current_time }}";
                                    
                                    if(selectedTime > currentTime){
                                        return true;
                                    }
                                }else{
                                    return true;
                                }
                            }
                        }
                        return false;    
                    },
                    /*viewDidMount: function(arg) {
                        view = arg.view;
                        const current = new Date();
                        const start = new Date(view.activeStart);
                        const end = new Date(view.activeEnd);

                        if(current >= start && current <= end){
                            $(".fc-prev-button").prop('disabled', true);
                            $(".fc-prev-button").addClass('fc-state-disabled');
                        }
                        else {
                            $(".fc-prev-button").removeClass('fc-state-disabled');
                            $(".fc-prev-button").prop('disabled', false);
                        }
                    },*/
                    /*dayCellDidMount: function (arg){
                        let date = arg.date;
                        let cell = arg.el;

                        if(events.length != 0){
                            events.forEach(element => {
                                var date_start = new Date(element['start']);
                                date_start.setDate(date_start.getDate() - 1);
                                // let end = new Date(element['end']);
                                // dodan je bil še replace zaradi safarijeve napačne interpetacije
                                let end = new Date(element['end'].replace(/-/g, "/"));

                                if(date >= date_start && date <= end) {
                                  cell.classList.add('restricted');
                                }

                                // če je zadnji dan enak zadnjemu dnevu eventa, dodamo special class
                                // datumov ne moremo kopletno primerjati, ker zaradzi različnih ur, pogoj primerjanja pade
                                if(date.getMonth() == end.getMonth() && date.getDate() == end.getDate()) {
                                  cell.classList.add('last_cell_of_event');
                                }
                            });
                        }
                    },*/
                    select: function(arg){
                        let startDate = moment(arg.start).format('Y-MM-DD HH:00');
                        let endDate = moment(arg.end).format('Y-MM-DD HH:00');

                        if(clicked){
                            calendar.getEventById('set').remove();
                        }

                        calendar.addEvent({
                            start: startDate,
                            end: endDate,
                            id: 'set',
                            className: 'current-event',
                            editable: true,
                        });

                        search_from = startDate;
                        search_to = endDate;

                        clicked = true;
                        calendar.unselect();
                    },
                    eventResize: function(arg) {
                        // posodobi search_to
                        search_to = moment(arg.event.end).format('Y-MM-DD HH:00');
                    },
                    eventDrop: function(arg) {
                        if(moment(arg.event.start) < moment()) {
                          //console.log("trenutni datum je večji od začetka eventa");
                          arg.revert();
                        } else {
                          //console.log("trenutni datum je manjši od začetka eventa");
                            search_from = moment(arg.event.start).format('Y-MM-DD HH:00');
                            search_to = moment(arg.event.end).format('Y-MM-DD HH:00');

                        }

                    },
                });
                calendar.render();
            };

            $('#submitDateBtn').on('click', function(){
                if(search_from == null || search_to == null){
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Najprej izberite datum!',
                    });
                }else{
                    Swal.fire({
                        title: 'Ali ste prepričani?',
                        text: "Trenutno izbrani termin je od " + moment(search_from).format('HH:00') + " do " + moment(search_to).format('HH:00') + " dne " + moment(search_to).format('DD.MM.Y'),
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#26aa01',
                        cancelButtonColor: '#6a0505',
                        confirmButtonText: 'Da, ta termin mi ustreza.',                            
                        cancelButtonText: 'Ne, prekliči',
                    }).then((result) => {
                        if(result.isConfirmed) {
                            $.ajax({
                                url: "{{ url('reservation/add') }}",
                                data: {
                                product_id: "<?= $product->id ?>",
                                start: search_from,
                                end: search_to
                                },
                                type: "POST",
                                success: function (response) {
                                    window.location.replace('{{ url("/product/$product->id") }}');
                                }
                            });
                        }
                    })

                    /*const date1 = new Date(search_from);
                    const date2 = new Date(search_to);
                    let diffTime = Math.abs(date2 - date1);
                    let diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                    diffDays += 1;

                    if(search_from == search_to && is_weekend(search_from)){
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Izberete lahko le soboto in nedeljo hkrati!',
                        });
                    }else if(diffDays > '{{ $product->max_num_days }}' || diffDays < '{{ $product->min_num_days }}'){
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Minimalno število dni za ta kos opreme je {{ $product->min_num_days }}, maksimalno pa {{ $product->max_num_days }}!',
                        });
                    }else{
                        Swal.fire({
                            title: 'Ali ste prepričani?',
                            text: "Trenutno izbrani datum je od " + moment(search_from).format('DD.MM.Y') + " do " + moment(search_to).format('DD.MM.Y'),
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#26aa01',
                            cancelButtonColor: '#6a0505',
                            confirmButtonText: 'Da, ta termin mi ustreza.',
                            cancelButtonText: 'Ne, prekliči',
                        }).then((result) => {
                            if(result.isConfirmed) {
                                $.ajax({
                                    url: "{{ url('reservation/add') }}",
                                    data: {
                                    product_id: "<?= $product->id ?>",
                                    start: search_from,
                                    end: search_to
                                    },
                                    type: "POST",
                                    success: function (response) {
                                        window.location.replace('{{ url("/product/$product->id") }}');
                                    }
                                });
                            }
                        })
                    }*/
                }
            });
        });
    </script>

     <style>
        .p-4{
            padding: 0px !important;
        }
    </style>
 @endsection
