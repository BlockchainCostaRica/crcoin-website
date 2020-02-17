@extends('master')
@section('content')
    <div class="col-lg-6 col-offset-2 acurate">
        <div class="form-right height-section  d-flex justify-content-center align-items-center text-center">
            <div class="form-areas">
                <div class="form-top">
                    <h2>{{__('Reset Password')}}</h2>
                </div>
                <form action="{{ route('resetPasswordSave') }}" method="post" >
                    @csrf
                    @if(session()->has('dismiss'))
                        <div class="alert alert-danger">
                            <strong>{{session('dismiss')}}</strong>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    @if(session()->has('success'))
                        <div class="alert alert-success">
                            <strong>{{session('success')}}</strong>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    <div class="form-group ">
                           
                        <label>CODE</label>
                        <input id="token" autocomplete="off"  type="text" placeholder="{{__('Verification code')}}"   class="form-control" autocomplete="off" name="token" value=""  >
                        @if(Session::has('resend_email'))
                            <a class="btn btn-code" href="{{ route('sendToken').'?email='.Session('resend_email') }}">
                                {{ __('Resend code') }}
                            </a>
                        @endif
                        @if ($errors->has('token'))
                            <p class="invalid-feedback">{{ $errors->first('token') }}</p>
                        @endif
                    </div>
                    <div class="form-group ">
                       <label>PASSWORD</label>
                       <div class="position-relative">
                            <input id="password"  type="password" autocomplete="off" placeholder="{{__('Password')}}" class="look-pass-l form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" >
                            <div class="pass-l eye password-show"><i class="fa fa-eye-slash toggle-password" onclick="showHidePassword('old_password')" ></i></div>
                        </div>
                        @if ($errors->has('password'))
                            <p class="invalid-feedback">{{ $errors->first('password') }}</p>
                        @endif
                    </div>
                    <div class="form-group ">
                        <label>REPEAT PASSWORD</label>
                        <div class="position-relative">
                            <input id="password-confirm" autocomplete="off" type="password" class="form-control look-pass-m" placeholder="{{__('Password confirm')}}" name="password_confirmation" >
                            <div class="pass-m eye password-show"><i class="fa fa-eye-slash toggle-password" onclick="showHidePassword('old_password')" ></i></div>
                        </div>
                        @if ($errors->has('password_confirmation'))
                            <p class="invalid-feedback">{{ $errors->first('password_confirmation') }}</p>
                        @endif
                    </div>
                    <div class="form-group ">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Reset Password') }}
                        </button>
                    </div>
                    <p class="dont-account"><a href="{{route('login')}}">Sign In</a></p>
                </form>
            </div>
        </div>
    </div>
@endsection