<!DOCTYPE HTML>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="description" content="The Highly Secured Bitcoin Wallet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta property="og:type" content="article" />
    <meta property="og:title" content="{{allsetting('app_title')}}"/>
    <meta property="og:image" content="{{asset('assets/images/logo.png')}}">
    <meta property="og:site_name" content="MTCore"/>
    <meta property="og:url" content="{{url()->current()}}"/>
    <meta property="og:type" content="{{allsetting('app_title')}}"/>
    <meta itemscope itemtype="{{ url()->current() }}/{{allsetting('app_title')}}" />
    <meta itemprop="headline" content="{{allsetting('app_title')}}" />
    <meta itemprop="image" content="{{asset('assets/images/logo.png')}}" />
    <!-- title here -->
    <title>{{allsetting('app_title')}}</title>
    <!-- Favicon and Touch Icons -->
    <link rel="shortcut icon" href="{{asset('/')}}assets/images/fav.png">
    <!-- Place favicon.ico in the root directory -->
    <link rel="apple-touch-icon" href="apple-touch-icon.png">
    <!-- Fonts -->
    <link href="{{asset('assets/css/gfont.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('/')}}assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{asset('/')}}assets/css/flaticon.css">
    <!-- Plugin CSS -->
    <link rel="stylesheet" href="{{asset('/')}}assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{asset('/')}}assets/css/animate.css">
    <link rel="stylesheet" href="{{asset('/')}}assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="{{asset('/')}}assets/css/owl.theme.default.min.css">
    <link rel="stylesheet" href="{{asset('/')}}assets/css/metisMenu.css">
    <link rel="stylesheet" href="{{asset('/')}}assets/css/scrollbar.css">
    <link rel="stylesheet" href="{{asset('/')}}assets/css/model.css">
    <!--Theme custom css -->
    <link rel="stylesheet" href="{{asset('/')}}assets/css/style.css">
    <!--Theme Responsive css-->
    <link rel="stylesheet" href="{{asset('/')}}assets/css/responsive.css" />
    <script src="{{asset('/')}}assets/js/vendor/modernizr-3.6.0.min.js"></script>
</head>
<body class="body-class">
<!-- sign-from-area start here -->
<div class="from-field-area">
    <div class="container">
        <div class="row acurate">
            <div class="col-lg-6 acurate">
                <div class="from-left height-section d-flex justify-content-center align-items-center">
                    <div class="site-logo">
                        <a href="{{url('/')}}"><img src="{{landingPageImage('login_logo','/images/biglogo.png')}}" class="img-fluid" alt="logo"></a>
                    </div>
                </div>
            </div>

           @yield('content')
        </div>
    </div>
</div>
<!-- sign-from-area start here -->
<!-- js file start -->
<script src="{{asset('/')}}assets/js/vendor/jquery-3.3.1.min.js"></script>
<script src="{{asset('/')}}assets/js/plugins.js"></script>
<script src="{{asset('/')}}assets/js/Popper.js"></script>
<script src="{{asset('/')}}assets/js/bootstrap.min.js"></script>
<script src="{{asset('/')}}assets/js/scrollup.js"></script>
<script src="{{asset('/')}}assets/js/owl.carousel.min.js"></script>
<script src="{{asset('/')}}assets/js/metisMenu.min.js"></script>
<script src="{{asset('/')}}assets/js/scrollbar.min.js"></script>
<script src="{{asset('/')}}assets/js/jquery.knob.js"></script>
<script src="{{asset('/')}}assets/js/jquery.appear.js"></script>
<script src="{{asset('/')}}assets/js/model.js"></script>
<script src="{{asset('/')}}assets/js/main.js"></script>
@yield('script')
<!-- End js file -->
@yield('script')
</body>
</html>


