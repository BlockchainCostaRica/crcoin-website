@extends('Admin.master',['menu'=>'users','submenu'=>'pendingid'])
@section('title',__('Pending Id Verified User'))
@section('content')
    <div class="idvarification-page">
        <div class="container-fluid">
            <div class="page-wraper section-padding">
                <!-- breadcrumb-area start here -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="breadcrumb-area">
                            <ul>
                                <li class="page " >User management </li>
                                <li class="page active"> Pending  verification</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="page-inner section-height">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="idvarification-area">
                                <?php
                                $nid_front = (isset($fields_name['nid_front'])) ? $pending[array_search('nid_front',array_keys($fields_name))]->photo : '';
                                $nid_back = (isset($fields_name['nid_back'])) ? $pending[array_search('nid_back',array_keys($fields_name))]->photo : '';
                                $drive_front = (isset($fields_name['drive_front'])) ? $pending[array_search('drive_front',array_keys($fields_name))]->photo : '';
                                $drive_back = (isset($fields_name['drive_back'])) ? $pending[array_search('drive_back',array_keys($fields_name))]->photo : '';

                                $pass_front = (isset($fields_name['pass_front'])) ? $pending[array_search('pass_front',array_keys($fields_name))]->photo : '';
                                $pass_back = (isset($fields_name['pass_back'])) ? $pending[array_search('pass_back',array_keys($fields_name))]->photo : '';

                                ?>
                                @if(isset($fields_name['nid_front']))
                                    <div class="nid-card-list  plr-110">
                                        <div class="page-title-inner">
                                            <div class="title">
                                                <h3>{{__('Pending ID Verification')}}</h3>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="single-nid text-center">
                                                    <div class="nid-img">

                                                        <img src="{{imageSrc($nid_front,IMG_USER_VIEW_PATH)}}" alt="front nid">
                                                    </div>
                                                    <div class="title">
                                                        <h4>{{__('NID Front Side')}}</h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="single-nid text-center">
                                                    <div class="nid-img">
                                                        <img src="{{imageSrc($nid_back,IMG_USER_VIEW_PATH)}}" alt="back nid">
                                                    </div>
                                                    <div class="title">
                                                        <h4>{{__('NID Back Side')}}</h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="chart-btn text-center">
                                                    <a href="{{route('adminUserVerificationActive',[$user_id,'nid'])}}" class="verifay-btn ">{{__('Approve')}}</a>
                                                    <a href="#" data-popup-open="reject" class="verifay-btn color">Reject</a>
                                                    <!-- reject start here -->
                                                    <form action="{{route('varificationReject')}}">
                                                    <div class="popup" data-popup="reject">
                                                        <div class="popup-inner">
                                                            <div class="popup-title">
                                                                <h3>{{__('Rejected Cause')}}</h3>
                                                            </div>
                                                            <div class="popup-content">
                                                                <h5>{{__('Cause of  Rejection')}}</h5>
                                                                <input type="hidden" name="type" value="nid">
                                                                <input type="hidden" name="user_id" value="{{$user_id}}">
                                                                <input type="hidden" name="ids[]" value="{{$nid_front}}">
                                                                <input type="hidden" name="ids[]" value="{{$nid_back}}">
                                                                <textarea required name="couse" id="" cols="45" rows="10"></textarea>
                                                                <button type="submit" class="button-primary" href="#">Send</button>
                                                            </div>
                                                            <a class="popup-close" data-popup-close="reject" href="#">x</a>
                                                        </div>
                                                    </div>
                                                    </form>
                                                    <!-- reject end here -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if(isset($fields_name['drive_front']))
                                    <div class="nid-card-list  plr-110">
                                        <div class="page-title-inner">
                                            <div class="title">
                                                <h3>{{__('Driving licence Verification')}}</h3>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="single-nid text-center">
                                                    <div class="nid-img">

                                                        <img src="{{imageSrc($drive_front,IMG_USER_VIEW_PATH)}}" alt="front nid">
                                                    </div>
                                                    <div class="title">
                                                        <h4>{{__('Driving licence Front Side')}}</h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="single-nid text-center">
                                                    <div class="nid-img">
                                                        <img src="{{imageSrc($drive_back,IMG_USER_VIEW_PATH)}}" alt="back nid">
                                                    </div>
                                                    <div class="title">
                                                        <h4>{{__('Driving licence Back Side')}}</h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="chart-btn text-center">
                                                    <a href="{{route('adminUserVerificationActive',[$user_id,'driving'])}}" class="verifay-btn ">{{__('Approve')}}</a>
                                                    <a href="#" data-popup-open="reject_driving" class="verifay-btn color">Reject</a>
                                                    <!-- reject start here -->
                                                    <form action="{{route('varificationReject')}}">
                                                        <div class="popup" data-popup="reject_driving">
                                                            <div class="popup-inner">
                                                                <div class="popup-title">
                                                                    <h3>{{__('Rejected Cause')}}</h3>
                                                                </div>
                                                                <div class="popup-content">
                                                                    <h5>{{__('Cause of  Rejection')}}</h5>
                                                                    <input type="hidden" name="type" value="drive">
                                                                    <input type="hidden" name="user_id" value="{{$user_id}}">
                                                                    <input type="hidden" name="ids[]" value="{{$drive_front}}">
                                                                    <input type="hidden" name="ids[]" value="{{$drive_back}}">
                                                                    <textarea required name="couse" id="" cols="45" rows="10"></textarea>
                                                                    <button type="submit" class="button-primary" href="#">Send</button>
                                                                </div>
                                                                <a class="popup-close" data-popup-close="reject_driving" href="#">x</a>
                                                            </div>
                                                        </div>
                                                    </form>
                                                    <!-- reject end here -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if(isset($fields_name['pass_front']))
                                    <div class="nid-card-list  plr-110">
                                        <div class="page-title-inner">
                                            <div class="title">
                                                <h3>{{__('Passport Verification')}}</h3>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="single-nid text-center">
                                                    <div class="nid-img">

                                                        <img src="{{imageSrc($pass_front,IMG_USER_VIEW_PATH)}}" alt="front nid">
                                                    </div>
                                                    <div class="title">
                                                        <h4>{{__('Passport Front Side')}}</h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="single-nid text-center">
                                                    <div class="nid-img">
                                                        <img src="{{imageSrc($pass_back,IMG_USER_VIEW_PATH)}}" alt="back nid">
                                                    </div>
                                                    <div class="title">
                                                        <h4>{{__('Passport Back Side')}}</h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="chart-btn text-center">
                                                    <a href="{{route('adminUserVerificationActive',[$user_id,'passport'])}}" class="verifay-btn ">{{__('Approve')}}</a>
                                                    <a href="#" data-popup-open="reject_passport" class="verifay-btn color">Reject</a>
                                                    <!-- reject start here -->
                                                    <form action="{{route('varificationReject')}}">
                                                        <div class="popup" data-popup="reject_passport">
                                                            <div class="popup-inner">
                                                                <div class="popup-title">
                                                                    <h3>{{__('Rejected Cause')}}</h3>
                                                                </div>
                                                                <div class="popup-content">
                                                                    <h5>{{__('Cause of  Rejection')}}</h5>
                                                                    <input type="hidden" name="type" value="passport">
                                                                    <input type="hidden" name="user_id" value="{{$user_id}}">
                                                                    <input type="hidden" name="ids[]" value="{{$pass_front}}">
                                                                    <input type="hidden" name="ids[]" value="{{$pass_back}}">
                                                                    <textarea required name="couse" id="" cols="45" rows="10"></textarea>
                                                                    <button type="submit" class="button-primary" href="#">Send</button>
                                                                </div>
                                                                <a class="popup-close" data-popup-close="reject_passport" href="#">x</a>
                                                            </div>
                                                        </div>
                                                    </form>
                                                    <!-- reject end here -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')

@endsection