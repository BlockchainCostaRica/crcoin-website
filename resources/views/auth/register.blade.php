@extends('master')
@section('content')
    <div class="col-lg-6 acurate">
        <div class="form-right height-sectionregister  d-flex justify-content-center align-items-center text-center">
            <div class="form-areas register-t">
                <div class="form-top">
                    <h2>{{__('Sign Up')}}</h2>
                    <span>{{__('Create a new account.')}}</span>
                </div>
                <div class="row">
                    <div class="col-lg-12">
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
                    </div>
                    <div class="col-lg-12">
                        <form action="{{route('registerSave')}}" method="post">
                            @csrf
                            <div class="form-group">
                                <label for="">{{__('FIRST NAME')}}</label>
                                <input name="first_name" value="{{ old('first_name') }}" type="text" class="form-control" id="firstname"  placeholder="Type your first name">
                                @error('first_name')
                                    <p class="invalid-feedback">{{ $message }} </p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="">{{__('LAST NAME')}}</label>
                                <input type="text" name="last_name" class="form-control" value="{{old('last_name')}}" id="lastname"  placeholder="Type your last name">
                                @error('last_name')
                                    <p class="invalid-feedback">{{ $message }} </p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="">{{__('EMAIL')}}</label>
                                <input type="text" value="{{old('email')}}"  class="form-control" name="email" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Type your email here">
                                @error('email')
                                    <p class="invalid-feedback">{{ $message }} </p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="">{{__('PHONE NUMBER')}}</label>
                                <input name="phone" value="{{old('phone')}}" type="text" class="form-control" id="number"  placeholder="Type your phone number">
                                @error('phone')
                                    <p class="invalid-feedback">{{ $message }} </p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="">{{__('PASSWORD')}}</label>
                                <div class="position-relative">
                                    <input name="password"  autocomplete="new-password" type="password" class="form-control look-pass" value="" id="exampleInputPassword1" placeholder="Type your password">
                                    <div class="rev password-show">
                                        <i class="fa fa-eye-slash toggle-password" onclick="showHidePassword('old_password')" ></i>
                                    </div>
                                </div>
                                @error('password')
                                    <p class="invalid-feedback">{{ $message }} </p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="">{{__('CONFIRM PASSWORD')}}</label>
                                <div class="position-relative">
                                    <input name="password_confirmation" type="password" class="form-control look-pass-1" value="" id="confirmpassword" placeholder="Type your password again">
                                    <div class="rev-1 password-show"><i class="fa fa-eye-slash toggle-password" onclick="showHidePassword('old_password')" ></i></div>
                                </div>
                                @error('password_confirmation')
                                    <p class="invalid-feedback">{{ $message }} </p>
                                @enderror
                            </div>
                            @if( app('request')->input('ref_code'))
                                {{Form::hidden('ref_code', app('request')->input('ref_code') )}}
                            @endif
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Sign Up</button>
                            </div>
                            <p class="dont-account">Already have an account ? <a href="{{route('login')}}">Sign In</a></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
    