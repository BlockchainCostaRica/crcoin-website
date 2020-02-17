<!DOCTYPE HTML>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="author" content="smartit-source">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- title here -->
    <title>{{settings('app_title')}}::@yield('title')</title>
    <!-- Favicon and Touch Icons -->
    <link rel="shortcut icon" href="{{landingPageImage('favicon','images/fav.png')}}/">
    <!-- Place favicon.ico in the root directory -->
    <link rel="apple-touch-icon" href="apple-touch-icon.png">
    <!-- Fonts -->
    <link href="{{asset('assets/css/gfont.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('user/assets')}}/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{asset('user/assets')}}/css/flaticon.css">
    <!-- Plugin CSS -->
    <link rel="stylesheet" href="{{asset('user/assets')}}/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{asset('user/assets')}}/css/animate.css">
    <link rel="stylesheet" href="{{asset('user/assets')}}/css/owl.carousel.min.css">
    <link rel="stylesheet" href="{{asset('user/assets')}}/css/owl.theme.default.min.css">
    <link rel="stylesheet" href="{{asset('user/assets')}}/css/model.css">
    <link rel="stylesheet" href="{{asset('user/assets')}}/css/nice-select.css">
    <link rel="stylesheet" href="{{asset('user/assets')}}/css/basictable.css">
    <link href="{{asset('assets/toast/vanillatoasts.css')}}" rel="stylesheet" >
    <link rel="stylesheet" href="{{asset('assets/css/bootstrap3.3.5.min.css')}}">
    <link rel="stylesheet" href="{{asset('user/assets')}}/css/dropify.css">
    <link rel="stylesheet" href="{{asset('assets/phonebox')}}/css/intlTelInput.css">
    <link rel="stylesheet" href="{{asset('assets/phonebox')}}/css/demo.css">
    <!--Theme custom css -->
    <link rel="stylesheet" href="{{asset('user/assets')}}/css/style.css">
    <!--Theme Responsive css-->
    <link rel="stylesheet" href="{{asset('user/assets')}}/css/responsive.css" />
    <script src="{{asset('user/assets')}}/js/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('assets/DataTables/css/jquery.dataTables.min.css')}}">
    @yield('style')
</head>
<body class="body-class">
<!-- header area start here -->
<header class="header-area">
    <!-- header top area start here -->
    <div class="header-top-area">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="logo-area">
                        <a href="{{route('UserDashboard')}}"><img src="{{landingPageImage('logo','images/logo.png')}}" alt=""></a>
                    </div>
                    <div class="header-right">
                        <div class="menu-area">
                            <nav class="main-menu">
                                <ul>
                                    <li @if($menu == 'dashboard') class="active" @endif ><a href="{{route('UserDashboard')}}">Dashboard</a></li>
                                    <li @if($menu == 'buy_coin') class="active" @endif ><a href="{{route('buyCoin')}}">Buy Coin</a></li>
                                    <li @if($menu == 'my_wallet') class="active" @endif ><a href="{{route('myWallet')}}">My Wallet</a></li>
                                    <li @if($menu == 'my_profile') class="active" @endif ><a href="{{route('myProfile')}}">My Profile</a></li>
                                    <li @if($menu == 'settings') class="active" @endif ><a href="{{route('settings')}}">Settings</a></li>
                                    <li @if($menu == 'refferal') class="active" @endif ><a href="{{route('referral')}}">Referral</a></li>
                                </ul>
                            </nav>
                        </div>
                        <div class="profile-area">
                            <ul>
                                <li><a href="#"><img src="{{imageSrcUser(\Illuminate\Support\Facades\Auth::user()->photo,IMG_USER_VIEW_PATH)}}" alt="profile"></a></li>
                                <li class="profile-menu"><a href="#">{{\Illuminate\Support\Facades\Auth::user()->first_name.' '.\Illuminate\Support\Facades\Auth::user()->last_name}} <i class="fa fa-caret-down"></i> </a>
                                    <ul class="profile-dropdown">
                                        <li><a href="{{route('myProfile')}}"> <i class="fa fa-pencil-square-o"></i> {{__('My Profile')}}</a></li>
                                        <li><a href="{{route('settings')}}"> <i class="fa fa-cog"></i> {{__('Settings')}}</a></li>
                                        <li><a href="{{route('logoutUser')}}"> <i class="fa fa-power-off"></i> {{__('Logout')}}</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <div class="menu-bar">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- header top area end here -->
    <!-- header bottom area start here -->
    <div class="header-bottom-area">
        <div class="container">
            <div class="row">

            </div>
            <div class="row">
                <div class="col-lg-3">
                    <div class="page-title">
                        <h2 class="title"> <span class="flaticon-wallet"></span>@if(isset($title)) {!! clean($title) !!} @else Crypto Wallet @endif</h2>
                        <div class="bradcumbs-area">
                            <p>{{settings('app_title')}} <i class="fa fa-caret-right"></i> <span>@if(isset($title)) {{$title}} @endif</span></p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="blance-area">
                        @php
                        $balance = getUserBalance(Auth::id());
                        @endphp
                        <div class="single-balance available-balance">
                            <h4 class="balance-title">{{__('Available Balance')}}</h4>
                            <h4 class="balance">{{number_format($balance['available_coin'],8)}} <span>{{settings('coin_name')}}</span> </h4>
                            <h4 class="balance">{{number_format($balance['available_used'],8)}}<span> USD</span></h4>
                            <div class="blance-icon">
                                <span class="flaticon-bitcoin-1"></span>
                            </div>
                        </div>
                        <div class="single-balance pending-balance">
                            <h4 class="balance-title">{{__('Pending Withdrawal')}}</h4>
                            <h4 class="balance">{{number_format($balance['pending_withdrawal_coin'],8)}} <span>{{settings('coin_name')}}</span> </h4>
                            <h4 class="balance">{{number_format($balance['pending_withdrawal_usd'],8)}}<span> USD</span></h4>
                            <div class="blance-icon">
                                <span class="flaticon-bitcoin-1"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- header bottom area end here -->
</header>
<!-- sidebar-box area start here -->
<div class="sidebar-box ">
    <div class="close-menu ">
        <span class="bar first "></span>
        <span class="bar second"></span>
    </div>
    <div>
        <ul class="sidebar-menu">
            <li @if($menu == 'dashboard') class="active" @endif ><a href="{{route('UserDashboard')}}">Dashboard</a></li>
            <li @if($menu == 'buy_coin') class="active" @endif ><a href="{{route('buyCoin')}}">Buy Coin</a></li>
            <li @if($menu == 'my_wallet') class="active" @endif ><a href="{{route('myWallet')}}">My Wallet</a></li>
            <li @if($menu == 'my_profile') class="active" @endif ><a href="{{route('myProfile')}}">My Profile</a></li>
            <li @if($menu == 'settings') class="active" @endif ><a href="{{route('settings')}}">Settings</a></li>
            <li @if($menu == 'refferal') class="active" @endif ><a href="{{route('referral')}}">Referral</a></li>
        </ul>
    </div>
</div>
<!-- sidebar-box area start here -->
<!-- header area end here -->
<!-- dashbord-area start here -->
<div id="app">
    @yield('content')
</div>
<!-- dashbord-area end here -->
<!-- js file start -->

<script src="{{asset('user/assets')}}/js/vendor/jquery.min.js"></script>
<script src="{{asset('user/assets')}}/js/plugins.js"></script>
<script src="{{asset('user/assets')}}/js/Popper.js"></script>
<script src="{{asset('user/assets')}}/js/bootstrap.min.js"></script>
<script src="{{asset('user/assets')}}/js/owl.carousel.min.js"></script>
<script src="{{asset('user/assets')}}/js/scrollup.js"></script>
<script src="{{asset('user/assets')}}/js/wow.min.js"></script>
<script src="{{asset('user/assets')}}/js/model.js"></script>
<script src="{{asset('user/assets')}}/js/jquery.nice-select.js"></script>
<script src="{{asset('user/assets')}}/js/jquery.basictable.js"></script>
<script src="{{asset('user/assets')}}/js/jquery.knob.js"></script>
<script src="{{asset('user/assets')}}/js/jquery.appear.js"></script>
<script src="{{asset('user/assets')}}/js/main.js"></script>

<script src="{{asset('user/assets')}}/js/utils.js"></script>
<script src="{{asset('assets/js/jquery3.4.1.min.js')}}"></script>
<script src="{{asset('assets/js/bootstrap3.4.min.js')}}"></script>
<script src="{{ mix('/js/app.js') }}"></script>
<script src="{{asset('assets/DataTables/js/jquery.dataTables.min.js')}}"></script>

<script src="{{asset('assets/toast/vanillatoasts.js')}}"></script>
 @yield('script')

@if(session()->has('success'))
    <script>
        window.onload = function () {

            VanillaToasts.create({
              //  title: 'Message Title',
                text: '{{session('success')}}',
                backgroundColor: "linear-gradient(135deg, #73a5ff, #5477f5)",
                type: 'success',
                timeout: 3000


            });
        }
    </script>
@elseif(session()->has('dismiss'))
    <script>
        window.onload = function () {

            VanillaToasts.create({
               // title: 'Message Title',
                text: '{{session('dismiss')}}',
                type: 'warning',
                timeout: 3000

            });
        }
    </script>
@elseif($errors->any())
    @foreach($errors->getMessages() as $error)
        <script>
            window.onload = function () {

                VanillaToasts.create({
                    // title: 'Message Title',
                    text: '{{    $error[0]}}',
                    type: 'warning',
                    timeout: 3000

                });
            }
        </script>
        @break
    @endforeach
@endif
<!-- End js file -->
</body>
</html>