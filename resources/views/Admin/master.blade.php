<!DOCTYPE HTML>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="author" content="itechTheme">
    <meta name="description" content="Secured crypto wallet platform">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- title here -->
    <title>{{settings('app_title')}}::@yield('title')</title>
    <!-- Favicon and Touch Icons -->
    <link rel="shortcut icon" href="{{landingPageImage('favicon','images/fav.png')}}/">
    <!-- Place favicon.ico in the root directory -->
    <link rel="apple-touch-icon" href="apple-touch-icon.png">
    <!-- Fonts -->
    <link href="{{asset('assets/css/gfont.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('admin/css')}}/font-awesome.min.css">
    <link rel="stylesheet" href="{{asset('admin/css')}}/flaticon.css">
    <!-- Plugin CSS -->
    <link rel="stylesheet" href="{{asset('admin/css')}}/bootstrap.min.css">
    <link rel="stylesheet" href="{{asset('admin/css')}}/animate.css">
    <link rel="stylesheet" href="{{asset('admin/css')}}/owl.carousel.min.css">
    <link rel="stylesheet" href="{{asset('admin/css')}}/owl.theme.default.min.css">
    <link rel="stylesheet" href="{{asset('admin/css')}}/metisMenu.css">
    <link rel="stylesheet" href="{{asset('admin/css')}}/scrollbar.css">
    <link rel="stylesheet" href="{{asset('admin/css')}}/perfect-scrollbar.css">
    <link rel="stylesheet" href="{{asset('admin/css')}}/model.css">
    <link rel="stylesheet" href="{{asset('user/assets')}}/css/dropify.css">
    <link rel="stylesheet" href="{{asset('admin/css/custom.css')}}" />
    <script src="{{asset('assets/js/vendor/modernizr-3.6.0.min.js')}}"></script>
    <link href="{{asset('assets/toast/vanillatoasts.css')}}" rel="stylesheet" >
    <link rel="stylesheet" href="{{asset('assets/DataTables/css/jquery.dataTables.min.css')}}">
    <!--Theme custom css -->
    <link rel="stylesheet" href="{{asset('admin/css')}}/style.css">
    <!--Theme Responsive css-->
    <link rel="stylesheet" href="{{asset('admin/css')}}/responsive.css" />
    @yield('style')
</head>
<?php
$submenu = (!empty($submenu)) ? $submenu : '';
?>
<body class="body-class">
<!-- header area start here -->
<header class="header-area">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="header-inner">
                    <div class="hader-left">
                        <div class="menubar-area bars">
                            <span class="one bar"></span>
                            <span class="two bar"></span>
                            <span class="three bar"></span>
                        </div>
                    </div>
                    <div class="header-right">
                        <div class="user-area">
                            <ul>
                                <li class="user-img"><a href="#"><img src="{{imageSrcUser(Auth::user()->photo,IMG_USER_VIEW_PATH)}}" alt="user"></a></li>
                                <li class="user-profile"><a href="#">{{\Illuminate\Support\Facades\Auth::user()->first_name.' '.\Illuminate\Support\Facades\Auth::user()->last_name}}  <i class="fa fa-caret-down"></i></a>
                                    <ul class="drop-down user-dropdown">
                                        <li><a href="{{route('adminProfile')}}"> <i class="fa fa-user-circle-o"></i> {{__('Profile')}}</a></li>
                                        <li><a href="{{route('logoutUser')}}"> <i class="fa fa-power-off"></i> {{__('Logout')}}</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- header area end here -->
<!-- left-sidebar area start here -->
<div class="left-sidebar">
    <div class="logo-area admin_logo">
        <a href="{{route('AdminDashboard')}}"><img src="{{landingPageImage('logo','images/logo.png')}}" alt=""></a>
    </div>
    <nav class="left-menu">
        <ul class="metismenu" id="menu">
           <li class=" @if($menu == 'dashboard') active @endif">
            <a href="{{route('AdminDashboard')}}"> <span class="flaticon-dashboard"></span> {{__('Dashboard')}}</a>
           </li>
            <li class="sidemenu-items custom_icon @if(isset($menu) && $menu=='crypto_wallet') active @endif "><a aria-expanded="false" href="javascript:void(0);"><img src="{{asset('admin/images/icons/wallet.svg')}}" alt="">{{__('Wallets')}}</a>
                <ul aria-expanded="false">
                    <li class="sidemenu-items custom_icon @if(isset($submenu) && $submenu=='wallet') active @endif"><a aria-expanded="false" href="{{route('adminWallets')}}"><img src="{{asset('admin/images/icons/user.svg')}}" alt=""> {{__('User Wallets')}}</a></li>
                </ul>
            </li>
            <li class="sidemenu-items custom_icon @if(isset($menu) && $menu=='transactions') active @endif "><a aria-expanded="false" href="javascript:void(0);"><img src="{{asset ('admin/images/icons/history.svg')}}" alt="">{{__('Transaction Report')}}</a>
                <ul aria-expanded="false">
                    <li class="sidemenu-items custom_icon @if(isset($submenu) && $submenu=='all_transaction') active @endif"><a aria-expanded="false" href="{{route('adminDepositHistory')}}"><img src="{{asset('admin/images/icons/deposit.svg')}}" alt=""> {{__('Transaction Histories')}}</a></li>
                    <li class="sidemenu-items custom_icon @if(isset($submenu) && $submenu=='transaction_hash') active @endif"><a aria-expanded="false" href="{{route('adminTransactionHashDetails')}}"><img src="{{asset('admin/images/icons/history.svg')}}" alt=""> {{__('Node History')}}</a></li>
                    <li class="sidemenu-items custom_icon @if(isset($submenu) && $submenu=='pending_withdrawal') active @endif"><a aria-expanded="false" href="{{route('adminPendingWithdrawal')}}"><img src="{{asset('admin/images/icons/pending_withdrawal.svg')}}" alt=""> {{__('Pending Withdrawal')}}</a></li>
                </ul>
            </li>
            <li class="sidemenu-items custom_icon @if(isset($menu) && $menu=='pending_transaction') active @endif" ><a href="{{route('adminPendingDeposit')}}"> <span class="fa fa-btc"></span> {{__('Buy Coin Order List')}}</a></li>
            <li class="@if(isset($menu) && $menu=='bank') active @endif">
                <a class="" href="{{route('bankList')}}"> <span><img src="{{asset('admin/images/icons/bank management.svg')}}" alt=""></span> {{__('Bank Management')}}</a>
            </li>
            <li class="sidemenu-items custom_icon @if($menu == 'users') active @endif">
                <a href="javascript:void(0)" aria-expanded="true"> <span class="flaticon-user-1"></span> {{__('User management')}}</a>
                <ul>
                    <li><a  @if($submenu == 'users') class="active" @endif href="{{route('admin.users')}}"><img src="{{asset('admin/images/icons/user.svg')}}" alt="">{{__('User')}}</a></li>
                    <li><a @if($submenu == 'pendingid') class="active" @endif href="{{route('adminUserIdVerificationPending')}}"><img src="{{asset('admin/images/icons/id-verification.svg')}}" alt="">{{__('Pending ID Verification')}}</a></li>
                </ul>
            </li>
            <li class="sidemenu-items custom_icon @if(isset($menu) && $menu=='setting') active @endif ">
                <a href="javascript:void(0)" aria-expanded="true"> <span class="flaticon-settings"></span> Settings</a>
                <ul>
                    <li><a class="@if(isset($sub_menu) && $sub_menu=='setting') active @endif" href="{{route('adminSettings')}}"><img src="{{asset('admin/images/icons/settings.svg')}}" alt="">{{__('Settings')}}</a></li>
                    <li><a class="@if(isset($sub_menu) && $sub_menu=='cms') active @endif" href="{{route('adminCmsSetting')}}"><img src="{{asset('admin/images/icons/landing-page.svg')}}" alt="">{{__('Landing Page')}}</a></li>
                </ul>
            </li>
            <li class="@if(isset($menu) && $menu=='cp') active @endif">
                <a class="" href="{{route('adminCustomPageList')}}">
                    <span><img src="{{asset('admin/images/icons/custom-page.svg')}}" alt=""></span>{{__('Custom Pages')}}
                </a>
            </li>
            <li class="@if(isset($menu) && $menu=='notification') active @endif">
                <a class="" href="{{route('adminSendNotification')}}">
                    <span><img src="{{asset('admin/images/icons/bulk-email.svg')}}" alt=""></span>{{__('Bulk Email')}}
                </a>
            </li>
        </ul>
    </nav>
    <div class="close">
        <span>X</span>
    </div>
</div>
@yield('content')
<script src="{{asset('admin/js')}}/vendor/jquery-3.3.1.min.js"></script>
<script src="{{asset('admin/js')}}/plugins.js"></script>
<script src="{{asset('admin/js')}}/Popper.js"></script>
<script src="{{asset('admin/js')}}/bootstrap.min.js"></script>
<script src="{{asset('admin/js')}}/scrollup.js"></script>
<script src="{{asset('admin/js')}}/owl.carousel.min.js"></script>
<script src="{{asset('admin/js')}}/metisMenu.min.js"></script>
<script src="{{asset('admin/js')}}/scrollbar.min.js"></script>
<script src="{{asset('admin/js')}}/perfect-scrollbar.min.js"></script>
<script src="{{asset('admin/js')}}/jquery.knob.js"></script>
<script src="{{asset('admin/js')}}/jquery.appear.js"></script>
<script src="{{asset('admin/js')}}/model.js"></script>
<script src="{{asset('user/assets')}}/js/dropify.js"></script>
<script src="{{asset('user/assets')}}/js/form-file-uploads.js"></script>
<script src="{{asset('admin/js')}}/main.js"></script>
<script src="{{asset('assets/toast/vanillatoasts.js')}}"></script>
<script src="{{asset('assets/DataTables/js/jquery.dataTables.min.js')}}"></script>
<script>
    // scrollbar-plugin
    new PerfectScrollbar('.left-sidebar');
</script>

@if(session()->has('success'))
    <script>
        window.onload = function () {
            VanillaToasts.create({
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
<script>
    /* Add here all your JS customizations */
    $('.number-only').keypress(function (e) {
        alert(11);
        var regex = /^[+0-9+.\b]+$/;
        var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
        if (regex.test(str)) {
            return true;
        }
        e.preventDefault();
        return false;
    });
    $('.no-regx').keypress(function (e) {
        var regex = /^[a-zA-Z+0-9+\b]+$/;
        var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
        if (regex.test(str)) {
            return true;
        }
        e.preventDefault();
        return false;
    });
</script>

@yield('script')
</body>
</html>