@extends('Admin.master',['menu'=>'notification'])
@section('title',__('User Notification'))
@section('content')
    <div class="container">
        <div class="ckeditor-wrap">
            <div class="row">
                <div class="col-12">
                    <div class="referral-title">
                        <h4 class="">{{__('Email Send To Users')}}</h4>
                    </div>
                    <div class="pull-right">
                        <a class="btn btn-primary clear-record ico-add-user-btn mb-md-0 mb-3 text-right" href="{{ route('clearEmailRecord') }}">
                            {{ __('Clear Record') }}
                        </a>
                    </div>
                </div>
            </div>
            <div class="form-area label-span p-0">
                <div class="row">
                    <div class="col-lg-12">
                    <form action="{{route('adminSendMailProcess')}}" method="post">
                        @csrf
                        <span>{{__('Header Text')}}</span>
                        <div class="form-group">
                            <textarea name="email_headers" id="text-header" placeholder="{{__('Email header text')}}" class="textarea form-control">{{old('email_headers')}}</textarea>
                        </div>
                        <span>{{__('Subject')}}</span>
                        <div class="form-group">
                            <input type="text" class="form-control" id="exampleInputEmail1" value="{{old('subject')}}"  name="subject" placeholder="{{__('Subject')}}">
                        </div>
                        <span>{{__('Message')}}</span>
                        <div class="form-group">
                            <textarea name="message" placeholder="{{__('Email message body')}}" id="text-message" class="textarea form-control">{{old('message')}}</textarea>
                        </div>
                        <span>{{__('Role')}}</span>
                        <div class="form-group">
                            <select class="wide" name="role[]" id="users" class="form-control">
                                <option value=1> Admin </option>
                                <option value=2> User </option>
                            </select>
                        </div>
                        <span>{{__('Email Type')}}</span>
                        <div class="form-group">
                            <input type="text" class="form-control" id="exampleInputEmail1" value="{{old('email_type')}}" name="email_type" placeholder="{{__('Email type')}}">
                        </div>
                        <span>{{__('Footer Text')}}</span>
                        <div class="form-group">
                            <textarea name="footers" id="text-footer" placeholder="{{__('Email footer text')}}" class="textarea form-control">{{old('footers')}}</textarea>
                        </div>
                        <div class="form-group">
                            <button class="button-primary" type="submit">{{__('Send')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        </div>
    </div>
@endsection
@section('before-style')
@endsection
@section('script')
    <script>
        $("#clear").on("click", function(){
            return confirm("Do you want to clear all record ?");
        });
    </script>
    <script src="{{asset('assets/js/ckeditor.js')}}"></script>
    <script>
        ClassicEditor
            .create( document.querySelector( '#text-message',function () {
                config.width = 200;
            } ) )
            .catch( error => {
                console.error( error );
            } );
    </script>

{{--    <script src="{{asset('vendor/unisharp/laravel-ckeditor/ckeditor.js')}}"></script>--}}
    <script>
        // CKEDITOR.replace( 'text-header');
        // CKEDITOR.replace( 'text-message');
        // CKEDITOR.replace( 'text-footer');
    </script>
@endsection