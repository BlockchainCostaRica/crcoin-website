@extends('Admin.master',['menu'=>'setting','sub_menu'=>'setting'])
@section('title',__('General Settings'))
@section('style')
@endsection
@section('content')
    <div class="user-page-area">
        <div class="container-fluid">
            <div class="page-wraper section-padding">
                <div class="row">
                    <div class="col-12">
                        <div class="single-tab">
                            <div class="section-body ">
                                <ul class="nav  nav-pills" id="tab" role="tablist">
                                    <li><a class=" @if(isset($tab) && $tab=='general') active @endif nav-link "
                                           data-toggle="pill" role="tab" data-controls="general" aria-selected="true"
                                           href="#general_settings">{{__('General Settings')}}</a></li>
                                    <li><a class=" @if(isset($tab) && $tab=='braintree') active @endif nav-link  "
                                           data-toggle="pill" role="tab" data-controls="braintree" aria-selected="true"
                                           href="#braintree">{{__('Braintree Settings')}}</a></li>
                                    <li><a class=" @if(isset($tab) && $tab=='sms') active @endif nav-link  "
                                           data-toggle="pill" role="tab" data-controls="sms" aria-selected="true"
                                           href="#sms">{{__('Sms Settings')}}</a></li>
                                    <li><a class=" @if(isset($tab) && $tab=='api') active  @endif nav-link "
                                           data-toggle="pill" role="tab" data-controls="apisettings"
                                           href="#apisettings"> {{__('API Settings')}}</a></li>
                                    <li><a class=" @if(isset($tab) && $tab=='fee') active  @endif nav-link"
                                           data-toggle="pill" role="tab" data-controls="fees_settings"
                                           href="#fees_settings"></span>{{__('Fees Settings')}}</a></li>
                                    <li><a class=" @if(isset($tab) && $tab=='wdrl') active @endif nav-link"
                                           data-toggle="pill" role="tab" data-controls="withdrawl_settings"
                                           href="#withdrawl_settings"></span>{{__('Withdrawal Settings')}}</a></li>
                                </ul>
                                <div class="tab-content tab-pt-n" id="tabContent">
                                    <!-- genarel-setting start-->
                                    <div class="tab-pane fade show @if(isset($tab) && $tab=='general')  active @endif " id="general" role="tabpanel" aria-labelledby="general-setting-tab">
                                        <div class="form-area plr-65">
                                            <h4>{{__('Setting Manager')}}</h4>
                                            <form action="{{route('adminCommonSettings')}}" method="post" enctype="multipart/form-data">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-lg-6 col-12  mt-20">
                                                        <div class="form-group">
                                                            <label for="#">{{__('Company Name')}}</label>
                                                            <input type="text" name="company_name" placeholder="{{__('Company Name')}}" value="{{$settings['app_title']}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-12  mt-20">
                                                        <div class="form-group">
                                                            <label for="#">{{__('Copyright Text')}}</label>
                                                            <input type="text" name="copyright_text" placeholder="{{__('Copyright Text')}}" value="{{$settings['copyright_text']}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-12  mt-20">
                                                        <div class="form-group">
                                                            <label for="#">{{__('Coin Price')}}</label>
                                                            <input type="text" name="coin_price" placeholder="{{__('coin price')}}" value="{{isset($settings['coin_price']) ? $settings['coin_price'] : ''}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-12  mt-20">
                                                        <div class="form-group">
                                                            <label for="#">{{__('Company Coin Address')}}</label>
                                                            <input type="text" name="admin_coin_address" placeholder="{{__('Company coin address')}}" value="{{isset($settings['admin_coin_address']) ? $settings['admin_coin_address'] : ''}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-12 mt-20">
                                                        <div class="form-group">
                                                            <label for="#">{{__('Primary Email Settings')}} </label>
                                                            <input type="email" name="mail_from" placeholder="{{__('Email')}}" value="{{$settings['mail_from']}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-12 mt-20">
                                                        <div class="form-group">
                                                            <label for="#">{{__('Number of confirmation for Notifier deposit')}} </label>
                                                            <input type="text" name="number_of_confirmation" placeholder="" value="{{$settings['number_of_confirmation']}}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="uplode-img-list">
                                                    <div class="row">
                                                        <div class="col-lg-4 mt-20">
                                                            <div class="single-uplode">
                                                                <div class="uplode-catagory">
                                                                    <span>{{__('Logo')}}</span>
                                                                </div>
                                                                <div class="form-group buy_coin_address_input ">
                                                                    <div id="file-upload" class="section-p">
                                                                        <input type="hidden" name="bank_deposit_id" value="">
                                                                        <input type="file" placeholder="0.00" name="logo" value="" id="file" ref="file" class="dropify" @if(isset($settings['logo']) && (!empty($settings['logo'])))  data-default-file="{{asset(path_image().$settings['logo'])}}" @endif />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 mt-20">
                                                            <div class="single-uplode">
                                                                <div class="uplode-catagory">
                                                                    <span>{{__('Login Logo')}}</span>
                                                                </div>
                                                                <div class="form-group buy_coin_address_input ">
                                                                    <div id="file-upload" class="section-p">
                                                                        <input type="hidden" name="bank_deposit_id" value="">
                                                                        <input type="file" placeholder="0.00" name="login_logo" value="" id="file" ref="file" class="dropify" @if(isset($settings['login_logo']) && (!empty($settings['login_logo'])))  data-default-file="{{asset(path_image().$settings['login_logo'])}}" @endif />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 mt-20">
                                                            <div class="single-uplode">
                                                                <div class="uplode-catagory">
                                                                    <span>{{__('Fevicon')}}</span>
                                                                </div>
                                                                <div class="form-group buy_coin_address_input ">
                                                                    <div id="file-upload" class="section-p">
                                                                        <input type="file" placeholder="0.00" name="favicon" value="" id="file" ref="file" class="dropify" @if(isset($settings['favicon']) && (!empty($settings['favicon'])))  data-default-file="{{asset(path_image().$settings['favicon'])}}" @endif />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    @if(isset($itech))
                                                        <input type="hidden" name="itech" value="{{$itech}}">
                                                    @endif
                                                    <div class="col-lg-2 col-12 mt-20">
                                                        <button class="button-primary">{{__('Update')}}</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <!-- genarel-setting end-->
                                    </div>
                                    <!-- API Settings start-->
                                    <div class="tab-pane fade @if(isset($tab) && $tab=='api')show active @endif" id="apisettings" role="tabpanel" aria-labelledby="apisetting-tab">
                                        <div class="form-area">
                                            <div class="form-style">
                                                <form action="{{route('adminCoinApiSettings')}}" method="post">
                                                    @csrf
                                                    <div class="Braintree-Settings plr-65">
                                                        <h4>My api details</h4>
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-lg-6 col-12  mt-20">
                                                                    <div class="form-group">
                                                                        <label for="#">{{__('User')}}</label>
                                                                        <input type="text" name="coin_api_user"
                                                                               autocomplete="off" placeholder="Username"
                                                                               value="{{settings('coin_api_user')}}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6 col-12 mt-20">
                                                                    <div class="form-group">
                                                                        <label for="#">{{__('Password')}}</label>
                                                                        <input type="password" name="coin_api_pass"
                                                                               autocomplete="off" placeholder="Password"
                                                                               value="{{settings('coin_api_pass')}}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6 col-12 mt-20">
                                                                    <div class="form-group">
                                                                        <label for="#">{{__('Host Name')}}</label>
                                                                        <input type="text" name="coin_api_host"
                                                                               autocomplete="off" placeholder="Host"
                                                                               value="{{settings('coin_api_host')}}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6 col-12 mt-20">
                                                                    <div class="form-group">
                                                                        <label for="#">{{__('Port')}}</label>
                                                                        <input type="text" name="coin_api_port"
                                                                               autocomplete="off" placeholder="Port"
                                                                               value="{{settings('coin_api_port')}}">
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="Braintree-Settings plr-65">
                                                        <h4>BTC api details</h4>
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-lg-6 col-12  mt-20">
                                                                    <div class="form-group">
                                                                        <label for="#">{{__('User')}}</label>
                                                                        <input type="text" name="btc_coin_api_user"
                                                                               autocomplete="off" placeholder="Username"
                                                                               value="{{settings('coin_api_user')}}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6 col-12 mt-20">
                                                                    <div class="form-group">
                                                                        <label for="#">{{__('Password')}}</label>
                                                                        <input type="password" name="btc_coin_api_pass"
                                                                               autocomplete="off" placeholder="Password"
                                                                               value="{{settings('coin_api_pass')}}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6 col-12 mt-20">
                                                                    <div class="form-group">
                                                                        <label for="#">{{__('Host Name')}}</label>
                                                                        <input type="text" name="btc_coin_api_host"
                                                                               autocomplete="off" placeholder="Host"
                                                                               value="{{settings('coin_api_host')}}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6 col-12 mt-20">
                                                                    <div class="form-group">
                                                                        <label for="#">{{__('Port')}}</label>
                                                                        <input type="text" name="btc_coin_api_port"
                                                                               autocomplete="off" placeholder="Port"
                                                                               value="{{settings('coin_api_port')}}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-2">
                                                            <button class="button-primary"
                                                                    type="submit">{{__('Update')}}</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <!-- API Settings end-->
                                    </div>
                                    <!-- Fees Settings start-->
                                    <div class="tab-pane fade @if(isset($tab) && $tab=='braintree')show active @endif" id="braintree" role="tabpanel" aria-labelledby="braintree-tab">
                                        <div class="form-area ">
                                            <div class="form-style">
                                                <form action="{{route('adminBraintreeApiSettings')}}" method="post">
                                                    @csrf
                                                    <h4>Braintree details</h4>
                                                    <div class="card-body p-0">
                                                        <div class="row">
                                                            <div class="col-lg-6 col-12  mt-20">
                                                                <div class="form-group">
                                                                    <label for="#">{{__('Client Token')}}</label>
                                                                    <input type="text" name="braintree_client_token"
                                                                           autocomplete="off" placeholder=""
                                                                           value="{{settings('braintree_client_token')}}">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6 col-12 mt-20">
                                                                <div class="form-group">
                                                                    <label for="#">{{__('Environment')}}</label>
                                                                    <select class="wide" name="braintree_environment">
                                                                        <option @if(settings('braintree_environment') == 'sandbox') selected
                                                                                @endif value="sandbox">Sandbox
                                                                        </option>
                                                                        <option @if(settings('braintree_environment') == 'production') selected
                                                                                @endif value="production">Production
                                                                        </option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6 col-12 mt-20">
                                                                <div class="form-group">
                                                                    <label for="#">{{__('Merchant ID  ')}}</label>
                                                                    <input type="text" name="braintree_merchant_id"
                                                                           autocomplete="off" placeholder="Host"
                                                                           value="{{settings('braintree_merchant_id')}}">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6 col-12 mt-20">
                                                                <div class="form-group">
                                                                    <label for="#">{{__('Public Key')}}</label>
                                                                    <input type="text" name="braintree_public_key"
                                                                           autocomplete="off" placeholder="Port"
                                                                           value="{{settings('braintree_public_key')}}">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6 col-12 mt-20">
                                                                <div class="form-group">
                                                                    <label for="#">{{__('Private Key')}}</label>
                                                                    <input type="text" name="braintree_private_key"
                                                                           autocomplete="off" placeholder="Port"
                                                                           value="{{settings('braintree_private_key')}}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-2">
                                                            <button class="button-primary"
                                                                    type="submit">{{__('Update')}}</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <!-- API Settings end-->
                                    </div>
                                    <div class="tab-pane fade @if(isset($tab) && $tab=='sms')show active @endif" id="sms" role="tabpanel" aria-labelledby="braintree-tab">
                                        <div class="form-area">
                                            <div class="form-style">
                                                <form action="{{route('adminSaveSmsSettings')}}" method="post">
                                                    @csrf
                                                    <div class="Braintree-Settings plr-65">
                                                        <h4>SMS getway settings</h4>

                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <span>{{__('Use for sms getway')}}</span>
                                                                    <div class="radio ys">
                                                                        <label><input type="radio"
                                                                                      {{(settings('sms_getway_name') == 'twillo') ? 'checked' : ''}} value="twillo"
                                                                                      name="sms_getway_name">TWILLO</label>
                                                                    </div>
                                                                    <div class="radio ys">
                                                                        <label><input type="radio"
                                                                                      {{(settings('sms_getway_name') == 'clickatell') ? 'checked' : ''}}  value="clickatell"
                                                                                      name="sms_getway_name">CLICKATELL</label>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-lg-6 Braintree-Settings col-12">
                                                                    <div class="col-lg-12 col-12  mt-20">
                                                                        {{__('Twillo Settings')}}
                                                                    </div>
                                                                    <div class="col-lg-12 col-12  mt-20">
                                                                        <div class="form-group">
                                                                            <label for="#">{{__('Twillo ID')}}</label>
                                                                            <input type="text" name="twilo_id"
                                                                                   autocomplete="off"
                                                                                   placeholder="Username"
                                                                                   value="{{settings('twilo_id')}}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-12 col-12 mt-20">
                                                                        <div class="form-group">
                                                                            <label for="#">{{__('Twillo token')}}</label>
                                                                            <input type="password" name="twilo_token"
                                                                                   autocomplete="off"
                                                                                   placeholder="Password"
                                                                                   value="{{settings('twilo_token')}}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-12 col-12 mt-20">
                                                                        <div class="form-group">
                                                                            <label for="#">{{__('Sender Name')}}</label>
                                                                            <input type="text" name="sender_phone_no"
                                                                                   autocomplete="off" placeholder="Host"
                                                                                   value="{{settings('sender_phone_no')}}">
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-lg-12 mt-20">
                                                                        <div class="form-group">
                                                                            <label for="#">{{__('SSl Verify')}} </label>
                                                                            <select class="wide" name="ssl_verify">
                                                                                <option @if(isset($settings['ssl_verify']) && $settings['ssl_verify']=='en') selected
                                                                                        @endif value="no">No
                                                                                </option>
                                                                                <option @if(isset($settings['ssl_verify']) && $settings['ssl_verify']=='en') selected
                                                                                        @endif value="yes">Yes
                                                                                </option>
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                                <div class="col-lg-6 col-12">
                                                                    <div class="col-lg-12 col-12  mt-20">
                                                                        {{__('Clickatell Settings')}}
                                                                    </div>
                                                                    <div class="col-lg-12 col-12  mt-20">
                                                                        <div class="form-group">
                                                                            <label for="#">{{__('CLICKATELL api key')}}</label>
                                                                            <input type="text" name="clickatell_api_key"
                                                                                   autocomplete="off" placeholder=""
                                                                                   value="{{settings('clickatell_api_key')}}">
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                                <div class="col-12">
                                                                    <button class="button-primary"
                                                                            type="submit">{{__('Update')}}</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <!-- API Settings end-->
                                    </div>
                                    <!-- Fees Settings start-->
                                    <div class="tab-pane fade @if(isset($tab) && $tab=='fee')show active @endif" id="fees_settings">
                                        <div class="form-area">
                                            <div class="form-style">
                                                <h4>{{__('Fees Settings')}}</h4>
                                                <form method="post" action="{{route('adminSendFeesSettings')}}">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col-lg-6 col-12  mt-20">
                                                            <div class="form-group">
                                                                <label for="#">{{__('Withdrawal Fees Method')}}</label>
                                                                <select class="wide" name="send_fees_type">
                                                                    @foreach(sendFeesType() as $key_sft=>$value_sft)
                                                                        <option value="{{$key_sft}}" @if($settings['send_fees_type']==$key_sft) selected @endif >{{$value_sft}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-12 mt-20">
                                                            <div class="form-group">
                                                                <label for="#">{{__('Withdrawal Fixed Fees')}}</label>
                                                                <input type="text" name="send_fees_fixed" placeholder="{{__('Send Coin Fixed Fees')}}" value="{{$settings['send_fees_fixed']}}">
                                                            </div>
                                                        </div>
                                                        <div class="col-6 mt-20">
                                                            <div class="form-group">
                                                                <label for="#">{{__('Withdrawal Fees Percent')}}</label>
                                                                <p class="fees-wrap">
                                                                    <input type="text" name="send_fees_percentage" placeholder="{{__('Currency Deposit Fees in Percent')}}" value="{{$settings['send_fees_percentage']}}">
                                                                    <span>%</span>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-2 col-12 mt-20">
                                                            <button class="button-primary">{{__('Update')}}</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="form-area">
                                            <div class="form-style">
                                                <h4>{{__('Referral Fees Settings')}}</h4>
                                                <form method="post" action="{{route('adminReferralFeesSettings')}}">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col-lg-6 col-12  mt-20">
                                                            <div class="form-group">
                                                                <label class="">{{__('Maximum Affiliation Level') }}</label>
                                                                <input type="number" class="" name="max_affiliation_level" min="1" max="10" value="{{ old('max_affiliation_level', isset($settings['max_affiliation_level']) ? $settings['max_affiliation_level'] : 3) }}">
                                                            </div>
                                                        </div>
                                                        @for($i = 1; $i <=10 ; $i ++)
                                                            <div class="col-lg-6 col-12  mt-20">
                                                                <div class="form-group">
                                                                    <label for="#">{{ __('Level') }} {{$i}} (%)</label>
                                                                    @php( $slug_name = 'fees_level'.$i)
                                                                    <p class="fees-wrap">
                                                                        <input type="text" class="number_only" name="{{$slug_name}}" value="{{ old($slug_name, isset($settings[$slug_name]) ? $settings[$slug_name] : 0) }}">
                                                                        <span>%</span>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        @endfor
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-2 col-12 mt-20">
                                                            <button class="button-primary">{{__('Update')}}</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <!-- Fees Settings end-->
                                    </div>
                                    <!-- Send Coin Limit Settings start-->
                                    <div class="tab-pane fade @if(isset($tab) && $tab=='wdrl')show active @endif" id="withdrawl_settings">
                                        <div class="form-area">
                                            <div class="userlist-wrap custom-wrapper custom-wrapper-2 form-style">
                                                <h4>{{__('Send Coin Limit Settings')}}</h4>
                                                <form method="post" action="{{route('adminWithdrawalSettings')}}">
                                                    @csrf
                                                    <label for="#">{{__('Max. Send Limit')}}</label>
                                                    <div class="row">
                                                        <div class="col-lg-6 col-12">
                                                            <div class="form-group">
                                                                <input type="text" name="max_send_limit" placeholder="{{__('Send Limit')}}" value="{{$settings['max_send_limit']}}">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-2 col-12">
                                                            <button class="button-primary">{{__('Update')}}</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <!-- Send Coin Limit Settings end-->
                                    </div>
                                    <div class="tab-pane fade" id="coins">
                                        <div class=" form-style">
                                            <h4>{{__('Coin List')}}</h4>
                                            <div class="walletview-wrap form-style table-style">
                                                <table class="table-responsive" id="cointable" width="100%">
                                                    <thead>
                                                    <tr>
                                                        <th>{{__('Name')}}</th>
                                                        <th>{{__('Is Primary?')}}</th>
                                                        <th>{{__('Created At')}}</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <!-- Send Coin Limit Settings end-->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- .user-area end -->
@endsection
@section('script')
    <script>
        $('.nav-link').on('click',function () {
            $('.nav-link').removeClass('active');
            $(this).addClass('active');
          var str = '#'+$(this).data('controls');
            $('.tab-pane').removeClass('show active');
            $(str).addClass('show active');
        });
    </script>
    <script>
        $('#cointable').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 10,
            responsive: true,
            ajax: '{{route('adminSettings')}}',
            order: [2, 'desc'],
            autoWidth:false,
            language: {
                paginate: {
                    next: 'Next &#8250;',
                    previous: '&#8249; Previous'
                }
            },
            columns: [
                {"data": "name"},
                {"data": "is_default"},
                {"data": "created_at"}
            ]
        });
    </script>
@endsection