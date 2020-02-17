@extends('Admin.master',['menu'=>'cp'])
@section('title',isset($cp) ? 'Update Custom Page' : 'Add Custom Page')
@section('style')
@endsection
@section('content')
    <!-- coin-area start -->
    <div class="user-area">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="admin-wrapper custom-wrapper">
                        <h3 class="subtitle mb-4">{{__('Custom Page')}}</h3>
                        <div class="form-area p-0">
                            <div class="row">
                                <div class="col-12">
                                  <form action="{{route('adminCustomPageSettingSave')}}" method="post" enctype="multipart/form-data" >
                                      @if(!empty($cp->id))
                                      <input type="hidden" name="edit_id" value="{{$cp->id}}">
                                      @endif
                                    @csrf
                                    <div class="userlist-wrap adduser-wrap form-style">
                                        <div class="form-group">
                                            <label for="#">{{__('Menu name')}}</label>
                                            <input type="text" name="menu" placeholder="{{__('Menu name')}}" @if(isset($cp))value="{{$cp->key}}" @else value="{{old('key')}}" @endif>
                                        </div>
                                        <div class="form-group">
                                            <label for="#">{{__('Page Title')}}</label>
                                            <input type="text" name="title" placeholder="{{__('Text')}}" @if(isset($cp))value="{{$cp->title}}" @else value="{{old('title')}}" @endif>
                                        </div>
                                        <div class="form-group">
                                            <label for="">{{__('Description')}}</label>
                                            <textarea rows="10" name="description" id="editor" class="textarea" class="form-control">@if(isset($cp)){!! $cp->description !!} @else {{old('description')}} @endif</textarea>
                                        </div>
                                        <div class=" mt-20">
                                            <button class="button-primary"> @if(isset($cp)) {{__('Update')}} @else {{__('Submit')}} @endif</button>
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
    <!-- .user-area end -->
@endsection
@section('script')
    <script src="{{asset('assets/js/ckeditor.js')}}"></script>
    <script>
        ClassicEditor
            .create( document.querySelector( '#editor',function () {
                config.width = 500;
            } ) )
            .catch( error => {
                console.error( error );
            } );
    </script>
    <script>
        CKEDITOR.replace( '#editor', {
            uiColor: '#14B8C4',
            toolbar: [
                [ 'Bold', 'Italic', '-', 'NumberedList', 'BulletedList', '-', 'Link', 'Unlink' ],
                [ 'FontSize', 'TextColor', 'BGColor' ]
            ],
            width:['250px']

        });

    </script>
    <script>
        $(document).ready(function(){
            $('#users').select2({
                placeholder : 'Please select Role (default- user)',
                tags: true
            });
        });

        $("#clear").on("click", function(){
            return confirm("Do you want to clear all record ?");
        });

    </script>

    {{--<script src="{{asset('vendor/unisharp/laravel-ckeditor/ckeditor.js')}}"></script>--}}
    {{--<script>--}}
        {{--CKEDITOR.replace( 'text-message');--}}
    {{--</script>--}}
@endsection