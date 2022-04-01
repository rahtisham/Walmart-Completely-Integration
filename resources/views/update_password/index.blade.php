<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Appeal Lab') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">

{{--        @livewireStyles--}}

<!-- Scripts -->
    <script src="{{ mix('js/app.js') }}" defer></script>

    <!-- AppealLab script and css classes -->
    <link rel="icon" type="{{ asset('image/png') }}" sizes="16x16" href="{{ asset('AppealLab/images/logo1.png') }}">
    <link rel="stylesheet" href="{{ asset('AppealLab/js/ajax.js') }}">
    <link rel="stylesheet" href="{{ asset('AppealLab/vendor/chartist/css/chartist.min.css') }}">
    <link href="{{ asset('AppealLab/vendor/bootstrap-select/dist/css/bootstrap-select.min.css') }}" rel="stylesheet">
    <link href="{{ asset('AppealLab/vendor/owl-carousel/owl.carousel.css') }}" rel="stylesheet">
    <link href="{{ asset('AppealLab/css/style.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&family=Roboto:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">


</head>
<style>
    img, svg, video, canvas, audio, iframe, embed, object {
        display: initial !important;
        vertical-align: middle;
    }
    .nav-header .brand-title {
        margin-left: 10px;
        max-width: 190px !important;
        margin-top: 0px;
    }
    .btn-primary {
        color: #fff;
        background-color: #03c6ad !important;
        border-color: #03c6ad;
        border: 1px solid #03c6ad;
    }

    .form-control {
    border-radius: 0.35rem !important;
    }

    .authincation-content {
    background: #03c6ad !important;
    }
    .btn {
    border-radius: 0.35rem !important;
    }
    .p-6
    {
        padding: 30px;
    }

</style>
<body class="font-sans antialiased">
<x-jet-banner />
<!--**********************************
    Main wrapper start
***********************************-->
<div id="main-wrapper">

    <!--**********************************
  `     Nav header start
    ***********************************-->
    <div class="nav-header">
        <a href="#" class="brand-logo">
            <img class="brand-title" style="width: 100%;" src="{{ asset('AppealLab/images/logoa.png') }}" alt="">
        </a>
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
                        </div>
                    </div>
                    <ul class="navbar-nav header-right">
                        <li class="nav-item dropdown notification_dropdown">
                            <div class="dropdown-menu rounded dropdown-menu-right">
                                <a class="all-notification" href="javascript:void(0)">See all notifications <i class="ti-arrow-right"></i></a>
                            </div>
                        </li>

                        <li class="nav-item dropdown header-profile">
                            <a class="nav-link" href="#" role="button" data-toggle="dropdown">
                                <img src="{{ asset('AppealLab/images/profile/user.png') }}" width="20" alt=""/>
                                <div class="header-info">
                                    <span class="text-black"><strong>{{ auth()->user()->name }}</strong></span>
                                    <p class="fs-12 mb-0">{{ auth()->user()->last_name }}</p>
                                </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">

                                {{-- <!-- Authentication --> --}}
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf

                                    <x-jet-dropdown-link href="{{ route('logout') }}"
                                                         onclick="event.preventDefault();
                                            this.closest('form').submit();">
                                        <svg id="icon-logout" xmlns="http://www.w3.org/2000/svg" class="text-danger" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                                        <span class="ml-2">Logout </span>
                                    </x-jet-dropdown-link>
                                </form>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>
    <!--**********************************
        Header end ti-comment-alt
    ***********************************-->

    <!-- Page Heading -->
    @if (isset($header))
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
    @endif

<!-- Page Content -->
    <main>
        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-bodys" style="margin-top: 185px !important;">
            <div class="min-h-screen">
                <!-- <div class="content-body"> -->
                <div class="authincation h-100">
                    <div class="container h-100">
                        <div class="row justify-content-center h-100 align-items-center">
                            <div class="col-md-3"></div>
                            <div class="col-md-6">

                                <div class="authincation-content">
                                    <div class="row">
                                        <div class="col-xl-12">
                                            <div class="auth-form">
                                                {{-- @foreach ($errors->all() as $error)
                                                    <p class="text-danger">{{ $error }}</p>
                                                @endforeach --}}
                                                <form action="{{ url('password/password-updated') }}" method="post">
                                                    @csrf
                                                    <div class="form-group">
                                                        <label class="mb-1 text-white"><strong>Current Password</strong></label>
                                                        <input type="password" name="current_password" class="form-control" placeholder="Current Password">
                                                        @error('current_password')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="mb-1 text-white"><strong>New Password</strong></label>
                                                        <input type="password" name="new_password" class="form-control" placeholder="New Password">
                                                        @error('new_password')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="mb-1 text-white"><strong>Confirm Password</strong></label>
                                                        <input type="password" name="new_confirm_password" placeholder="Confirm Password" class="form-control" >
                                                        @error('new_confirm_password')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                    <div class="text-center mt-4">
                                                        <button type="submit" class="btn bg-white text-primary btn-block">RESET PASSWORD</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3"></div>
                        </div>
                    </div>
                </div>
                <!-- </div> -->
            </div>
        </div>
        <!--**********************************
            Content body end
        ***********************************-->
    </main>
</div>
<!--**********************************
Main wrapper end
***********************************-->
@stack('modals')

@livewireScripts
</body>
</html>

<!--**********************************
Scripts
***********************************-->
<!-- Required vendors -->
<script src="{{ asset('AppealLab/vendor/global/global.min.js') }}"></script>
<script src="{{ asset('AppealLab/vendor/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('AppealLab/vendor/chart.js/Chart.bundle.min.js') }}"></script>
<script src="{{ asset('AppealLab/js/custom.min.js') }}"></script>
<script src="{{ asset('AppealLab/js/deznav-init.js') }}"></script>
<script src="{{ asset('AppealLab/vendor/owl-carousel/owl.carousel.js') }}"></script>
<script src="{{ asset('AppealLab/vendor/peity/jquery.peity.min.js') }}"></script>
<script src="{{ asset('AppealLab/vendor/apexchart/apexchart.js') }}"></script>
<script src="{{ asset('AppealLab/js/dashboard/dashboard-1.js') }}"></script>
