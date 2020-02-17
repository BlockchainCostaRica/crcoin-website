<!DOCTYPE HTML>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="author" content="itechtheme">
    <meta name="description" content="The Highly Secured Bitcoin Wallet">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- title here -->
    <title>{{settings('app_title')}}</title>

    <!-- Favicon and Touch Icons -->
    <link rel="shortcut icon" href="{{landingPageImage('favicon','images/fav.png')}}/">
    <!-- Place favicon.ico in the root directory -->
    <link rel="apple-touch-icon" href="apple-touch-icon.png">
    <!-- Fonts -->
    <link href="{{asset('assets/css/gfont.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('landing')}}/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{asset('landing')}}/css/flaticon.css">
    <!-- Plugin CSS -->
    <link rel="stylesheet" href="{{asset('landing')}}/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{asset('landing')}}/css/animate.css">
    <link rel="stylesheet" href="{{asset('landing')}}/css/owl.carousel.min.css">
    <link rel="stylesheet" href="{{asset('landing')}}/css/owl.theme.default.min.css">
    <link rel="stylesheet" href="{{asset('landing')}}/css/meanmenu.min.css">
    <link href="{{asset('landing')}}/css/aos.css" rel="stylesheet">
    <!--Theme custom css -->
    <link rel="stylesheet" href="{{asset('landing')}}/css/style.css">
    <!--Theme Responsive css-->
    <link rel="stylesheet" href="{{asset('landing')}}/css/responsive.css" />
    <script src="{{asset('landing')}}/js/vendor/modernizr-3.6.0.min.js"></script>
</head>
<body>
<!-- header-area start here -->
<header class="header-area" id="sticky">
    <div class="container">
        <div class="row">
            <div class="col-md-2">
                <div class="logo-area">
                    <a href="{{url('/')}}"><img src="{{landingPageImage('logo','images/logo.png')}}" alt=""></a>
                </div>
            </div>
            <div class="col-md-10">
                <div class="menu-area text-right">
                    <nav class="main-menu">
                        <ul id="nav">
                            <li class=""><a href="{{route('home')}}">{{__('Home')}}</a></li>

                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- header-area end here -->
<!-- banner area start here -->
<section class="" id="">
<div class="page-title">
    <div class="container">
        <h1>Home / {{$item->key}}</h1>
    </div>
</div>
<div class="container py-5">
        <div class="banner-wrap">
            <div class="row">
                <div class="col-md-12">
                    <div class="banner-text">
                        <div class="banner-title" data-aos="fade-down" data-aos-duration="2000">
                            <h2>{!! clean($item->title) !!}</h2>
                        </div>
                        <div class="banner-content" data-aos="fade-down" data-aos-duration="3000">
                            <p>
                                {!! clean($item->description) !!}
                            </p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- banner area end here -->
<!-- about us area start here -->

<!-- about us area end here -->

<!-- awesome area end here -->

<!-- find-app-area end here -->
<!-- footer area start here -->
<footer class="footer-area">
    <div class="footer-top">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-30">
                    <div class="single-wedgets text-widget">
                        <div class="footer-logo">
                            <img src="{{asset('landing')}}/images/logo.png" alt="footer-logo">
                        </div>
                        <div class="widget-text widget-inner">
                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been text ever since.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-30">
                    <div class="single-wedgets text-widget">
                        <div class="widget-title">
                            <h4>Important Link</h4>
                        </div>
                        <div class="widget-inner">
                            <ul>
                                <li><a href="#">home</a></li>
                                <li><a href="#">Feature</a></li>
                                <li><a href="#">Integrations</a></li>
                                <li><a href="#">About</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-30">
                    <div class="single-wedgets text-widget">
                        <div class="widget-title">
                            <h4>{{__('Awesome Feature')}}</h4>
                        </div>
                        <div class="widget-inner">
                            <ul>
                                @foreach($custom_links as $link)
                                    <li><a href="{{route('getCustomPage',[$link->id,str_replace(' ','-',$link->key)])}}">{{$link->key}}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6">
                    <div class="single-wedgets social-link">
                        <div class="widget-title">
                            <h4>Social Link</h4>
                        </div>
                        <div class="widget-inner">
                            <ul>
                                <li><a href="#">Facebook</a></li>
                                <li><a href="#">Dribbble</a></li>
                                <li><a href="#">Behance</a></li>
                                <li><a href="#">LinkedIn</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="copyright-area text-center">
                        <p>{{settings('copyright_text') }} <a href="{{url('/')}}">{{ settings('app_title')}}</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- footer area end here -->
<!-- js file start -->
<script src="{{asset('landing')}}/js/vendor/jquery-3.3.1.min.js"></script>
<script src="{{asset('landing')}}/js/plugins.js"></script>
<script src="{{asset('landing')}}/js/Popper.js"></script>
<script src="{{asset('landing')}}/js/bootstrap.min.js"></script>
<script src="{{asset('landing')}}/js/scrollup.js"></script>
<script src="{{asset('landing')}}/js/owl.carousel.min.js"></script>
<script src="{{asset('landing')}}/js/jquery.meanmenu.js"></script>
<script src="{{asset('landing')}}/js/jquery.nav.js"></script>
<script src="{{asset('landing')}}/js/aos.js"></script>
<script src="{{asset('landing')}}/js/image-rotate.js"></script>
<script src="{{asset('landing')}}/js/main.js"></script>
<!-- End js file -->
</body>
</html>