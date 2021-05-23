<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@section('title') @show</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#00aeea">
    <meta name="msapplication-TileColor" content="#00aeea">
    <meta name="theme-color" content="#00aeea">
    <link rel="stylesheet" href="{{ asset("vendor/chartist/css/chartist.min.css") }}">
    <link rel="stylesheet" href="{{ asset("vendor/bootstrap-select/dist/css/bootstrap-select.min.css") }}">
    <link rel="stylesheet" href="{{ asset("vendor/owl-carousel/owl.carousel.css") }}">
    <link rel="stylesheet" href="{{ asset("css/style.css") }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&family=Roboto:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
    <!--page level css-->
    @yield('header_styles')
    <!--end of page level css-->
</head>
<body>

<!--*******************
    Preloader start
********************-->
<div id="preloader">
    <div class="sk-three-bounce">
        <div class="sk-child sk-bounce1"></div>
        <div class="sk-child sk-bounce2"></div>
        <div class="sk-child sk-bounce3"></div>
    </div>
</div>
<!--*******************
    Preloader end
********************-->

<!--**********************************
    Main wrapper start
***********************************-->
<div id="main-wrapper">

    <!--**********************************
        Nav header start
    ***********************************-->
    <div class="nav-header">
        <a href="{{ route("dashboard") }}" class="brand-logo">
            <img class="logo-abbr" src="{{ asset("images/icon-colors.svg") }}" alt="">
            <img class="logo-compact" src="{{ asset("images/logo-text-colors.svg") }}" alt="">
            <img class="brand-title" src="{{ asset("images/logo-text-colors.svg") }}" alt="">
        </a>

        <div class="nav-control">
            <div class="hamburger">
                <span class="line"></span><span class="line"></span><span class="line"></span>
            </div>
        </div>
    </div>
    <!--**********************************
        Nav header end
    ***********************************-->

    <!--**********************************
    Header start
***********************************-->
    <div class="header">
        <div class="header-content">
            <nav class="navbar navbar-expand">
                <div class="collapse navbar-collapse justify-content-between">
                    <div class="header-left">
                        <div class="dashboard_bar">
                            @section('title') @show
                        </div>
                    </div>
                    <ul class="navbar-nav header-right">
                        <li class="nav-item dropdown header-profile">
                            <a class="nav-link" href="javascript:void(0)" role="button" data-toggle="dropdown">
                                <div class="header-info">
                                    @if (!empty($device))
                                        <span class="text-black"><strong>
                                                @if (!empty($device->name))
                                                    {{$device->name}}
                                                @else
                                                    {{$device->device_id}}
                                                @endif
                                            </strong></span>
                                    @endif
                                        @if (!empty($session))
                                            <p class="fs-12 mb-0">
                                                @if (!empty($session->name))
                                                        {{$session->name}}
                                                    @else
                                                        {{$session->session_id}}
                                                    @endif
                                            </p>
                                        @endif
                                </div>
                            </a>
                        </li>
                        <li class="nav-item dropdown notification_dropdown">
                            <a class="nav-link bell bell-link" href="{{ route("sessions") }}">
                                <i class="flaticon-381-repeat-1"></i>
                            </a>
                        </li>
                        <li class="nav-item dropdown notification_dropdown">
                            <a class="nav-link bell bell-link" href="{{ route("logout") }}">
                                <i class="flaticon-381-exit"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>
    <!--**********************************
        Header end ti-comment-alt
    ***********************************-->

    @include('partials.menu')

    <!--**********************************
        Content body start
    ***********************************-->
    <div class="content-body">
        <!-- row -->
        <div class="container-fluid">
            @yield('content')
        </div>
    </div>
    <!--**********************************
        Content body end
    ***********************************-->
</div>
<!--**********************************
    Main wrapper end
***********************************-->

<!--**********************************
    Scripts
***********************************-->
<!-- Required vendors -->
<script src="{{ asset("vendor/global/global.min.js") }}"></script>
<script src="{{ asset("vendor/bootstrap-select/dist/js/bootstrap-select.min.js") }}"></script>
<script src="{{ asset("js/custom.min.js") }}"></script>
<script src="{{ asset("js/deznav-init.js") }}"></script>
<script>
    jQuery.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    jQuery(window).on('load',function(){
        setTimeout(function(){

        }, 1000);
    });
</script>
<!-- begin page level js -->
@yield('footer_scripts')
<!-- end page level js -->
</body>
</html>
