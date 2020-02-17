@extends('Admin.master',['menu'=>'setting','sub_menu'=>'cpc'])
@section('title',__('Landing Page content'))
@section('style')
@endsection
@section('content')
    <!-- coin-area start -->
    <div class="coin-area">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="admin-wrapper">
                       <div class="userlist-wrap sub-wrapper form-style">
                           <ul class="nav user-menu justify-content-start">
                               <li><a  href="{{route('adminCmsSetting',['tab'=>'hero'])}}">{{__('Header Setting')}}</a></li>
                               <li><a class="active"  href="{{route('adminCmsSetting',['tab'=>'static'])}}"> {{__('Static Content')}}</a></li>
                           </ul>
                           <h4 >{{$title}}</h4>
                           <div class="form-area">
                                <div class="row">
                                    <div class="col-12">
                                        <form method="post" enctype="multipart/form-data" action="{{route('adminCmsLandingPpageSaveContent')}}">
                                        @csrf
                                            <div class="userlist-wrap adduser-wrap form-style">
                                                <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <label for="">{{__('Page Title')}}</label>
                                                        <input type="text" class="form-control"  @if(isset($item))value="{{$item->title}}" @else value="{{old('title')}}" @endif  name="title" placeholder="{{__('Text')}}">
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <label for="">{{__('Image')}}</label>
                                                        <div class="file-design">
                                                            <input type="file" class="form-control custom-file-upload"  name="image" placeholder="{{__('Text')}}">
                                                            <span>Chosce File</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <label for="">{{__('Priority')}}</label>
                                                        <input type="text" class="form-control"  @if(isset($item))value="{{$item->priority}}" @else value="{{old('title')}}" @endif  name="priority" placeholder="{{__('Text')}}">
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <label for="">{{__('Description')}}</label>
                                                        <textarea name="description" id="text-message" class="textarea" class="form-control">@if(isset($item)){!! $item->description !!} @else {{old('description')}} @endif</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-lg-2 col-2 mt-3">
                                                    <div class="form-group">
                                                        @if(isset($item))<input type="hidden" name="edit_id" value="{{$item->id}}">@endif
                                                        <button class="button-primary">{{__('Submit')}}</button>
                                                    </div>
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
    <!-- .user-area end -->
@endsection
@section('script')
    <script src="{{asset('vendor/unisharp/laravel-ckeditor/ckeditor.js')}}"></script>
    <script>
        CKEDITOR.replace( 'text-message');
    </script>
@endsection