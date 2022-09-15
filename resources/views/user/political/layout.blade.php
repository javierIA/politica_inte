<!doctype html>
<html class="no-js" lang="zxx">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title> {{trans('user.app_title')}} </title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="manifest" href="site.webmanifest">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('images/logo/favicon.ico') }}">

    <!-- CSS here -->
    <link rel="stylesheet" href="{{ asset('css/political/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/political/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/political/slicknav.css') }}">
    <link rel="stylesheet" href="{{ asset('css/political/flaticon.css') }}">
    <link rel="stylesheet" href="{{ asset('css/political/gijgo.css') }}">
    <link rel="stylesheet" href="{{ asset('css/political/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/political/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('css/political/fontawesome-all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/political/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('css/political/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('css/political/nice-select.css') }}">
    <link rel="stylesheet" href="{{ asset('css/political/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/political/front_end_changes.css') }}">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">

    @yield('script_up')
</head>
<body>
<!--? Preloader Start -->
<div id="preloader-active">
    <div class="preloader d-flex align-items-center justify-content-center">
        <div class="preloader-inner position-relative">
            <div class="preloader-circle"></div>
            <div class="preloader-img pere-text">
                <img src="{{ asset('images/logo/logo.png') }}" alt="">
            </div>
        </div>
    </div>
</div>
<!-- Preloader Start -->

<!-- Login model -->
<div class="login-model">
    <div class="h-100 d-flex align-items-center justify-content-center login_margin">
        <div class="close-container">
            <div class="login-close-switch">+</div>
        </div>
        <form class="login-model-form" method="POST" action="{{ route('login') }}">
            <div class="container">
                <div align="center" class="login-img">
                    <img src="{{ asset('images/political/front_end/Logov2.png') }}">
                </div>
                {{ csrf_field() }}
                <input type="text" name="login" placeholder="{{trans('user.email_address')}}" style="margin-left: 20px"
                       class="form-control">
                <input type="password" name="password" placeholder="{{trans('user.password')}}">
                <div class="submit-login-btn">
                    <button type="submit" class="btn btn-default btn-block btn-login">
                        <i class="entypo-login"></i>
                        {{trans('user.login')}}
                    </button>
                </div>
                <br/>
                <a href="#" class="key_forget">{{trans('user.do_you_forget_password')}}</a>
            </div>
        </form>
    </div>
</div>
<!-- Login model end -->
<header>
    <!-- Header Start -->
    <div class="header-area">
        <div class="main-header ">
            <div class="header-top d-none d-lg-block">
                <!-- Left Social -->
                <div class="header-left-social">
                    <ul class="header-social">
                        <li><a href="{{ !is_null($setting_info)? $setting_info->twitter:'#' }}"><i class="fab fa-twitter"></i></a></li>
                        <li><a href="{{ !is_null($setting_info)? $setting_info->facebook:'#' }}"><i class="fab fa-facebook-f"></i></a></li>
                        <li><a href="{{ !is_null($setting_info)? $setting_info->pinterest:'#' }}"><i class="fab fa-pinterest"></i></a></li>
                        <li><a href="{{ !is_null($setting_info)? $setting_info->instagram:'#' }}"><i class="fab fa-instagram"></i></a></li>
                    </ul>
                </div>
                <div class="container ">
                    <div class="col-xl-12" style="margin-left: 70px">
                        <div class="row d-flex justify-content-between align-items-center">
                            <div class="header-info-left">
                                <ul>
                                    <li>{{ !is_null($setting_info)? $setting_info->email:'' }}</li>
                                    <li>{{ !is_null($setting_info)? $setting_info->phone:'' }}</li>
                                </ul>
                            </div>
                            <div class="header-info-right">
                                <ul class="site-nav-menu-up">
                                    <li><a href="#" class="login"><i class="ti-user"></i>{{trans('user.login')}}</a></li>
                                    <li class="dropdown language-selector">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-close-others="true">
                                            <img src="{{ asset('flat/'.config('locale.languages')[App::getLocale()][3]) }}" class="img-circle"/>
                                        </a>
                                        <ul class="dropdown-menu pull-right">
                                            @foreach(config('locale.languages') as $lang)
                                                @if($lang[0] != App::getLocale())
                                                    <li>
                                                @else
                                                    <li class="active">
                                                        @endif
                                                        <a href="{{ route('lang.swap', $lang[0]) }}">
                                                            <img src="{{ asset('flat/'.$lang[3]) }}"  class="img-circle"/>
                                                            <span>{{ $lang[4] }}</span>
                                                        </a>
                                                    </li>
                                                    @endforeach
                                        </ul>

                                    </li>
                                    {{--<li><a href="#"><i class="ti-lock"></i>{{trans('user.register')}}</a></li>--}}

                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="header-bottom header-sticky">
                <!-- Logo -->
                <div class="logo d-none d-lg-block">
                    <a href="{{ route('dashboard') }}"><img src="{{ asset('images/logo/logo.png') }}" style="width: 150px" alt=""></a>
                </div>
                <div class="container">
                    <div class="menu-wrapper">
                        <!-- Logo -->
                        <div class="logo logo2 d-block d-lg-none">
                            <a href="{{ route('dashboard') }}"><img src="{{ asset('images/logo/logo.png') }}"  style="width: 80px" alt=""></a>
                        </div>
                        <!-- Main-menu -->
                        <div class="main-menu d-none d-lg-block">
                            <nav>
                                <ul id="navigation">
                                    <li><a href="#home_container">{{trans('user.home')}}</a></li>
                                    <li><a href="#organization_values">{{trans('user.our_values')}}</a></li>
                                    <li><a href="#statistics">{{trans('user.statistics')}}</a></li>
                                    <li><a href="#about_us">{{trans('user.about_us')}}</a></li>
                                    <li><a href="#">{{trans('user.user_manual')}}</a></li>
                                </ul>
                            </nav>
                        </div>
                        <!-- Header-btn -->
                        <div class="header-search d-none d-lg-block">
                            <form action="#" class="form-box f-right ">
                                <input type="text" name="Search" placeholder="{{trans('user.search')}}">
                                <div class="search-icon">
                                    <i class="fas fa-search special-tag"></i>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- Mobile Menu -->
                    <div class="col-12">
                        <div class="mobile_menu d-block d-lg-none"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Header End -->
