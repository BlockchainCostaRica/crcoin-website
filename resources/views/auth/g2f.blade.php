@extends('master')
@section('content')
    <div class="col-lg-6 acurate">
        <div class="form-right height-section d-flex justify-content-center align-items-center text-center">
            <div class="form-areas">
                <div class="form-top">
                    <h2>{{__('Two Factor Authentication')}}</h2>
                    <span>{{__('Open your authentication app and enter the code for')}} {{env('APP_NAME')}}</span>
                </div>
                <form action="{{ route('g2fVerify') }}" method="post" >
                    @csrf
                    <div class="form-group">
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

                    <div class="form-group">
                        <input type="text" value="{{ old('code') }}" name="code" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="{{__('Code')}}">
                        @error('code')
                        <p class="invalid-feedback">{{ $message }} </p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">{{__('Verify')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection