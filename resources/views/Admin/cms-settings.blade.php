@extends('Admin.master',['menu'=>'setting','sub_menu'=>'cms'])
@section('title', 'Landing Setting')
@section('style')
@endsection
@section('content')
    <!-- coin-area start -->
    <div class="landing-page-area">
        <div class="container-fluid">
            <div class="page-wraper section-padding">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="single-tab section-height">
                            <div class="section-body ">
                                <div class="nav nav-pills nav-pill-three" id="tab" role="tablist">
                                    <a @if(isset($tab) && $tab=='hero')class="active" @endif data-toggle="tab"
                                       href="#hero">{{__('Header Setting')}}</a></li>
                                    <a @if(isset($tab) && $tab=='about_as')class="active" @endif data-toggle="tab"
                                       href="#about_as">{{__('About us')}}</a></li>
                                    <a @if(isset($tab) && $tab=='features')class="active" @endif data-toggle="tab"
                                       href="#features">{{__('Features')}}</a></li>
                                    <a @if(isset($tab) && $tab=='integration')class="active" @endif data-toggle="tab"
                                       href="#integration">{{__('Integration')}}</a></li>
                                    <a @if(isset($tab) && $tab=='screenshot')class="active" @endif data-toggle="tab"
                                       href="#screenshot">{{__('Screenshots')}}</a></li>
                                </div>
                                <div class="tab-content">
                                    <!-- genarel-setting start-->
                                    <div class="tab-pane fade  @if(isset($tab) && $tab=='hero')show active @endif " id="hero" role="tabpanel" aria-labelledby="header-setting-tab">
                                        <div class="page-title">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="page-title-inner">
                                                        <div class="title">
                                                            <h3>{{__('Landing Page Settings')}}</h3>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-area plr-65">
                                            <form enctype="multipart/form-data" method="POST" action="{{route('adminCmsSettingSave')}}">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label for="#">{{__('Landing Page Title')}}</label>
                                                                    <input type="text" name="landing_title" @if(isset(allsetting() ['landing_title'])) value="{{allsetting()['landing_title']}}" @endif>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="#">{{__('Landing Page Button Link')}}</label>
                                                                    <input type="text" name="landing_button_link" @if(isset(allsetting()['landing_button_link'])) value="{{allsetting()['landing_button_link']}}" @endif>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="#">{{__('Landing Page iOS App Link')}}</label>
                                                                    <input type="text" name="landing_ios_app_link" @if(isset(allsetting()['landing_ios_app_link'])) value="{{allsetting()['landing_ios_app_link']}}" @endif>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="#">{{__('Landing Page Android App Link')}}</label>
                                                                    <input type="text" name="landing_and_app_link" @if(isset(allsetting()['landing_and_app_link'])) value="{{allsetting()['landing_and_app_link']}}" @endif>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="#">{{__('Landing Page Description')}}</label>
                                                                    <textarea class="form-control" rows="5" name="landing_description">@if(isset(allsetting()['landing_description'])){{allsetting()['landing_description']}} @endif</textarea>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-lg-12">
                                                                        <div class="form-group">

                                                                            <label for="#">{{__('Landing Page Image')}}</label>
                                                                            <div id="file-upload" class="section-width">
                                                                                <input type="hidden" name="landing_page_logo" value="">
                                                                                <input type="file" placeholder="0.00" name="landing_page_logo"
                                                                                       value="" id="file" ref="file" class="dropify" @if(isset(allsetting()['landing_page_logo']) && (!empty(allsetting()['landing_page_logo']))) data-default-file="{{asset(path_image().allsetting()['landing_page_logo'])}}" @endif />
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                @if(isset($itech))
                                                                    <input type="hidden" name="itech" value="{{$itech}}">
                                                                @endif
                                                                <button class="button-primary">{{__('Update')}}</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade  @if(isset($tab) && $tab=='screenshot')show active @endif " id="screenshot" role="tabpanel" aria-labelledby="header-setting-tab">
                                        <div class="page-title">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="page-title-inner">
                                                        <div class="title">
                                                            <h3>{{__('Landing Page screenshots')}}</h3>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-area plr-65">
                                            <form enctype="multipart/form-data" method="POST" action="{{route('adminCmsSettingSave')}}">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label for="#">{{__('Landing Page screenshot Title')}}</label>
                                                                    <input type="text" name="landing_screenshot_title" @if(isset(allsetting()['landing_screenshot_title'])) value="{{allsetting()['landing_screenshot_title']}}" @endif>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="#">{{__('Landing Page screenshot subtitle')}}</label>
                                                                    <input type="text" name="landing_screenshot_subtitle" @if(isset(allsetting()['landing_screenshot_subtitle'])) value="{{allsetting()['landing_screenshot_subtitle']}}" @endif>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="#">{{__('Landing Page Button Link')}}</label>
                                                                    <input type="text" name="button_link" @if(isset(allsetting()['landing_button_link'])) value="{{allsetting()['landing_button_link']}}" @endif>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-lg-4">
                                                                        <div class="form-group">
                                                                            <label for="#">{{__('Admin 1st screenshot')}}</label>
                                                                            <div id="file-upload" class="section-width">
                                                                                <input type="hidden" name="admin_1st_screenshot" value="">
                                                                                <input type="file" placeholder="0.00" name="admin_1st_screenshot" value="" id="file" ref="file"
                                                                                       class="dropify" @if(isset(allsetting()['admin_1st_screenshot']) && (!empty(allsetting()['admin_1st_screenshot']))) data-default-file="{{asset(path_image().allsetting()['admin_1st_screenshot'])}}" @endif />
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">

                                                                            <label for="#">{{__('Admin 2nd screenshot')}}</label>
                                                                            <div id="file-upload" class="section-width">
                                                                                <input type="hidden" name="admin_2nd_screenshot" value="">
                                                                                <input type="file" placeholder="0.00" name="admin_2nd_screenshot" value="" id="file" ref="file"
                                                                                       class="dropify" @if(isset(allsetting()['admin_2nd_screenshot']) && (!empty(allsetting()['admin_2nd_screenshot']))) data-default-file="{{asset(path_image().allsetting()['admin_2nd_screenshot'])}}" @endif />
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="#">{{__('Admin 3rd screenshot')}}</label>
                                                                            <div id="file-upload" class="section-width">
                                                                                <input type="hidden" name="admin_3rd_screenshot" value="">
                                                                                <input type="file" placeholder="0.00" name="admin_3rd_screenshot" value="" id="file" ref="file"
                                                                                       class="dropify" @if(isset(allsetting()['admin_3rd_screenshot']) && (!empty(allsetting()['admin_3rd_screenshot']))) data-default-file="{{asset(path_image().allsetting()['admin_3rd_screenshot'])}}" @endif />
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-4">
                                                                        <div class="form-group">
                                                                            <label for="#">{{__('User 1st screenshot')}}</label>
                                                                            <div id="file-upload" class="section-width">
                                                                                <input type="hidden" name="user_1st_screenshot" value="">
                                                                                <input type="file" placeholder="0.00" name="user_1st_screenshot" value="" id="file" ref="file"
                                                                                       class="dropify" @if(isset(allsetting()['user_1st_screenshot']) && (!empty(isset(allsetting()['user_1st_screenshot'])))) data-default-file="{{asset(path_image().allsetting()['user_1st_screenshot'])}}" @endif />
                                                                            </div>
                                                                        </div>

                                                                        <div class="form-group">

                                                                            <label for="#">{{__('User 2nd screenshot')}}</label>
                                                                            <div id="file-upload" class="section-width">
                                                                                <input type="hidden" name="user_2nd_screenshot" value="">
                                                                                <input type="file" placeholder="0.00" name="user_2nd_screenshot" value="" id="file" ref="file"
                                                                                       class="dropify" @if(isset(allsetting()['user_2nd_screenshot']) && (!empty(isset(allsetting()['user_2nd_screenshot'])))) data-default-file="{{asset(path_image().allsetting()['user_2nd_screenshot'])}}" @endif />
                                                                            </div>
                                                                        </div>

                                                                        <div class="form-group">

                                                                            <label for="#">{{__('User 3rd screenshot')}}</label>
                                                                            <div id="file-upload" class="section-width">
                                                                                <input type="hidden" name="users_3rd_screenshot" value="">
                                                                                <input type="file" placeholder="0.00" name="users_3rd_screenshot" value="" id="file" ref="file"
                                                                                       class="dropify" @if(isset(allsetting()['users_3rd_screenshot']) && (!empty(isset(allsetting()['users_3rd_screenshot'])))) data-default-file="{{asset(path_image().allsetting()['users_3rd_screenshot'])}}" @endif />
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-lg-4">
                                                                        <div class="form-group">

                                                                            <label for="#">{{__('App 1st screenshot')}}</label>
                                                                            <div id="file-upload" class="section-width">
                                                                                <input type="hidden" name="app_1st_screenshot" value="">
                                                                                <input type="file" placeholder="0.00" name="app_1st_screenshot" value="" id="file" ref="file"
                                                                                       class="dropify" @if(isset(allsetting()['app_1st_screenshot']) && (!empty(allsetting()['app_1st_screenshot']))) data-default-file="{{asset(path_image().allsetting()['app_1st_screenshot'])}}"@endif />
                                                                            </div>
                                                                        </div>

                                                                        <div class="form-group">

                                                                            <label for="#">{{__('App 2nd screenshot')}}</label>
                                                                            <div id="file-upload" class="section-width">
                                                                                <input type="hidden" name="app_2nd_screenshot" value="">
                                                                                <input type="file" placeholder="0.00" name="app_2nd_screenshot" value="" id="file" ref="file"
                                                                                       class="dropify" @if(isset(allsetting()['app_2nd_screenshot']) && (!empty(allsetting()['app_2nd_screenshot']))) data-default-file="{{asset(path_image().allsetting()['app_2nd_screenshot'])}}"@endif />
                                                                            </div>
                                                                        </div>

                                                                        <div class="form-group">

                                                                            <label for="#">{{__('App 3rd screenshot')}}</label>
                                                                            <div id="file-upload" class="section-width">
                                                                                <input type="hidden" name="app_3rd_screenshot" value="">
                                                                                <input type="file" placeholder="0.00" name="app_3rd_screenshot" value="" id="file" ref="file"
                                                                                       class="dropify" @if(isset(allsetting()['app_3rd_screenshot']) && (!empty(allsetting()['app_3rd_screenshot']))) data-default-file="{{asset(path_image().allsetting()['app_3rd_screenshot'])}}"@endif />
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                @if(isset($itech))
                                                                    <input type="hidden" name="itech" value="{{$itech}}">
                                                                @endif
                                                                <button class="button-primary">{{__('Update')}}</button>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <!-- genarel-setting start-->
                                    <div class="tab-pane fade  @if(isset($tab) && $tab=='about_as')show active @endif "
                                         id="about_as" role="tabpanel" aria-labelledby="header-setting-tab">
                                        <div class="page-title">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="page-title-inner">
                                                        <div class="title">
                                                            <h3>{{__('Landing Page About us Settings')}}</h3>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-area plr-65">
                                            <form enctype="multipart/form-data" method="POST"
                                                  action="{{route('adminCmsSettingSave')}}">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label for="#">{{__('About us title')}}</label>
                                                                    <input type="text" name="about_us_title" @if(isset(allsetting()['about_us_title'])) value="{{allsetting()['about_us_title']}}" @endif>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="#">{{__('About us sub-title')}}</label>
                                                                    <input type="text" name="about_us_sub_title" @if(isset(allsetting()['about_us_sub_title'])) value="{{allsetting()['about_us_sub_title']}}" @endif>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="#">{{__('User Panel details')}}</label>
                                                                    <textarea class="form-control" rows="5" name="user_panel_details">@if(isset(allsetting()['user_panel_details'])){{allsetting()['user_panel_details']}} @endif</textarea>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="#">{{__('Admin Panel details')}}</label>
                                                                    <textarea class="form-control" rows="5" name="admin_panel_details">@if(isset(allsetting()['admin_panel_details'])){{allsetting()['admin_panel_details']}} @endif</textarea>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="#">{{__('App details')}}</label>
                                                                    <textarea class="form-control" rows="5" name="app_details">@if(isset(allsetting()['app_details'])){{allsetting()['app_details']}} @endif</textarea>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-lg-12">
                                                                        <div class="form-group">
                                                                            <label for="#">{{__('About us Image')}}</label>
                                                                            <div id="file-upload" class="section-width">
                                                                                <input type="hidden" name="about_us_logo" value="">
                                                                                <input type="file" placeholder="0.00" name="about_us_logo" value="" id="file" ref="file"
                                                                                       class="dropify" @if(isset(allsetting()['about_us_logo'])) data-default-file="{{asset(path_image().allsetting()['about_us_logo'])}}"@endif />
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                @if(isset($itech))
                                                                    <input type="hidden" name="itech" value="{{$itech}}">
                                                                @endif
                                                                <button class="button-primary">{{__('Update')}}</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade  @if(isset($tab) && $tab=='integration')show active @endif "
                                         id="integration" role="tabpanel" aria-labelledby="header-setting-tab">
                                        <div class="page-title">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="page-title-inner">
                                                        <div class="title">
                                                            <h3>{{__('Integration Settings')}}</h3>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-area plr-65">
                                            <form enctype="multipart/form-data" method="POST"
                                                  action="{{route('adminCmsSettingSave')}}">
                                                @csrf
                                                <div class="row">
                                                    @if(isset($itech))
                                                        <input type="hidden" name="itech" value="{{$itech}}">
                                                    @endif
                                                    <div class="col-12">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="#">{{__('Landing Page Integration Title')}}</label>
                                                                    <input type="text" name="landing_integration_title"
                                                                           @if(isset(allsetting()['landing_integration_title']))value="{{allsetting()['landing_integration_title']}}" @endif>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="#">{{__('Landing Page Integration Button Link')}}</label>
                                                                    <input type="text"
                                                                           name="landing_integration_button_link"
                                                                           @if(isset(allsetting()['landing_integration_button_link']))value="{{allsetting()['landing_integration_button_link']}}" @endif>
                                                                </div>

                                                                <div class="form-group">
                                                                    <label for="#">{{__('Landing Page Integration Description')}}</label>
                                                                    <textarea class="form-control" rows="5"
                                                                              name="landing_integration_description">@if(isset(allsetting()['landing_integration_description'])){{allsetting()['landing_integration_description']}} @endif</textarea>
                                                                </div>
                                                                <div class="row">


                                                                    <div class="col-lg-12">
                                                                        <div class="form-group">

                                                                            <label for="#">{{__('Landing Page integration Image')}}</label>
                                                                            <div id="file-upload" class="section-width">
                                                                                <input type="hidden" name="landing_integration_page_logo" value="">
                                                                                <input type="file" placeholder="0.00" name="landing_integration_page_logo" value="" id="file" ref="file"
                                                                                       class="dropify" @if(isset(allsetting()['landing_integration_page_logo'])) data-default-file="{{asset(path_image().allsetting()['landing_integration_page_logo'])}}"@endif />
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <button class="button-primary">{{__('Update')}}</button>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="#">{{__('Landing Page Integration 2nd Title')}}</label>
                                                                    <input type="text" name="landing_integration_2nd_title"
                                                                           @if(isset(allsetting()['landing_integration_2nd_title']))value="{{allsetting()['landing_integration_2nd_title']}}" @endif>
                                                                </div>

                                                                <div class="form-group">
                                                                    <label for="#">{{__('Landing Page Integration 2nd Button Link')}}</label>
                                                                    <input type="text" name="landing_integration_2nd_button_link"
                                                                           @if(isset(allsetting()['landing_integration_2nd_button_link']))value="{{allsetting()['landing_integration_2nd_button_link']}}" @endif>
                                                                </div>


                                                                <div class="form-group">
                                                                    <label for="#">{{__('Landing Page Integration 2nd Description')}}</label>
                                                                    <textarea class="form-control" rows="10" name="landing_integration_2nd_description">@if(isset(allsetting()['landing_integration_2nd_description'])){{allsetting()['landing_integration_2nd_description']}} @endif</textarea>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-lg-12">
                                                                        <div class="form-group">
                                                                            <label for="#">{{__('Landing Page integration 2nd Image')}}</label>
                                                                            <div id="file-upload" class="section-width">
                                                                                <input type="hidden" name="landing_page_2nd_logo" value="">
                                                                                <input type="file" placeholder="0.00" name="landing_page_2nd_logo" value="" id="file" ref="file"
                                                                                       class="dropify" @if(isset(allsetting()['landing_page_2nd_logo'])) data-default-file="{{asset(path_image().allsetting()['landing_page_2nd_logo'])}}"@endif />
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade  @if(isset($tab) && $tab=='features')show active @endif "
                                         id="features" role="tabpanel" aria-labelledby="header-setting-tab">
                                        <div class="page-title">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="page-title-inner">
                                                        <div class="title">
                                                            <h3>{{__('Landing Page Features Settings')}}</h3>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-area plr-65">
                                            <form enctype="multipart/form-data" method="POST"
                                                  action="{{route('adminCmsSettingSave')}}">
                                                @csrf
                                                <div class="row">
                                                    @if(isset($itech))
                                                        <input type="hidden" name="itech" value="{{$itech}}">
                                                    @endif
                                                    <div class="col-12">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="#">{{__('Landing Page Features Title')}}</label>
                                                                    <input type="text" name="landing_feature_title"
                                                                           @if(isset(allsetting()['landing_feature_title']))value="{{allsetting()['landing_feature_title']}}" @endif>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="#">{{__('Landing feature subtitle')}}</label>
                                                                    <input type="text" name="landing_feature_subtitle"
                                                                           @if(isset(allsetting()['landing_feature_subtitle']))value="{{allsetting()['landing_feature_subtitle']}}" @endif>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="row">
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label for="#">{{__('1st feature title')}}</label>
                                                                            <input type="text" name="1st_feature_title"
                                                                                   @if(isset(allsetting()['1st_feature_title']))value="{{allsetting()['1st_feature_title']}}" @endif>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="#">{{__('1st feature subtitle')}}</label>
                                                                            <input type="text"
                                                                                   name="1st_feature_subtitle"
                                                                                   @if(isset(allsetting()['1st_feature_subtitle']))value="{{allsetting()['1st_feature_subtitle']}}" @endif>
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <label for="#">{{__('1st feature image')}}</label>
                                                                            <div id="file-upload" class="section-width">
                                                                                <input type="hidden" name="1st_feature_icon" value="">
                                                                                <input type="file" placeholder="0.00" name="1st_feature_icon" value="" id="file" ref="file"
                                                                                       class="dropify" @if(isset(allsetting()['1st_feature_icon'])) data-default-file="{{asset(path_image().allsetting()['1st_feature_icon'])}}"@endif />
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label for="#">{{__('2nd feature title')}}</label>
                                                                            <input type="text" name="2nd_feature_title"
                                                                                   @if(isset(allsetting()['2nd_feature_title']))value="{{allsetting()['2nd_feature_title']}}" @endif>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="#">{{__('2nd feature subtitle')}}</label>
                                                                            <input type="text"
                                                                                   name="2nd_feature_subtitle"
                                                                                   @if(isset(allsetting()['2nd_feature_subtitle']))value="{{allsetting()['2nd_feature_subtitle']}}" @endif>
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <label for="#">{{__('2nd feature image')}}</label>
                                                                            <div id="file-upload" class="section-width">
                                                                                <input type="hidden" name="2nd_feature_icon" value="">
                                                                                <input type="file" placeholder="0.00" name="2nd_feature_icon" value="" id="file" ref="file"
                                                                                       class="dropify" @if(isset(allsetting()['2nd_feature_icon'])) data-default-file="{{asset(path_image().allsetting()['2nd_feature_icon'])}}"@endif />
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label for="#">{{__('3rd feature title')}}</label>
                                                                            <input type="text" name="3rd_feature_title"
                                                                                   @if(isset(allsetting()['3rd_feature_title']))value="{{allsetting()['3rd_feature_title']}}" @endif>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="#">{{__('3rd feature subtitle')}}</label>
                                                                            <input type="text" name="3rd_feature_subtitle"
                                                                                   @if(isset(allsetting()['3rd_feature_subtitle']))value="{{allsetting()['3rd_feature_subtitle']}}" @endif>
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <label for="#">{{__('3rd feature image')}}</label>
                                                                            <div id="file-upload" class="section-width">
                                                                                <input type="hidden" name="3rd_feature_icon" value="">
                                                                                <input type="file" placeholder="0.00" name="3rd_feature_icon" value="" id="file" ref="file"
                                                                                       class="dropify" @if(isset(allsetting()['3rd_feature_icon'])) data-default-file="{{asset(path_image().allsetting()['3rd_feature_icon'])}}"@endif />
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <button class="button-primary">{{__('Update')}}</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade  @if(isset($tab) && $tab=='static')show active @endif "
                                         id="static" role="tabpanel" aria-labelledby="static-content-tab">
                                        <div class="userlist-wrap form-style">
                                            <div class="table-area">
                                                <h4>{{__('Landing Page content')}}</h4>
                                                <ul class="nav user-menu justify-content-start pull-right">
                                                    <li>
                                                        <a class="add-btn" href="{{route('adminCmsLandingPpageContentAdd')}}">
                                                            <i class="fa fa-plus"></i>
                                                            {{__('Add')}}
                                                        </a>
                                                    </li>
                                                </ul>
                                                <div class="table-responsive">
                                                    <table class="table" id="table">
                                                        <thead>
                                                        <tr>
                                                            <th scope="col">{{__('Image')}}</th>
                                                            <th scope="col">{{__('Title')}}</th>
                                                            <th scope="col">{{__('Priority')}}</th>
                                                            <th scope="col">{{__('Actions')}}</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade  @if(isset($tab) && $tab=='footer')show active @endif " id="footer">
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
        $('#table').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 10,
            retrieve: true,
            bLengthChange: true,
            responsive: true,
            ajax: '{{route('adminCmsLandingPpageContent')}}',
            order: [2, 'desc'],
            autoWidth:false,
            language: {
                paginate: {
                    next: 'Next &#8250;',
                    previous: '&#8249; Previous'
                }
            },
            columns: [
                {"data": "image"},
                {"data": "title"},
                {"data": "priority"},
                {"data": "actions"}
            ]
        });
    </script>
@endsection
