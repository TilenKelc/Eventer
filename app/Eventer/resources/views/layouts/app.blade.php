<!doctype html>
<html lang="en">
  <head>
  	<title>Eventer</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!--<link  rel="icon" type="image/x-icon" href="{{URL::asset('/image/favicon_round_1111-opt.png')}}" />
    <link  rel="shortcut icon" type="image/x-icon" href="{{URL::asset('/image/favicon_round_1111-opt.png')}}"/>-->

    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/bs.css') }}" >
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" >
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">

    <link rel="stylesheet" href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css">

    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>

    <!--<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.css"/>-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/combine/npm/fullcalendar@5.11.0/main.min.css,npm/fullcalendar@5.11.0/main.css">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <style>
      #setDate{
        display: none;
      }
    </style>
  </head>
  <body>

		<div class="wrapper d-flex align-items-stretch">
			<nav id="sidebar">
				<h1>
          <a href="/" class="logo">
            <!--<img src="{{URL::asset('/image/sidebar_logo.png')}}" alt="sport_11_izposoja_opreme" class="logo_small">
            <img src="{{URL::asset('/image/sidebar_logo_full.png')}}" alt="sport_11_izposoja_opreme" class="logo_big">-->
            Logo
          </a>
        </h1>
        <ul class="list-unstyled components mb-5">
          <!--<li>
            <a href="https://11-11.si"><span class="fa fa-sign-out fa-rotate-180"></span>{{ __('Nazaj na 11-11.si') }}</a>
          </li>-->
          <li>
            <a href="{{ url('/category/index') }}"><span class="fa fa-cubes"></span>{{ __('Restavracije') }}</a>
          </li>



          @if(Auth::check() && Auth::user()->isStaff())
            <li>
              <a href="{{ url('/product/admin/index') }}"><span class="fa fa-cube"></span>{{ __('Mize') }}</a>
            </li>
            <li>
              <a href="{{ url('/rent/show/all') }}"><span class="fa fa-archive"></span> {{ __('Rezervacije') }}</a>
            </li>
            <li>
              <a href="{{ url('/user/show/all') }}"><span class="fa fa-users"></span>{{ __('Stranke') }}</a>
            </li>
          @endif

          @if(Auth::check() && Auth::user()->isAdmin())
            <li>
              <a href="{{ url('/agent/index') }}"><span class="fa fa-users"></span>{{ __('Agenti') }}</a>
            </li>
            <li>
              <a href="{{ url('/company/edit') }}"><span class="fa fa-building"></span> {{ __('Podatki o podjetju') }}</a>
            </li>
          @endif

          @if(Auth::check())
            <li>
              @if(Auth::user()->isStaff())
                <a href="{{ url('/myrents') }}"><span class="fa fa-history"></span> {{ __('Interne rezervacije') }}</a>
              @else
                <a href="{{ url('/myrents') }}"><span class="fa fa-history"></span> {{ __('Moje rezervacije') }}</a>
              @endif
            </li>
            <li>
              <a href="{{ url('/user/edit/' . Auth::id()) }}"><span class="fa fa-cogs"></span> {{ __('Osebni podatki') }}</a>
            </li>
          @endif

          <li>
            <a href="{{ url('/cenik') }}"><span class="fa fa-credit-card"></span>{{ __('Cenik') }}</a>
          </li>
          <li>
            <a href="{{ url('/pravila-in-pogoji-poslovanja') }}"><span class="fa fa-tasks"></span>{{ __('Pravila in pogoji poslovanja') }}</a>
          </li>
        </ul>

        <div class="footer">
        	<!-- <p>
					  Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved
					</p> -->
        </div>
    	</nav>

        <!-- Page Content  -->
      <div id="content" class="p-4 p-md-5">

        <nav class="navbar navbar-expand-lg navbar-light bg-light">
          <div class="container-fluid">

            <button type="button" id="sidebarCollapse" class="btn custom-submit-btn">
              <i class="fa fa-bars"></i>
              <span class="sr-only">Toggle Menu</span>
            </button>

             <!--<img src="{{URL::asset('/image/sport11-crnlogo.png')}}" alt="sport_11_izposoja_opreme" class="logo_mobile_center">-->

            <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fa fa-chevron-down"></i>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
              <ul class="nav navbar-nav ml-auto">


                <?php
                $url = Request::url();
                $base_url = url('/');
                $current_url = substr($url, strlen($base_url));
                if(!empty($current_url)){
                  $current = explode("/", $current_url)[1];
                }else{
                  $current = "";
                }
                ?>

                @if($current == 'category')
                  @if(Auth::check() && Auth::user()->isStaff())
                    <li class="nav-item">
                      <a class="nav-link" href="{{ url('/category/admin/index') }}">{{ __('Prikaži vse restavracije') }}</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="{{ url('/category/add') }}">{{ __('Dodaj restavracijo') }}</a>
                    </li>
                  @endif
                @elseif($current == 'rent' && Auth::user()->isStaff())
                  <li class="nav-item">
                    <a class="nav-link" href="{{ url('/rent/show/all') }}">{{ __('Prikaži vse izposoje') }}</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="{{ url('/rent/show/pending') }}">{{ __('Status: PENDING') }}</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="{{ url('/rent/show/successfully_paid') }}">{{ __('Status: USPEŠNO PLAČANO') }}</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="{{ url('/rent/show/in_progress') }}">{{ __('Status: V TEKU') }}</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="{{ url('/rent/show/completed') }}">{{ __('Status: ZAKLJUČENO') }}</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="{{ url('/rent/show/canceled') }}">{{ __('Status: PREKLICANO') }}</a>
                  </li>
                @elseif($current == 'agent')
                  <li class="nav-item">
                    <a class="nav-link" href="{{ url('/agent/index') }}">{{ __('Prikaži vse agente') }}</a>
                  </li>
                  <li class="nav-item">
                      <a class="nav-link" href="{{ url('/agent/add') }}">{{ __('Dodaj agenta') }}</a>
                  </li>
                @elseif($current == 'product')
                  @if(Auth::check() && Auth::user()->isStaff())
                    <li class="nav-item">
                      <a class="nav-link" href="{{ url('/product/admin/index') }}">{{ __('Vse razpoložljive mize') }}</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="{{ url('/product/add') }}">{{ __('Dodaj mizo') }}</a>
                    </li>
                  @endif
                @endif
                <!--
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('product/add') }}">{{ __('add product') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('category/add') }}">{{ __('add category') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">{{ __('Search by date') }}</a>
                </li>
                -->
              </ul>

              <!--<div>
                @if(Auth::check())
                <a href="{{ url('cart') }}" >
                  <i class="fa fa-shopping-cart" aria-hidden="true"></i>({{ $cart_num }})
                </a>
                @endif
              </div>-->

              <ul class="navbar-nav ml-auto">
                  <li class="nav-item active">
                      <a class="nav-link" href="{{ url('/') }}">Na začetek</a>
                  </li>

                  <!-- Authentication Links -->
                  @guest
                      <li class="nav-item">
                          <a class="nav-link" href="{{ route('login') }}">{{ __('Prijava') }}</a>
                      </li>
                      @if (Route::has('register'))
                          <li class="nav-item">
                              <a class="nav-link" href="{{ route('register') }}">{{ __('Registracija') }}</a>
                          </li>
                      @endif
                  @else


                    <li class="nav-item">
                      <a href="{{ url('cart') }}" class="nav-link">
                        <i class="fa fa-shopping-cart" aria-hidden="true"></i>({{ $cart_num }})
                      </a>
                    </li>
                      <li class="nav-item dropdown">
                          <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                              {{ Auth::user()->name }} <span class="caret"></span>
                          </a>

                          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                              <a class="dropdown-item" href="{{ route('logout') }}"
                                 onclick="event.preventDefault();
                                 console.log('zere');
                                               document.getElementById('logout-form').submit();">
                                  {{ __('Odjava') }}
                              </a>

                              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                  @csrf
                              </form>
                          </div>
                      </li>
                  @endguest
              </ul>
            </div>
          </div>
        </nav>

        <div class="alert alert-info alert-dismissible fade show" id="setDate">
          <a href="#" class="close" id="closeSetDate">&times;</a>
          Določi svoj termin:
          <form action='{{ url("/reservation/set") }}' method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div>
              <label>Začetek izposoje: </label>
              <input type="text" name="set_from_date" id="set_from_date" readonly="readonly" />
              <!-- <input type="date" name="set_from_date" id="set_from_date"  /> -->
            </div>
            <div>
              <label>Opremo bom vrnil: </label>
              <input type="text" name="set_to_date" id="set_to_date" readonly="readonly"/>
              <!-- <input type="date" name="set_to_date" id="set_to_date" /> -->
            </div>
            <div>
              <button type="submit" class="btn btn-info">Potrdi izbrane datume</button>
            </div>
          </form>
        </div>

        @if(session()->get("rental_to") != null && session()->get("rental_from") != null)
          <div class="alert alert-info alert-dismissible fade show" role="alert">
            <a href="#" class="close" id='reservation_reset'>&times;</a>
            Izbran termin:

            <div>OD: {{ date_format(date_create(session()->get("rental_from")), "H:00") }}</div>
            <div>DO: {{ date_format(date_create(session()->get("rental_to")), "H:00") }}</div>
            <div>DNE: {{ date_format(date_create(session()->get("rental_to")), "d.m.Y") }}</div>
            @if (!request()->is('products/bydate*'))
            <br>
            <center><a href='{{ url("/products/bydate") }}' class="btn custom-submit-btn">Vse razpoložljive mize v izbranem terminu v izbrani restavraciji</a></center>
            @endif
          </div>
        @endif
        <main class="py-4">

            @if($current == 'rent' && Auth::user()->isStaff())

            <div class="bljiznice text-center" style="margin-bottom: 24px;">
            <a class="" style="padding: 10px; background: lightcoral;  color: #fff; border: 1px solid #a20606; margin-right: 10px; border-radius: 4px;" href="{{ url('/rent/show/today_out') }}"><span class="fa fa-exclamation-circle"></span> {{ __('Današnji prevzemi') }}</a>
            <a class="" style="padding: 10px; background: blanchedalmond; border: 1px solid #a20606; margin-right: 10px; border-radius: 4px;" href="{{ url('/rent/show/today_in') }}"><span class="fa fa-share-square"></span> {{ __('Današnja vračila') }}</a>
            <a class="" style="padding: 10px; background: cornsilk; border: 1px solid #a20606; margin-right: 10px; border-radius: 4px;" href="{{ url('/rent/show/week_out') }}"><span class="fa fa-calendar"></span> {{ __('Prevzemi 7 dni') }}</a>
            </div>
            @endif


            @yield('content')
        </main>

        <!-- <h2 class="mb-4">Sidebar #07</h2>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p> -->
      </div>
		</div>

    <!-- <script src="{{ asset('js/jquery.min.js') }}" defer></script> -->
    <script src="{{ asset('js/popper.js') }}" defer></script>
    <script src="{{ asset('js/bootstrap.min.js') }}" defer></script>
    <script src="{{ asset('js/main.js') }}" defer></script>
    <script src="{{ asset('js/datepicker-sl.js') }}" defer></script>

    <script>
      $(document).ready(function () {
        $(document).on('click', ".demo", function () {
          Swal.fire(
            'Preklicano',
            'Ta funkcionalnost na demo verziji ne deluje',
            'info'
          )
        });

        $('#reservation_reset').on('click', function(){
          Swal.fire({
            title: 'Ali ste prepričani, da želite ponastaviti datum?',
            // text: "Tega dejanja ni mogoče razveljaviti",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Da, izbrati želim druge datume.',
            cancelButtonText: 'Ne, prekliči.',
          }).then((result) => {
            if(result.isConfirmed) {
              $.get("/reservation/delete", function(data) {
                Swal.fire(
                  'Datum ponastavljen!',
                  '',
                  'success'
                )
                location.reload();
              });
            }

          });
        });

        $('#dateSearchBtn').on('click', function(){
          $('#setDate').show();
          $("html, body").animate({ scrollTop: 0 }, "slow");
          return false;
        });

        /*
        $('#setDateBtnSubmit').on('click', function(){
          let data = {
            from: $('#set_from_date').val(),
            to:$('#set_to_date').val()
          };

          if(data['from'] >= data['to']){
            alert('Datum od dne, ne sme biti večji od datuma do dne');

          }else{
            $.post("{{ url('/reservation/set') }}", data)
            .done(function(response){
              alert("success");
              console.log(response);
            });
          }
        });
        */

        $('#closeSetDate').on('click', function(){
          $('#setDate').hide();
        });

        // ob kliku na prvi datepicker vplivamo na minDate drugega datepickerja

        $("#set_from_date").datepicker({
          minDate: new Date(),
          onSelect: function(dateText, inst){
            var datepicker_1_val = $("#set_from_date").datepicker('getDate');
            datepicker_1_val.setDate(datepicker_1_val.getDate()+1);
            $("#set_to_date").datepicker("option","minDate", datepicker_1_val);
          }
        });

        $("#set_to_date").datepicker();



      });
    </script>
  </body>
</html>
