@extends('User.master',['menu'=>'buy_coin'])
@section('title', $title)

@section('content')
    <div class="byecoin-page">
        <div class="bye-coin-area">
            <div class="container">
                <div class="byecoin-inner">
                    <div class="row ">
                        <div class="col-md-12 ">
                            <div class="page-wraper min-height">
                                <div class="bye-coin-top">
                                    <div class="section-title">
                                        <h4>{{__('Buy Our Coin From Here')}}</h4>
                                    </div>
                                </div>
                                <div class="bye-coin-from">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-area">
                                                <form action="{{route('buyCoin')}}" method="POST" enctype="multipart/form-data" id="buy_coin">
                                                  @csrf
                                                    <div class="row">
                                                        <div class="col-md-7">
                                                            <div class="form-group">
                                                                <label for="amount">{{__('Coin Amount')}}</label>
                                                                <buy-coin :coin_price="{{ settings('coin_price')}}"></buy-coin>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="amount2">{{__('Select Your Payment Method')}}</label>
                                                                <div class="check-box-list">
                                                                    <ul>
                                                                        <li>
                                                                            <input checked onchange="$('.payment_method').addClass('d-none');$('.bank-details').addClass('d-none');$('.bank-details').removeClass('d-block'); $('.card_payment').toggleClass('d-none');" value="{{CARD}}" type="radio" id="s-option" name="payment_type">
                                                                            <label for="s-option">{{__('Credit Card')}}</label>
                                                                            <div class="check"><div class="inside"></div></div>
                                                                        </li>
                                                                        <li>
                                                                            <input type="radio" onchange="$('.payment_method').addClass('d-none');$('.bank-details').addClass('d-none');$('.bank-details').removeClass('d-block');$('.btc_payment').toggleClass('d-none');" value="{{BTC}}" id="t-option" name="payment_type">
                                                                            <label for="t-option">{{__('Bitcoin')}}</label>
                                                                            <div class="check"><div class="inside"></div></div>
                                                                        </li>
                                                                        <li>
                                                                            <input type="radio" value="{{BANK_DEPOSIT}}"  onchange="$('.payment_method').addClass('d-none');$('.bank-details').addClass('d-block');$('.bank-details').removeClass('d-none');$('.bank_payment').toggleClass('d-none');" id="f-option" name="payment_type">
                                                                            <label for="f-option">{{__('Bank deposit')}}</label>
                                                                            <div class="check"></div>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-7">
                                                            <div class="form-group">
                                                                <div class="check-box-list btc_payment payment_method d-none">

                                                                    <div class="form-group buy_coin_address_input ">

                                                                        <input name="btc_address" readonly value="{{$address}}" type="text"
                                                                               class="form-control" id="address">

                                                                        <div class="qr-img qr-back">
                                                                            @if(isset($address))  {!! QrCode::size(300)->generate($address); !!} @endif
                                                                        </div>
                                                                        <a class="copy_address" type="button ">{{__('Copy')}}</a>
                                                                    </div>
                                                                </div>
                                                                <div class="check-box-list bank_payment payment_method d-none">
                                                                    <div class="form-group">
                                                                        <label>{{__('Select Bank')}}</label>
                                                                        <select name="bank_id" class="bank-id form-control " >
                                                                            <option value="">{{__('Select')}}</option>
                                                                            @if(isset($banks[0]))
                                                                                @foreach($banks as $value)
                                                                                    <option @if((old('bank_id') != null) && (old('bank_id') == $value->id)) @endif value="{{ $value->id }}">{{$value->bank_name}}</option>
                                                                                    <span class="text-danger"><strong>{{ $errors->first('bank_id') }}</strong></span>
                                                                                @endforeach
                                                                            @endif
                                                                        </select>
                                                                    </div>
                                                                    <div class="form-group buy_coin_address_input mt-4">
                                                                        <div id="file-upload" class="section-p">
                                                                            <input type="hidden" name="bank_deposit_id" value="">
                                                                            <input type="file" placeholder="0.00" name="sleep" value="" id="file" ref="file" class="dropify" data-default-file="{{asset('assets/images/placeholder-image.png')}}" />
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="check-box-list card_payment payment_method">
                                                                    <input id="nonce" name="payment_method_nonce" type="hidden" />
                                                                    <input type="hidden" name="pay_method_id" id="methodIdFormBrain">
                                                                    <div class="col-12 sds px-0">
                                                                        <div id="payment-form">
                                                                        </div>
                                                                        <div id="bt-dropin"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-5">
                                                            <div class="bank-details">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <button id="buy_button" type="submit" class="primary-btn ">{{__('Buy Now')}}</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('script')
    <!-- includes the Braintree JS client SDK -->
    <script src="https://js.braintreegateway.com/web/dropin/1.20.0/js/dropin.min.js"></script>
    <!-- includes jQuery -->
    <script src="{{asset('user/assets')}}/js/dropify.js"></script>
    <script src="{{asset('user/assets')}}/js/form-file-uploads.js"></script>
    <script>

        document.querySelector('.copy_address').addEventListener('click', function (event) {

            var copyTextarea = document.querySelector('#address');
            copyTextarea.focus();
            copyTextarea.select();

            try {
                var successful = document.execCommand('copy');
                VanillaToasts.create({
                    // title: 'Message Title',
                    text: '{{__('Address copied successfully')}}',
                    type: 'success',

                });
            } catch (err) {

            }
        });

    </script>


    <script>
        var form = document.querySelector('#buy_coin');
        var client_token = "{{settings('braintree_client_token')}}";
        braintree.dropin.create({
            authorization: client_token,
            selector: '#bt-dropin',
            // paypal: {
            //     flow: 'vault'
            // },
            // paypalCredit: {
            //     flow: 'checkout',
            // },
            venmo: {},
            applePay: {
                displayName: 'My Store',
                paymentRequest: {
                    total: {
                        label: 'My Store',
                        amount: '19.99'
                    },
                    // We recommend collecting billing address information, at minimum
                    // billing postal code, and passing that billing postal code with all
                    // Apple Pay transactions as a best practice.
                    requiredBillingContactFields: ["postalAddress"]
                }
            },
        }, function (createErr, instance) {
            if (createErr) {
                console.log('Create Error', createErr);
                return;
            }
            form.addEventListener('submit', function (event) {

                 $('#buy_button').attr('disabled',true);
                 var pay_type = document.querySelector('input[name="payment_type"]:checked').value;
               if (pay_type == '{{CARD}}'){
                   event.preventDefault();

               }

                instance.requestPaymentMethod(function (err, payload) {
                    if (err) {
                        console.log('Request Payment Method Error', err);
                        return;
                    }
// Add the nonce to the form and submit
                    document.querySelector('#nonce').value = payload.nonce;
                   // setTimeout('', 5000);
                    form.submit();
                });
            });
        });
    </script>

    <script>
        //bank details

        $('.bank-id').change(function () {
            var id = $(this).val();
            $.ajax({
                url: "{{route('bankDetails')}}?val=" + id,
                type: "get",
                success: function (data) {
                    console.log(data);
                    $('div.bank-details').html(data.data_genetare);
                },
                error: function (jqXHR, textStatus, errorThrown) {

                }
            });
        });
    </script>

@endsection