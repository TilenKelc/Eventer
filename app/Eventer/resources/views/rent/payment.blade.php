@extends('layouts.app')

@section('content')
    <div class="alert alert-success text-center" role="alert" id="alert-success" style="display: none;">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        Plačilo je bilo uspešno.
    </div>
    <div class="alert alert-danger text-center" role="alert" id="alert-danger" style="display: none;">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        Plačilo ni bilo uspešno.
    </div>

    <div class="container bt-payment">
        <div class="col-md-6 col-md-offset-3 row">
            <div id="dropin-container"></div>
            <button id="submit-button">Plačaj</button>
            <div class="loader_parent">
              <div class="loader" id="loaderIcon"></div>
            </div>

        </div>
    </div>

    <script src="https://js.braintreegateway.com/web/dropin/1.31.1/js/dropin.min.js"></script>
    <style>
        .loader_parent{
          display: none;
          width: 100%;
          height: 100%;
          position: absolute;
          left: 0px;
          top: 0px;
          background-color: rgba(255,255,255,0.8);
        }

        .loader_parent.show{
          display: flex;
          justify-content: center;
          align-items: center;
          z-index: 9;
        }

        #loaderIcon{
            display: none;
        }
        .loader {
            border: 16px solid #f3f3f3; /* Light grey */
            border-top: 16px solid #3498db; /* Blue */
            border-radius: 50%;
            width: 120px;
            height: 120px;
            animation: spin 2s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .braintree-dropin.braintree-loading,
        .braintree-dropin.braintree-loaded{
          padding-top: 20px;
          padding-bottom: 20px;
        }

        .reload_page{
          display: block;
          margin-top: 12px;
          cursor: pointer;
          color: #a20606;
          font-size: 16px;
        }
    </style>
    <script>
        $(document).ready(function () {
            var button = document.querySelector('#submit-button');

            var threeDSecureParameters = {
              amount: "<?= $amount ?>",
              email: "<?= $customer->email ?>",
              billingAddress: {
                givenName: "<?= $customer->name ?>",
                surname: "<?= $customer->surname ?>",
                phoneNumber: "<?= $customer->phone_number ?>",
                streetAddress: "<?= $address->street ?>",
                postalCode: "<?= $address->postal_code ?>",
              }
            };

            braintree.dropin.create({
                authorization: "<?= $clientToken ?>",
                container: '#dropin-container',
                threeDSecure: true,
                translations: {
                   payingWith: 'Plačevanje s kartico',
                   payWithCard: 'Plačilo s kartico',
                   cardholderNameLabel: 'Imetnik kartice',
                   cardholderNamePlaceholder: 'Ime Priimek',
                   fieldEmptyForCardholderName: 'Prosimo vpišite podatke o imetniku kartice',
                   cardNumberLabel: 'Številka kartice',
                   fieldEmptyForNumber: 'Prosimo vpišite številko kartice',
                   fieldInvalidForNumber: 'Vpisana številka kartice ni veljavna',
                   expirationDateLabel: 'Datum veljavnosti',
                   expirationDateLabelSubheading: '(MM/LL)',
                   expirationDatePlaceholder: 'MM/LL',
                   fieldEmptyForExpirationDate: 'Prosimo vpišite datum veljavnosti vaše kartice',
                   cvvLabel: 'Varnostna koda',
                   cvvThreeDigitLabelSubheading: '(CVV)',
                   fieldEmptyForCvv: 'Prosimo vpišite varnostno kodo',

                   hostedFieldsFailedTokenizationError: 'Preverite vaše podatke in poskusite ponovno',
                   hostedFieldsFieldsInvalidError: 'Preverite vaše podatke in poskusite ponovno',
                   chooseAnotherWayToPay: 'Poskusite z drugo kartico',

                   fieldInvalidForExpirationDate: 'Datum veljavnosti kartice ni pravilen',
                 }

            }, function (createErr, instance) {



                button.addEventListener('click', function () {
                  $("#submit-button").hide();
                  $(".braintree-toggle").hide();

                  instance.requestPaymentMethod({
                    threeDSecure: threeDSecureParameters,

                    },function (err, payload) {
            
                      payload["rentID"] = "{{ $rent->id }}";
                      $('#loaderIcon').show();
                      $('.loader_parent').addClass("show");
                      $.get("{{ route('payment.process') }}", {payload}, function (response) {
                          if(response.success){
                              //$("#alert-success").show();
                              location.href = "{{ url('/payment_redirect') }}";
                          }else{
                              $("#alert-danger").show();
                              $("#submit-button").show();
                              $(".braintree-toggle").show();
                              $(".loader_parent").hide();
                          }
                      }, 'json');
                  });
                });
            });
        });

        $('.reload_page').click(function(){
          location.reload();
        });


    </script>
@endsection