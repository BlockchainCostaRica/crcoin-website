@extends('User.master',['menu'=>'settings','sub_menu'=>'settings'])
@section('style')
@endsection
@section('content')
    <div class="setting-page-area">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="setting-page-inner">
                        <div class="google-authetication">
                            <div class="row">
                                <div class="col-lg-10 offset-lg-1">
                                    <div class="settingpage-title">
                                        <h3>Google Authentication Settings</h3>
                                    </div>
                                    <div class="Android-icon">
                                        <img src="assets/images/Android-icon.png" alt="">
                                    </div>
                                    <div class="google-authetication-content">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="authenticator-appa">
                                                    <h4>Authenticator app</h4>
                                                    <p>Use the Authenticator app to get free verification codes, even when your phone is offline. Available for Android and iPhone.</p>

                                                    @if( empty(\Illuminate\Support\Facades\Auth::user()->google2fa_secret))
                                                    <a href="javascript:" data-toggle="modal" data-target="#exampleModal" class="primary-btn">{{__('Set up')}}</a>

                                                        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <form method="post" action="{{route('g2fSecretSave')}}">
                                                                    @csrf
                                                                    <input type="hidden" name="google2fa_secret" value="{{$google2fa_secret}}">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title" id="exampleModalLabel">{{__('Google Authentication')}}</h5>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <div class="row">
                                                                                <div class="col-4">
                                                                                    <img src="{{$qrcode}}" alt="">
                                                                                </div>
                                                                                <div class="col-8">
                                                                                    <p>{{__('Open your Google Authenticator app, and scan Your secret code and enter the 6-digit code from the app into the input field')}}</p>
                                                                                    <input placeholder="{{__('Code')}}" type="text" class="form-control" name="code">
                                                                                </div>

                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Close')}}</button>
                                                                            <button type="submit" class="btn btn-primary">{{__('Verify')}}</button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    @else
                                                    <a href="javascript:" data-toggle="modal" data-target="#exampleModalRemove" class="primary-btn">{{__('Remove google secret key')}}</a>

                                                        <div class="modal fade" id="exampleModalRemove" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <form method="post" action="{{route('g2fSecretSave')}}">
                                                                    @csrf
                                                                    <input type="hidden" name="remove" value="1">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title" id="exampleModalLabel">{{__('Google Authentication')}}</h5>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <div class="row">

                                                                                <div class="col-12">
                                                                                    <p>{{__('Open your Google Authenticator app and enter the 6-digit code from the app into the input field to remove the google secret key')}}</p>
                                                                                    <input placeholder="{{__('Code')}}" type="text" class="form-control" name="code">
                                                                                </div>

                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Close')}}</button>
                                                                            <button type="submit" class="btn btn-primary">{{__('Verify')}}</button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="security-area">
                                                    <h4 class="ss">Security</h4>
                                                    <p>Please on this option to enable two factor authentication at login.</p>
                                                    <form method="post" action="{{route('googleLoginEnable')}}">
                                                      @csrf

                                                        <label class="switch">
                                                            <input {{(\Illuminate\Support\Facades\Auth::user()->g2f_enabled == 1) ? 'checked' : ''}} onclick="$(this).closest('form').submit();" type="checkbox">
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="preference-settinga">
                            <div class="row">
                                <div class="col-lg-10 offset-lg-1">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="section-title">
                                                <h3>Preference Settings</h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-area">
                                                <form action="#">
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <div class="form-group">
                                                                <label>Language</label>
                                                                <select class="wide">
                                                                    <option data-display="English">English</option>
                                                                    <option value="1">English</option>
                                                                    <option value="2">English</option>
                                                                    <option value="4">English</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="form-group">
                                                                <label>Currency</label>
                                                                <select class="wide">
                                                                    <option data-display="Currency">Currency</option>
                                                                    <option value="1">Currencyn</option>
                                                                    <option value="2">Currency</option>
                                                                    <option value="4">Currency</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12">
                                                            <button type="submit" class="primary-btn">Save</button>
                                                        </div>
                                                    </div>
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