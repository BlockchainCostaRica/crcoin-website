@extends('master')
@section('content')
    <div class="col-lg-6 acurate">
        <div class="form-right height-section reset-st d-flex justify-content-center align-items-center text-center">
            <div class="form-areas">
                <div class="form-top">
                    <h2>Forgot Password ?</h2>
                </div>
                <form action="{{ route('sendToken') }}" method="post" >
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
                        <input type="text" value="{{ old('email') }}" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Email">
                        @error('email')
                            <p class="invalid-feedback">{{ $message }} </p>
                        @enderror
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Send</button>
                    </div>
                    <p class="dont-account"><a href="{{route('login')}}">Sign In</a></p>
                </form>
            </div>
        </div>
    </div>
@endsection