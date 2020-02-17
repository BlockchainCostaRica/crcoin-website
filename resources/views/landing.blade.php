@extends('master')
@section('content')
    <div class="col-lg-6 acurate">
        <div class="form-right height-section d-flex justify-content-center align-items-center text-center">
            <div class="form-areas">
                <div class="form-top">
                    <h2>{{__('Sign In')}}</h2>
                </div>
                <form action="{{ route('postLogin') }}" method="post" >
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

                    <div class="form-group">
                        <label>{{__('EMAIL')}}</label>
                        <input type="text" value="{{ old('email') }}" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Type your email here">
                        @error('email')
                            <p class="invalid-feedback">{{ $message }} </p>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>{{__('PASSWORD')}}</label>
                        <div class="position-relative">
                            <input name="password" type="password" value="" class="form-control look-pass-a" id="exampleInputPassword1" placeholder="Type your password">
                            <div class="eye password-show"><i class="fa fa-eye-slash toggle-password" onclick="showHidePassword('old_password')" ></i></div>
                        </div>
                        @error('password')
                            <p class="invalid-feedback">{{ $message }} </p>
                        @enderror
                    </div>

                    <a class="forgot-password " href="{{ route('forgotPassword') }}">
                        {{ __('Forgot Your Password?') }}
                    </a>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">{{__('Sign In')}}</button>
                    </div>
                    <p class="dont-account">Don’t have account ? <a href="{{route('signup')}}">{{__('Sign Up')}}</a></p>
                </form>
                <div class="default-access-wrapper">
                    <div class="auth-btn user">User Login</div>
                    <div class="auth-btn admin">Admin Login</div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        var email = $('input#exampleInputEmail1');
        var pass = $('input#exampleInputPassword1');

        $(window).on('load', function () {
            email.val('user@bitcr.co');
            pass.val('12345');
        });

        $('.user').on('click', function () {
            email.val('user@bitcr.co');
            pass.val('12345');
        });


        $('.admin').on('click', function () {
            email.val('admin@bitcr.co');
            pass.val('12345');
        })
    </script>
@endsection