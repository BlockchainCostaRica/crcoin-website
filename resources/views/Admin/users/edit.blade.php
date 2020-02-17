@extends('Admin.master',['menu'=>'users','submenu'=>'users'])
@section('title', 'Update User')
@section('style')
@endsection
@section('content')
    <div class="profile-page-area">
        <div class="container-fluid">
            <div class="page-wraper section-padding">
                <!-- breadcrumb-area start here -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="breadcrumb-area">
                            <ul>
                                <li class="page active">{{__('Profile')}}</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="page-inner">
                    <!-- breadcrumb-area start here -->
                    <div class="row">
                        <div class="col-sm-12">
                            <!-- tabs style start here -->
                            <div class="single-tab section-height">
                                <div class="section-body ">
                                    <div class="nav nav-pills nav-pill-three" id="tab" role="tablist">
                                        <a class="nav-link {{($type == 'view') ? 'active' : ''}}" id="home-tab" data-toggle="pill" href="#home" role="tab" aria-controls="home" aria-selected="true">
                                            <span class="flaticon-user one"></span>{{__('Details')}}
                                        </a>
                                        <a class="nav-link  {{($type == 'edit') ? 'active' : ''}}" id="profile-tab" data-toggle="pill" href="#profile" role="tab" aria-controls="profile" aria-selected="false">
                                            <span class="flaticon-user-4 two"></span> {{__('Edit')}}
                                        </a>
                                    </div>
                                    <div class="tab-content " id="tabContent">
                                        <div class="tab-pane fade show  {{($type == 'view') ? 'active' : ''}}" id="home" role="tabpanel" aria-labelledby="home-tab">
                                            <div class="profile-inner plr-65 pt-55 ">
                                                <div class="row">
                                                    <div class="col-lg-4">
                                                        <div class="profile-img-area text-center">
                                                            <div class="prifile-img">
                                                                <img src="{{imageSrcUser($user->photo,IMG_USER_VIEW_PATH)}}" alt="profile">
                                                            </div>
                                                            <div class="profile-name">
                                                                <h3>{!! clean($user->first_name.' '.$user->last_name) !!}</h3>
                                                                <span>{!! clean($user->email) !!}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-8">
                                                        <div class="profile-info">
                                                            <div class="table-responsive">
                                                                <table class="table table-striped">
                                                                    <tbody>
                                                                    <tr>
                                                                        <td>{{__('First Name')}}</td>
                                                                        <td>:</td>
                                                                        <td><span>{!! clean($user->first_name) !!}</span></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>{{__('Last Name')}}</td>
                                                                        <td>:</td>
                                                                        <td><span>{!! clean($user->last_name) !!}</span></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Role</td>
                                                                        <td>:</td>
                                                                        <td>
                                                                            <span>{{($user->type == 1)?'Admin':'User'}}</span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Status</td>
                                                                        <td>:</td>
                                                                        <td><span>{{statusAction($user->status)}}</span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>{{__('Contact')}}</td>
                                                                        <td>:</td>
                                                                        <td><span>{!! clean($user->phone) !!}</span></td>
                                                                    </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade show  {{($type == 'edit') ? 'active' : ''}}" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                            <div class="profile-inner plr-65 pt-55 ">
                                                <div class="row">
                                                    <div class="col-lg-4 offset-lg-1">
                                                        <div class="profile-img-area text-center">
                                                            <div class="eidt-profile_image">
                                                                <img src="{{imageSrcUser($user->photo,IMG_USER_VIEW_PATH)}}" alt="profile">
                                                                <div class="uplode-img">
                                                                    <form enctype="multipart/form-data" method="post" action="{{route('uploadProfileImage')}}">
                                                                        @csrf
                                                                        <div class="box">
                                                                            <input type="hidden" name="id" value="{{encrypt($user->id)}}">
                                                                            <input class="d-none" type="file" name="file_one" id="file-one" class="inputfile"/>
                                                                            <label class="upload-btn" for="file-one">
                                                                                <i class="fa fa-camera"></i>
                                                                            </label>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="form-area">
                                                            <form action="{{route('UserProfileUpdate')}}" method="post">
                                                                @csrf
                                                                <input type="hidden" name="id" value="{{encrypt($user->id)}}">
                                                                <div class="form-group">
                                                                    <label for="firstname">{{__('First Name')}}</label>
                                                                    <input name="first_name" value="{{old('first_name',$user->first_name)}}" type="text" class="form-control" id="firstname" placeholder="{{__('First name')}}">
                                                                    @error('first_name')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                    @enderror
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="lastname">{{__('Last Name')}}</label>
                                                                    <input name="last_name" value="{{old('last_name',$user->last_name)}}" type="text" class="form-control" id="lastname" placeholder="{{__('Last name')}}">
                                                                    @error('last_name')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                    @enderror
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="lastname">{{__('Phone Number')}}</label>
                                                                    <input name="phone" type="text" value="{{old('phone',$user->phone)}}" class="form-control" id="phoneVerify" placeholder="{{__('01999999999')}}">
                                                                    @error('phone')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                    @enderror
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="email">{{__('Email')}}</label>
                                                                    <span class="email-input form-control"> {{ $user->email }} </span>
                                                                </div>
                                                                <button type="submit" class="button-primary">{{__('Update')}}</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <!-- tabs style start here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    @if(isset($errors->all()[0]))
        <script>

            $('.tab-pane').removeClass('active show');
            $('.nav-link').removeClass('active show');
            $('.add_user').addClass('active show');
            $('#profile').addClass('active show');

        </script>
    @endif

    <script>
        jQuery("#file-one").change(function () {
            this.form.submit();

        });
    </script>


@endsection