@extends('User.master',['menu'=>'my_profile'])
@section('title', $title)
@section('content')
    <div class="profile-page-area">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="profile-inner">
                        <div class="profile-top-menu">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="tabe-menu">
                                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link user {{($qr == 'profile-tab') ? 'active' : ''}}" data-id="profile-tab" id="Profile-tab" data-toggle="tab" href="#Profile" role="tab" aria-controls="Profile" aria-selected="true"> <i class="flaticon-user"></i> {{__('Profile')}}</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link profile {{($qr == 'eProfile-tab') ? 'active' : ''}}" data-id="eProfile-tab" id="eProfile-tab" data-toggle="tab" href="#eProfile" role="tab" aria-controls="eProfile" aria-selected="false">  <i class="flaticon-user-1"> </i> {{__('Edit Profile')}}</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link phonevverifaction {{($qr == 'pvarification-tab') ? 'active' : ''}}" data-id="pvarification-tab" id="pvarification-tab" data-toggle="tab" href="#pvarification" role="tab" aria-controls="pvarification" aria-selected="false">  <i class="flaticon-phone"> </i> {{__('Phone Verification')}}</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link idverifaction {{($qr == 'idvarification-tab') ? 'active' : ''}}" data-id="idvarification-tab" id="idvarification-tab" data-toggle="tab" href="#idvarification" role="tab" aria-controls="idvarification" aria-selected="false">  <i class="flaticon-id-card"> </i>{{__('ID Verification')}}</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link password {{($qr == 'rpassword-tab') ? 'active' : ''}}" data-id="rpassword-tab" id="rpassword-tab" data-toggle="tab" href="#rpassword" role="tab" aria-controls="rpassword" aria-selected="false">  <i class="flaticon-padlock"> </i> {{__('Reset Password')}}</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade  {{($qr == 'profile-tab') ? 'show active in' : ''}} in" id="Profile" role="tabpanel" aria-labelledby="Profile-tab">
                                <div class="row">
                                    <div class="col-lg-4 offset-lg-1">
                                        <div class="profile-img-area text-center">
                                            <div class="prifile-img">
                                                <img width="100" src="{{imageSrcUser(\Illuminate\Support\Facades\Auth::user()->photo,IMG_USER_VIEW_PATH)}}" alt="profile">
                                            </div>
                                            <div class="profile-name">
                                                <h3>{!! clean(Auth::user()->first_name.' '.Auth::user()->last_name) !!}</h3>
                                                <span>{!! clean(Auth::user()->email) !!}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="profile-info">
                                            <div class="table-responsive">
                                                <table class="table table-striped">
                                                    <tbody>
                                                        <tr>
                                                            <td>{{__('Name')}}</td>
                                                            <td>:</td>
                                                            <td><span>{!! clean(Auth::user()->first_name.' '.Auth::user()->last_name) !!}</span></td>
                                                        </tr>
                                                        <tr>
                                                            <td>{{__('Role')}}</td>
                                                            <td>:</td>
                                                            <td><span>{{__('User')}}</span></td>
                                                        </tr>
                                                        <tr>
                                                            <td>{{__('Email')}}</td>
                                                            <td>:</td>
                                                            <td><span class="email-case">{!! clean(Auth::user()->email) !!}</span></td>
                                                        </tr>
                                                        <tr>
                                                            <td>{{__('Email Verification')}}</td>
                                                            <td>:</td>
                                                            <td><span class="color">{{__('Verified')}}</span></td>
                                                        </tr>
                                                        <tr>
                                                            <td>{{__('Contact')}}</td>
                                                            <td>:</td>
                                                            <td><span>{{\Illuminate\Support\Facades\Auth::user()->phone}}</span></td>
                                                        </tr>
                                                        <tr>
                                                            <td>{{__('Status')}}</td>
                                                            <td>:</td>
                                                            <td><span>{{__('Active')}}</span></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade {{($qr == 'eProfile-tab') ? 'show active in' : ''}}" id="eProfile" role="tabpanel" aria-labelledby="eProfile-tab">
                                <div class="row">
                                    <div class="col-lg-4 offset-lg-1">
                                        <div class="profile-img-area text-center">
                                            <div class="prifile-img">
                                                <img width="100" src="{{imageSrcUser(Auth::user()->photo,IMG_USER_VIEW_PATH)}}" alt="profile">
                                            </div>
                                            <div class="uplode-img">
                                                <form enctype="multipart/form-data" method="post" action="{{route('uploadProfileImage')}}">
                                                    @csrf
                                                    <div class="box">
                                                        <input  type="file" name="file_one" id="file-one" class="inputfile" />
                                                        <label for="file-one"> <span>{{__('Upload Image')}}</span></label>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-area">
                                            <form action="{{route('UserProfileUpdate')}}" method="post">
                                                @csrf
                                                <div class="form-group">
                                                    <label for="firstname">{{__('First Name')}}</label>
                                                    <input name="first_name" value="{{old('first_name',Auth::user()->first_name)}}" type="text" class="form-control" id="firstname" placeholder="{{__('First name')}}">
                                                    @error('first_name')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="lastname">{{__('Last Name')}}</label>
                                                    <input name="last_name" value="{{old('last_name',Auth::user()->last_name)}}" type="text" class="form-control" id="lastname" placeholder="{{__('Last name')}}">
                                                    @error('last_name')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                 </div>
                                                <div class="form-group">
                                                    <label for="lastname">{{__('Contact No.')}}</label>
                                                    <input name="phone"   type="text" value="{{old('phone',Auth::user()->phone)}}" class="form-control" id="phoneVerify" placeholder="{{__('01999999999')}}">
                                                    @error('phone')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="email">{{__('Email')}}</label>
                                                    <input name="" readonly type="email" value="{{old('email',Auth::user()->email)}}" class="form-control" id="email" placeholder="{{__('email')}}">
                                                    @error('email')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                                <button type="submit" class="primary-btn">{{__('Update')}}</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade {{($qr == 'pvarification-tab') ? 'show active in' : ''}}" id="pvarification" role="tabpanel" aria-labelledby="pvarification-tab">
                                <div class="row">
                                    <div class="col-lg-8 offset-lg-2">
                                        <div class="phone-varifaction-area">
                                            <div class="form-area">
                                                <form method="post" action="{{route('PhoneVerify')}}">
                                                    @csrf
                                                    <div class="form-group">
                                                        <label for="number">{{__('Phone number')}}</label>
                                                        <div class="code-list">

                                                            <input type="text"  readonly value="{{Auth::user()->phone}}" class="form-control" id="" >
                                                            @if((Auth::user()->phone_verified == 0 )  && (!empty(\Illuminate\Support\Facades\Cookie::get('code'))))
                                                            <a href="{{route('sendSMS')}}" class="primary-btn">{{__('Resend SMS')}}</a>
                                                                <p>{{__('Did not receive code')}}</p>
                                                                @elseif(Auth::user()->phone_verified == 1 )
                                                                <span class="verified" >{{__('Verified')}}</span>
                                                             @else
                                                                <a href="{{route('sendSMS')}}" class="primary-btn">{{__('Send SMS')}}</a>
                                                             @endif
                                                        </div>
                                                    </div>
                                                     @if((Auth::user()->phone_verified == 0) && (!empty(\Illuminate\Support\Facades\Cookie::get('code'))))
                                                        <div class="form-group">
                                                            <label for="number">{{__('Verify Code')}}</label>
                                                            <div class="code-list">
                                                                <input name="code" type="text" min="" max="" class="form-control" id="" >
                                                            </div>
                                                        </div>
                                                        <button type="submit" class="primary-btn">{{__('Verify')}}</button>
                                                     @endif
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade {{($qr == 'idvarification-tab') ? 'show active in' : ''}}" id="idvarification" role="tabpanel" aria-labelledby="idvarification-tab">
                                <div class="idvarification-area">
                                    <div class="row">
                                        <div class="col-lg-10 offset-lg-1">
                                            <div class="stype-name">
                                                <h4>Step - 1 : Select ID type</h4>
                                            </div>
                                            <div class="card-type-list">
                                                <div class="single-card text-center">
                                                    <a href="#" data-popup-open="popup-card" href="#">
                                                        <div class="card-top">
                                                            <img src="assets/images/card/1.png" alt="id card">
                                                        </div>
                                                        <div class="card-bottom">
                                                            @if((!empty($nid_back ) && !empty($nid_front)) && (($nid_back->status == STATUS_SUCCESS) && ($nid_front->status == STATUS_SUCCESS)))
                                                            <span>{{__('Approved')}}</span>
                                                            @elseif((!empty($nid_back ) && !empty($nid_front)) && (($nid_back->status == STATUS_REJECTED) && ($nid_front->status == STATUS_REJECTED)))
                                                            <span>{{__('Not Submitted')}}</span>
                                                            @elseif((!empty($nid_back ) && !empty($nid_front)) && (($nid_back->status == STATUS_PENDING) && ($nid_front->status == STATUS_PENDING)))
                                                            <span>{{__('Pending')}}</span>
                                                            @else
                                                                <span>{{__('Not Submitted')}}</span>
                                                            @endif
                                                            <h5>{{__('National Id Card')}}</h5>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="single-card text-center">
                                                    <a href="#" data-popup-open="popup-card-passport" href="#">
                                                        <div class="card-top">
                                                            <img src="assets/images/card/2.png" alt="id card">
                                                        </div>
                                                        <div class="card-bottom">
                                                            @if((!empty($pass_back ) && !empty($pass_front)) && (($pass_back->status == STATUS_SUCCESS) && ($pass_front->status == STATUS_SUCCESS)))
                                                                <span>{{__('Approved')}}</span>
                                                            @elseif((!empty($pass_back ) && !empty($pass_front)) && (($pass_back->status == STATUS_REJECTED) && ($pass_front->status == STATUS_REJECTED)))
                                                                <span>{{__('Not Submitted')}}</span>

                                                            @elseif((!empty($pass_back ) && !empty($pass_front)) && (($pass_back->status == STATUS_PENDING) && ($pass_front->status == STATUS_PENDING)))
                                                                <span>{{__('Pending')}}</span>
                                                            @else
                                                                <span>{{__('Not Submitted')}}</span>
                                                            @endif
                                                            <h5>Passport</h5>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="single-card text-center">
                                                    <a href="#" data-popup-open="popup-card-drive" href="#">
                                                        <div class="card-top">
                                                            <img src="assets/images/card/3.png" alt="id card">
                                                        </div>
                                                        <div class="card-bottom">
                                                            @if((!empty($drive_back ) && !empty($drive_front)) && (($drive_back->status == STATUS_SUCCESS) && ($drive_front->status == STATUS_SUCCESS)))
                                                                <span>{{__('Approved')}}</span>
                                                            @elseif((!empty($drive_back ) && !empty($drive_front)) && (($drive_back->status == STATUS_REJECTED) && ($drive_front->status == STATUS_REJECTED)))
                                                                <span>{{__('Not Submitted')}}</span>

                                                            @elseif((!empty($drive_back ) && !empty($drive_front)) && (($drive_back->status == STATUS_PENDING) && ($drive_front->status == STATUS_PENDING)))
                                                                <span>{{__('Pending')}}</span>
                                                            @else
                                                                <span>{{__('Not Submitted')}}</span>
                                                            @endif
                                                            <h5>Driving License</h5>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                            <!-- popup-card start here -->
                                            <div class="popup" data-popup="popup-card">
                                                <div class="popup-inner">
                                                    <div class="popup-title">
                                                        <h3>{{__('Step - 2 : Upload NID')}}</h3>
                                                    </div>
                                                    <form id="nidUpload" class="Upload" action="{{route('nidUpload')}}" enctype="multipart/form-data" method="post">
                                                       @csrf
                                                        <div class="popup-content">
                                                            <div class="card-list">
                                                                <div class="alert alert-danger d-none error_msg" id="" role="alert">
                                                                </div>
                                                                <div class="alert alert-success d-none succ_msg" id="" role="alert">
                                                                </div>
                                                            </div>
                                                            <div class="card-list">
                                                                <div class="single-card text-center">
                                                                    <div class="card-img">
                                                                        <img class="user-p-img" src="{{imageSrcVerification((!empty($nid_front)) ? $nid_front->photo : '',IMG_USER_VIEW_PATH)}}" alt="card">
                                                                    </div>
                                                                    <div class="uplode-img">
                                                                        @if((empty($nid_back ) && empty($nid_front)) || (($nid_back->status == STATUS_REJECTED) && ($nid_front->status == STATUS_REJECTED)))
                                                                            <div class="box">
                                                                                <input type="file" accept="image/x-png,image/jpeg"  name="file_two" id="file-two" class="inputfile" />
                                                                                <label for="file-two"> <span>{{__('Upload Image')}}</span></label>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                    <h5>{{__('Front')}}</h5>
                                                                </div>
                                                                <div class="single-card text-center">
                                                                    <div class="card-img">
                                                                        <img class="user-p-img" src="{{imageSrcVerification((!empty($nid_back)) ? $nid_back->photo : '',IMG_USER_VIEW_PATH)}}"  alt="card">
                                                                    </div>
                                                                    <div class="uplode-img">
                                                                        @if((empty($nid_back ) && empty($nid_front)) || (($nid_back->status == STATUS_REJECTED) && ($nid_front->status == STATUS_REJECTED)))
                                                                            <div class="box">
                                                                                <input type="file"    accept="image/x-png,image/jpeg"name="file_three" id="file-three" class="inputfile" />
                                                                                <label for="file-three"> <span>{{__('Upload Image')}}</span></label>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                    <h5>{{__('Back')}}</h5>
                                                                </div>
                                                            </div>
                                                           @if((empty($nid_back ) && empty($nid_front)) || (($nid_back->status == STATUS_REJECTED) && ($nid_front->status == STATUS_REJECTED)))
                                                                <div class="uplode-btn text-center">
                                                                    <button type="submit" class="primary-btn">{{__('Upload')}}</button>
                                                                </div>
                                                           @endif
                                                        </div>
                                                    </form>
                                                    <a class="popup-close" data-popup-close="popup-card" href="#">x</a>
                                                </div>
                                            </div>

                                            <div class="popup" data-popup="popup-card-passport">
                                                <div class="popup-inner">
                                                    <div class="popup-title">
                                                        <h3>{{__('Step - 3 : Upload Passport')}}</h3>
                                                    </div>
                                                    <form id="nidUpload" class="Upload" action="{{route('passUpload')}}" enctype="multipart/form-data" method="post">
                                                       @csrf
                                                        <div class="popup-content">
                                                            <div class="card-list">
                                                                <div class="alert alert-danger d-none error_msg" id="" role="alert">
                                                                </div>
                                                                <div class="alert alert-success d-none succ_msg" id="" role="alert">
                                                                </div>
                                                            </div>
                                                            <div class="card-list">
                                                                <div class="single-card text-center">
                                                                    <div class="card-img">
                                                                        <img class="user-p-img" src="{{imageSrcVerification((!empty($pass_front)) ? $pass_front->photo : '',IMG_USER_VIEW_PATH)}}" alt="card">
                                                                    </div>
                                                                    <div class="uplode-img">
                                                                        @if((empty($pass_back ) && empty($pass_front)) || (($pass_back->status == STATUS_REJECTED) && ($pass_front->status == STATUS_REJECTED)))
                                                                            <div class="box">
                                                                                <input type="file" accept="image/x-png,image/jpeg"  name="file_two" id="file-two" class="inputfile" />
                                                                                <label for="file-two"> <span>{{__('Upload Image')}}</span></label>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                    <h5>{{__('Front')}}</h5>
                                                                </div>
                                                                <div class="single-card text-center">
                                                                    <div class="card-img">
                                                                        <img class="user-p-img" src="{{imageSrcVerification((!empty($pass_back)) ? $pass_back->photo : '',IMG_USER_VIEW_PATH)}}"  alt="card">
                                                                    </div>
                                                                    <div class="uplode-img">
                                                                        @if((empty($pass_back ) && empty($pass_front)) || (($pass_back->status == STATUS_REJECTED) && ($pass_front->status == STATUS_REJECTED)))
                                                                            <div class="box">
                                                                                <input type="file"    accept="image/x-png,image/jpeg"name="file_three" id="file-three" class="inputfile" />
                                                                                <label for="file-three"> <span>{{__('Upload Image')}}</span></label>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                    <h5>{{__('Back')}}</h5>
                                                                </div>
                                                            </div>
                                                           @if((empty($pass_back ) && empty($pass_front)) || (($pass_back->status == STATUS_REJECTED) && ($pass_front->status == STATUS_REJECTED)))
                                                                <div class="uplode-btn text-center">
                                                                    <button type="submit" class="primary-btn">{{__('Upload')}}</button>
                                                                </div>
                                                           @endif
                                                        </div>
                                                    </form>
                                                    <a class="popup-close" data-popup-close="popup-card-passport" href="#">x</a>
                                                </div>
                                            </div>

                                            <div class="popup" data-popup="popup-card-drive">
                                                <div class="popup-inner">
                                                    <div class="popup-title">
                                                        <h3>{{__('Step - 3 : Upload Driving License')}}</h3>
                                                    </div>
                                                    <form id="nidUpload" class="Upload" action="{{route('driveUpload')}}" enctype="multipart/form-data" method="post">
                                                        @csrf
                                                        <div class="popup-content">
                                                            <div class="card-list">
                                                                <div class="alert alert-danger d-none error_msg" id="" role="alert">
                                                                </div>
                                                                <div class="alert alert-success d-none succ_msg" id="" role="alert">
                                                                </div>
                                                            </div>
                                                            <div class="card-list">
                                                                <div class="single-card text-center">
                                                                    <div class="card-img">
                                                                        <img class="user-p-img" src="{{imageSrcVerification((!empty($drive_front)) ? $drive_front->photo : '',IMG_USER_VIEW_PATH)}}" alt="card">
                                                                    </div>
                                                                    <div class="uplode-img">
                                                                        @if((empty($drive_back ) && empty($drive_front)) || (($drive_back->status == STATUS_REJECTED) && ($drive_front->status == STATUS_REJECTED)))
                                                                            <div class="box">
                                                                                <input type="file" accept="image/x-png,image/jpeg"  name="file_two" id="file-two" class="inputfile" />
                                                                                <label for="file-two"> <span>{{__('Upload Image')}}</span></label>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                    <h5>{{__('Front')}}</h5>
                                                                </div>
                                                                <div class="single-card text-center">
                                                                    <div class="card-img">
                                                                        <img class="user-p-img" src="{{imageSrcVerification((!empty($drive_back)) ? $drive_back->photo : '',IMG_USER_VIEW_PATH)}}"  alt="card">
                                                                    </div>
                                                                    <div class="uplode-img">
                                                                        @if((empty($drive_back ) && empty($drive_front)) || (($drive_back->status == STATUS_REJECTED) && ($drive_front->status == STATUS_REJECTED)))
                                                                            <div class="box">
                                                                                <input type="file"    accept="image/x-png,image/jpeg"name="file_three" id="file-three" class="inputfile" />
                                                                                <label for="file-three"> <span>{{__('Upload Image')}}</span></label>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                    <h5>{{__('Back')}}</h5>
                                                                </div>
                                                            </div>

                                                            @if((empty($drive_back ) && empty($drive_front)) || (($drive_back->status == STATUS_REJECTED) && ($drive_front->status == STATUS_REJECTED)))
                                                                <div class="uplode-btn text-center">
                                                                    <button type="submit" class="primary-btn">{{__('Upload')}}</button>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </form>
                                                    <a class="popup-close" data-popup-close="popup-card-drive" href="#">x</a>
                                                </div>
                                            </div>
                                            <!-- popup-card end here -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade  {{($qr == 'rpassword-tab') ? 'show active in' : ''}}" id="rpassword" role="tabpanel" aria-labelledby="rpassword-tab">
                                <div class="restpassword-area">
                                    <div class="row">
                                        <div class="col-lg-6 offset-lg-3">
                                            <div class="form-area">
                                                <form method="POST" action="{{route('changePasswordSave')}}">
                                                    @csrf
                                                    <div class="form-group">
                                                        <label for="email">{{__('Email')}}</label>
                                                        <input type="email" readonly class="form-control" id="email-two" value="{{\Illuminate\Support\Facades\Auth::user()->email}}" placeholder="example@email.com">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="currentpassword">{{__('Current Password')}}</label>
                                                        <input name="password" type="password" class="form-control show-pass" value="" id="currentpassword" autocomplete="off"  placeholder="">
                                                        <span class="reveal"><i class="fa fa-eye-slash toggle-password" onclick="showHidePassword('old_password')" ></i></span>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="newpassword">{{__('New Password')}}</label>
                                                        <input name="new_password" type="password" class="form-control show-pass-1" value="" id="newpassword" autocomplete="off" placeholder="">
                                                        <span class="reveal-1"><i class="fa fa-eye-slash toggle-password" onclick="showHidePassword('old_password')" ></i></span>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="confirmpassword">{{__('Confirm Password')}}</label>
                                                        <input name="confirm_new_password" type="password" class="form-control show-pass-2" value="" id="confirmpassword" autocomplete="off" placeholder="">
                                                        <span class="reveal-2"><i class="fa fa-eye-slash toggle-password" onclick="showHidePassword('old_password')" ></i></span>
                                                    </div>
                                                    <button type="submit" class="primary-btn">{{__('Change Password')}}</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade {{($qr == 'activitylog-tab') ? 'show active in' : ''}}" id="activitylog" role="tabpanel" aria-labelledby="activitylog-tab">
                                <div class="activelog-area">
                                    <div class="row">
                                        <div class="col-lg-10 offset-lg-1">
                                            <div class="activelog-title">
                                                <h4>All Activity List</h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="activelog-list">
                                                <div class="table-responsive">
                                                    <table class="table" >
                                                        <thead>
                                                        <tr>
                                                            <th>Action</th>
                                                            <th>Source</th>
                                                            <th>IP Address</th>
                                                            <th>Location</th>
                                                            <th>Created At</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr>
                                                            <td>Log In</td>
                                                            <td>Web</td>
                                                            <td>202.5.50.101</td>
                                                            <td>Bangladesh</td>
                                                            <td>2019-01-31 08:38:11</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Profile Image Upload</td>
                                                            <td>Web</td>
                                                            <td>202.5.50.101</td>
                                                            <td>Bangladesh</td>
                                                            <td>2019-01-31 08:38:11</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Log In</td>
                                                            <td>Web</td>
                                                            <td>202.5.50.101</td>
                                                            <td>Bangladesh</td>
                                                            <td>2019-01-31 08:38:11</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Create Address</td>
                                                            <td>Web</td>
                                                            <td>202.5.50.101</td>
                                                            <td>Bangladesh</td>
                                                            <td>2019-01-31 08:38:11</td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
