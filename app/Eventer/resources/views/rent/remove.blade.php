@extends('layouts.app')

@section('content')
    <div>
        @include('common.errors')
    </div>

    <h2>Brisanje rezervacije iz sistema.</h2>
    <p>Brisanje rezervacije je možno samo dokler oprema ni prevzeta oz. dokler je rent v statusih "v čakanju"  ali "uspešno plačano".</p>
    <hr>

    <div class="">

      <?php // če order še ni bil refundan in če customer ni admin ?>
      @if($rent->status == "successfully_paid")
      <?php // če je že plačan ?>

          @if(!$rent->braintree_refund_transaction_id && !$customer->isAgent())
              <div>
                <p>Sredstva plačilne kartice še niso bila vrnjena. Izbris rezervacije bo omogočen šele ko bodo stredstva uspešno vrnjena stranki.</p>
                <form action='{{ url("/payment/refund/remove/$rent->id") }}' method="GET" enctype="multipart/form-data">
                  {{ csrf_field() }}
                  <input type="submit" class="btn custom-submit-btn" name="action" value="Refund" />
                </form>
              </div>
          @else
              <div>
                  @if(!$customer->isAgent())
                    <p>Sredstva so že bila vrnjena stranki, rent lahko izbrišete.</p>

                  @else
                    <p>To je interna rezervacija in jo lahko izbrišete.</p>

                  @endif

                  <form action='{{ url("rent/remove/$rent->id") }}' method="POST" class="remove_rent_form" enctype="multipart/form-data">
                      {{ csrf_field() }}

                      <input type="submit" class="btn custom-submit-btn remove_rent" name="" value="Izbriši rent">
                  </form>
              </div>
          @endif

      @elseif($rent->status == "pending")
      <?php // če plačilo še ni bilo izvedeno ?>
      <div>
          @if(!$customer->isAgent())
            <p>Sredstva niso bila zajeta (ne še, ali pa je bilo plačilo neuspešno) rent lahko izbrišete.</p>

          @else
            <p>To je interna rezervacija in jo lahko izbrišete.</p>

          @endif

          <form action='{{ url("rent/remove/$rent->id") }}' method="POST" class="remove_rent_form" enctype="multipart/form-data">
              {{ csrf_field() }}
              <input type="submit" class="btn custom-submit-btn remove_rent" name="" value="Izbriši rent">
          </form>
      </div>

      @else
      <?php // ne naredi nič ?>

      @endif


    </div>

<script type="text/javascript">
  $(document).ready(function () {

    $('.remove_rent').on('click',function(e){

      e.preventDefault();

      Swal.fire({
          title: 'Ali ste prepričani?',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#26aa01',
          cancelButtonColor: '#6a0505',
          confirmButtonText: 'Da, ta rent mora v arhiv.',
          cancelButtonText: 'Ne, prekliči.',
      }).then((result) => {
          if(result.isConfirmed) {
            $('.remove_rent_form').submit();
          }
      })

    })

  });




</script>

 @endsection
