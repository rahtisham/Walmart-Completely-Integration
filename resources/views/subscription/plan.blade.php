<x-app-layout>

    <div class="container mobileVersion">
        <div class="page-titles" style="background-color: rgba(243, 244, 246, var(--tw-bg-opacity));">
            <ol class="breadcrumb" style="background-color: rgba(243, 244, 246, var(--tw-bg-opacity));">
                <li class="breadcrumb-item"><a href="javascript:void(0)"><h4>Dashboard / Plans</h4></a></li>
            </ol>
        </div>

        @if(\Session::has('success'))
            <div class="alert alert-primary alert-dismissible fade show">
                <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><circle cx="12" cy="12" r="10"></circle><path d="M8 14s1.5 2 4 2 4-2 4-2"></path><line x1="9" y1="9" x2="9.01" y2="9"></line><line x1="15" y1="9" x2="15.01" y2="9"></line></svg>
                <strong>Welcome!</strong> {{ \Session::get('success') }}
                <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span>
                </button>
            </div>
        @endif
        <div class="row">
            @foreach ($plans as $plan)
                <div class="col-xl-4">
                    <a href="{{ url('subscription/create-view' , $plan['stripe_plan'] ) }}">
                        <div class="card HoverCard bg-image-walmart">
                            <div class="card-header">

                                @if ($plan['marketPlace'] == "walmart_option1")
                                <li><button type="button" class="btn btn-success btn-xs mg5">Walmart</button></li>
                                @endif
                                @if ($plan['marketPlace'] == "walmart_option2")
                                <li><button type="button" class="btn btn-success btn-xs mg5">Walmart</button></li>
                                @endif
                                @if ($plan['marketPlace'] == "amazon_option1")
                                <li><button type="button" class="btn btn-primary btn-xs mg5">Amazon</button></li>
                                @endif
                                @if ($plan['marketPlace'] == "amazon_option2")
                                <li><button type="button" class="btn btn-success btn-xs mg5">Amazon</button></li>
                                @endif
                            </div>
                            <div class="pdleft">

                                @if ($plan['marketPlace'] == "walmart_option1")
                                    <img class="mg5" src="{{ asset('AppealLab/images/walmart-logo.png') }}" width="60px" alt="">
                                    <h3 class="mg5"><b>${{ $plan['amount'] }} / Month</b></h3>
                                    <li><i class="fa fa-arrow-right" aria-hidden="true"></i> {{ $plan['planName'] }}</li>
                                @endif
                                @if ($plan['marketPlace'] == "walmart_option2")
                                    <img class="mg5" src="{{ asset('AppealLab/images/walmart-logo.png') }}" width="60px" alt="">
                                    <h3 class="mg5"><b>${{ $plan['amount'] }} / Month</b></h3>
                                    <li><i class="fa fa-arrow-right" aria-hidden="true"></i> {{ $plan['planName'] }}</li>
                                @endif
                                @if ($plan['marketPlace'] == "amazon_option1")
                                    <img class="mg5" src="{{ asset('AppealLab/images/amazon-logo.png') }}" width="60px" alt="">
                                    <h3 class="mg5"><b>${{ $plan['amount'] }} / Month</b></h3>
                                    <li><i class="fa fa-arrow-right" aria-hidden="true"></i> {{ $plan['planName'] }}</li>
                                @endif
                                @if ($plan['marketPlace'] == "amazon_option2")
                                    <img class="mg5" src="{{ asset('AppealLab/images/amazon-logo.png') }}" width="60px" alt="">
                                    <h3 class="mg5"><b>${{ $plan['amount'] }} / Month</b></h3>
                                    <li><i class="fa fa-arrow-right" aria-hidden="true"></i> {{ $plan['planName'] }}</li>
                                @endif
                                <br>
                                <button type="button" style="background: #3EC2C2;" class="btn btn-info btn-xs mg5">GET INSURED NOW! (MONTHLY)  <i class="fa fa-arrow-right" aria-hidden="true"></i></button>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
            <div class="col-xl-4"></div>
            <div class="col-xl-4"></div>

        </div>
    </div>
</x-app-layout>

<style>
    .mobileVersion{
        margin-top: 120px !important;
    }

    .content-body {
        padding-top: 1.5rem !important;
    }
    .bg-image-walmart
    {
        background-image: url('AppealLab/images/walmart-logo-ol.png');
        background-position: left;
        width: 100%;
        background-size: cover;
        background-repeat: no-repeat;
    }
    .bg-image-amazone
    {
        background-image: url('AppealLab/images/amazon-logo-ol.png');
        background-position: left;
        width: 100%;
        background-size: cover;
        background-repeat: no-repeat;
    }
    .bg-image-apps
    {
        /* background-image: url('AppealLab/images/amazon-logo-ol.png'); */
        background: #37474F;
        background-position: left;
        width: 100%;
        background-size: cover;
        background-repeat: no-repeat;
    }
    .card
    {
        padding: 0px 0px 50px 0px;
    }
    .card-header
    {
        justify-content: flex-end;
    }
    .pdleft
    {
        margin-left: 30px;
    }
    .mg5
    {
        margin-top: 10px;
    }
    .mg10
    {
        margin-top: 20px;
    }
    .card-header
    {
        border-color: transparent !important;
    }


</style>