@endsection
@section('script')
    <script>
        $('.nav-link').on('click',function () {
          var query = $(this).data('id');
            window.history.pushState('page2', 'Title', '{{route('myProfile')}}?qr='+query);
        });

        jQuery("#file-one").change(function () {
            this.form.submit();

        });

        $(function () {
            $(document.body).on('submit','.Upload', function(e){
                e.preventDefault();
                $('.error_msg').addClass('d-none');
                $('.succ_msg').addClass('d-none');
                var form = $(this);
                $.ajax({
                    type: "POST",
                    enctype: 'multipart/form-data',
                    url: form.attr('action'),
                    data: new FormData($(this)[0]),
                    async: false,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                      if (data.success == true){
                          $('.succ_msg').removeClass('d-none');
                          $('.succ_msg').html(data.message);

                          $(".succ_msg").fadeTo(2000, 500).slideUp(500, function() {
                              $(".succ_msg").slideUp(500);
                          });
                      } else {
                          $('.error_msg').removeClass('d-none');
                          $('.error_msg').html(data.message);

                          $(".error_msg").fadeTo(2000, 500).slideUp(500, function() {
                                $(".error_msg").slideUp(500);
                            });
                      }
                    }
                });
                return false;
            });
        });
       
        $(".reveal").on('click',function() {
            var $pwd = $(".show-pass");
            if ($pwd.attr('type') === 'password') {
                $pwd.attr('type', 'text');
            } else {
                $pwd.attr('type', 'password');
            }
        });

        $(".reveal-1").on('click',function() {
            var $pwd = $(".show-pass-1");
            if ($pwd.attr('type') === 'password') {
                $pwd.attr('type', 'text');
            } else {
                $pwd.attr('type', 'password');
            }
        });
        $(".reveal-2").on('click',function() {
            var $pwd = $(".show-pass-2");
            if ($pwd.attr('type') === 'password') {
                $pwd.attr('type', 'text');
            } else {
                $pwd.attr('type', 'password');
            }
        });
    </script>

    <script>
        $(".toggle-password").click(function() {
            $(this).toggleClass("fa-eye-slash fa-eye");
        });

        function showHidePassword(id) {

            var x = document.getElementById(id);
            if (x.type === "password") {
                x.type = "text";

            } else {
                x.type = "password";
            }
        }
        function readURL(input,img) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#'+img).attr('src', e.target.result);
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
        $(document.body).on('click','#password_btn',function () {
            console.log($('#password').input.type);
        });
    </script>
  
    <script>
        $(document.body).on('click','.iti__country',function () {
           var cd = $(this).find('.iti__dial-code').html();
           $('#code_v').val(cd)
        });
    </script>

@endsection