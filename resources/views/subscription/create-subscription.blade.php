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
    <link href="{{ asset('AppealLab/vendor/jquery-smartwizard/dist/css/smart_wizard.min.css') }}" rel="stylesheet">
    <link href="{{ asset('AppealLab/vendor/bootstrap-select/dist/css/bootstrap-select.min.css') }}" rel="stylesheet">

</head>
<style>
    .tab-content
    {
        height: auto !important;
    }
    .content-body .container {
        margin-top: 0px !important;
    }
    .sw-theme-default {
        border: 1px solid white;
    }
    .content-body {
    margin-left: 0rem !important;
    z-index: 0;
    transition: all .2s ease;
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
        <div class="content-body" style="margin-top: 0px !important;">
            <div class="min-h-screen">
                <!-- <div class="content-body"> -->

                <div class="container">
                    <div class="page-titles">
                    </div>
                    <div class="card-header justify-content-center">
                        <div class="card-title">
                            <div class="text-center">
                                <img class="mg5" src="{{ asset('AppealLab/images/walmart-logo.png') }}" width="60px" alt="">
                                <h3>Connect to your Selected Marketplace</h3>
                                <p style="font-size: 16px; color: gray;">we help you to connect securely with multiple plateform</p>
                            </div>
                        </div>
                    </div>
                    <!-- row -->
                    <div class="row">

                        <div class="col-xl-12 col-xxl-12">
                            <div class="card">

                                <div class="card-body">

                                    <div class="form-wizard order-create">
                                        <div class="tab-content" style="margin-top: 30px;">
                                                <div class="row">
                                                    <div class="col-lg-12 mb-3">
                                                        <div class="form-group">
                                                            <p><span style="color: #03c6ad;">Step 1 </span>: Go here and login using the same email/password to access your Walmart account : <a style="color: #03c6ad;" href="https://developer.walmart.com/account/generateKey">https://developer.walmart.com/account/generateKey</a> </p>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12 mb-3">
                                                        <div class="form-group">
                                                            <p><span style="color: #03c6ad;">Step 2 </span>: Enter in your store front name </p>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12 mb-3">
                                                        <div class="form-group">
                                                            <p><span style="color: #03c6ad;">Step 3 </span>: Go to 'My API Key' and copy the Client ID. Paste it on Appeal Lab</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12 mb-3">
                                                        <div class="form-group">
                                                            <p><span style="color: #03c6ad;">Step 4 </span>: Go to 'My API Key' and click on the 'eye' to show your secret phrase, then click on copy. Paste it on Appeal Lab. </p>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12 mb-3">
                                                        <div class="form-group">
                                                            <p><span style="color: #03c6ad;">Step 5 </span>:  Click connect - You are now done and your account is connected and will now start receiving alerts.</p>
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
<script src="{{ asset('AppealLab/vendor/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('AppealLab/vendor/jquery-smartwizard/dist/js/jquery.smartWizard.js') }}"></script>

<script>
    $(document).ready(function(){
        // SmartWizard initialize
        $('#smartwizard').smartWizard();
        $('#result').html(response.success)
    });
</script>
