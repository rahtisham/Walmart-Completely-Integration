<x-app-layout>
    <div class="container">
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8 mr-5">
                <header class="site-header" id="header">
                    <h1 class="site-header__title" data-lead-id="site-header-title"><i class="fa fa-check main-content__checkmark" id="checkmark"></i> SUCCESS !</h1>
                </header>

                <div class="main-content">
                    <img class="mg5" src="{{ asset('AppealLab/images/walmart-logo.png') }}" width="60px" alt="">
                    <p class="main-content__body" data-lead-id="main-content-body">Your Walmart Store has Been Submit Successfully Registered. Enjoy peace of mind knowing that your Walmart store is protected in case of an account suspension.</p>
                    <br>
                    <a href="{{ route('dashboard.marketplace') }}" class="btn btn-lg btn-primary btnStyle">BACK TO HOMEPAGE</a>
                </div>
            </div>
            <div class="col-md-2"></div>
        </div>
    </div>
</x-app-layout>

<style>
    .btnStyle
    {
        background: #03c6ad; border: 1px solid #03c6ad;
    }
    .content-body .container {
        margin-top: 0px !important;
    }
    .col-md-8.mr-5 {
        margin-top: 70px;
    }
    h1.site-header__title {
        font-size: 70px;
        font-weight: 700;
        text-align: center;
    }
    i#checkmark {
        font-size: 80px;
        color: #03c6ad;
    }
    .main-content {
        text-align: center;
    }
</style>
