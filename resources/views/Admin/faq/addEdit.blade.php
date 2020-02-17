@extends('Admin.master',['menu'=>'setting','sub_menu'=>'faq'])
@section('title',__('FAQS'))
@section('style')
@endsection
@section('content')
    <!-- coin-area start -->
    <div class="user-area">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="admin-wrapper custom-wrapper">
                        <h3 class="subtitle">{{$title}}</h3>
                        <div class="form-area">
                            <div class="row">
                                <div class="col-12">
                                  <form method="post" action="{{route('adminFaqSave')}}">
                                      @csrf
                                    <div class="userlist-wrap adduser-wrap form-style">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label for="#">{{__('Question')}}</label>
                                                <input type="text" name="question" placeholder="{{__('Question')}}" @if(isset($item))value="{{$item->question}}" @else value="{{old('question')}}" @endif>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label for="#">{{__('Answer')}}</label>
                                                <textarea name="answer" id="text-message" class="textarea" class="form-control">@if(isset($item)){!! $item->answer !!} @else {{old('answer')}} @endif</textarea>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label for="#">{{__('Priority')}}</label>
                                                <input type="number" name="priority" min="1" placeholder="{{__('Example: 1')}}" @if(isset($item))value="{{$item->priority}}" @else value="{{old('priority')}}" @endif>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-12">
                                            @if(isset($item))<input type="hidden" name="edit_id" value="{{$item->id}}">@endif
                                            <button class="button-primary" >{{__('Submit')}}</button>
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

    <script src="{{asset('vendor/unisharp/laravel-ckeditor/ckeditor.js')}}"></script>
    <script>
        CKEDITOR.replace( 'text-message');
    </script>
@endsection