</header>
<main>
    @yield('content')
</main>

@include('user.political.partials.footer')

<!-- JS here -->

<script src="{{ asset('js/political/vendor/modernizr-3.5.0.min.js') }}"></script>
<!-- Jquery, Popper, Bootstrap -->
<script src="{{ asset('js/political/vendor/jquery-1.12.4.min.js') }}"></script>
<script src="{{ asset('js/political/popper.min.js') }}"></script>
<script src="{{ asset('js/political/bootstrap.min.js') }}"></script>

<!-- Jquery Mobile Menu -->
<script src="{{ asset('js/political/jquery.slicknav.min.js') }}"></script>

<!-- Jquery Slick , Owl-Carousel Plugins -->
<script src="{{ asset('js/political/owl.carousel.min.js') }}"></script>
<script src="{{ asset('js/political/slick.min.js') }}"></script>
<!-- One Page, Animated-HeadLin -->
<script src="{{ asset('js/political/wow.min.js') }}"></script>
<script src="{{ asset('js/political/animated.headline.js') }}"></script>
<script src="{{ asset('js/political/jquery.magnific-popup.js') }}"></script>

<!-- Date Picker -->
<script src="{{ asset('js/political/gijgo.min.js') }}"></script>
<!-- Nice-select, sticky -->
<script src="{{ asset('js/political/jquery.nice-select.min.js') }}"></script>
<script src="{{ asset('js/political/jquery.sticky.js') }}"></script>

<!-- counter , waypoint -->
<script src="{{ asset('js/political/jquery.counterup.min.js') }}"></script>
<script src="{{ asset('js/political/waypoints.min.js') }}"></script>

<!-- contact js -->
<script src="{{ asset('js/political/waypoints.min.js') }}"></script>
<script src="{{ asset('js/political/jquery.form.js') }}"></script>
<script src="{{ asset('js/political/jquery.validate.min.js') }}"></script>
<script src="{{ asset('js/political/mail-script.js') }}"></script>
<script src="{{ asset('js/political/jquery.ajaxchimp.min.js') }}"></script>

<!-- Jquery Plugins, main Jquery -->
<script src="{{ asset('js/political/plugins.js') }}"></script>
<script src="{{ asset('js/political/main.js') }}"></script>
<script src="{{ asset('js/login.js') }}"></script>

<!--notify-->
<script src="{{ asset('js/notify.min.js') }}"></script>

</body>
</html>

@yield('script_down')