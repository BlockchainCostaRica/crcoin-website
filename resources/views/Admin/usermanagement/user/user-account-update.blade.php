@extends('backend.admin-master',['menu'=>'user'])
@section('title',__('User Details'))
@section('content')
    <!-- header-area end -->
    @include('backend.widget.admin-header',['title'=>__('User Account Update')])
    <!-- header-area end -->
    <!-- .user-area start -->
    <div class="user-area">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="admin-wrapper">
                        <!-- user-details -->
                        {{Form::open(['route'=>'adminUserAccountUpdate'])}}
                        <div class="updateuser-wrap form-style ">
                            <h3 class="user-title">{{__('Edit User Information')}}</h3>
                            <ul>
                                <li>
                                    <label for="#">{{__('First Name')}}</label>
                                    <input type="text" placeholder="{{__('First Name')}}" name="first_name" value="{{$user['userFirstName']}}">
                                </li>
                                <li>
                                    <label for="#">{{__('Last Name')}}</label>
                                    <input type="text" placeholder="{{__('Last Name')}}" name="last_name" value="{{$user['userLastName']}}">
                                </li>
                                <li>{{__('Email')}}<span class="dotted-style">:</span> <span class="text-style">{{$user['userEmail']}}</span></li>
                                <li>{{__('Role')}} <span class="dotted-style">:</span> <span class="text-style"> {{userRole($user['userRole'])}}</span></li>
                                <li>{{__('Email Verification')}} <span class="dotted-style">:</span> <span class="text-style">{{statusAction($user['userEmailVerified'])}}</span></li>
                                <li>{{__('Phone Verification')}} <span class="dotted-style">:</span> <span class="text-style">{{statusAction($user['userPhoneVerified'])}}</span></li>
                                <li>{{__('Status')}} <span class="dotted-style">:</span> <span class="text-style">{{statusAction($user['idStatus'])}}</span></li>
                                <li>
                                    <div class="row">
                                        <div class="col-lg-2 offset-lg-10">
                                            <input type="hidden" name="edit_id" value="{{$user['userId']}}">
                                            <button>{{__('Save')}}</button>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        {{Form::close()}}
                        <!-- user-details -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- .user-area end -->
@endsection
@section('script')

@endsection