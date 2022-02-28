<x-app-layout>

    <style>
        .content-body .container {
            margin-top: 0px !important;
        }
        .container {
            padding-top: 5.5rem !important;
        }
    </style>
    <div class="container">
        <div class="page-titles" style="background-color: rgba(243, 244, 246, var(--tw-bg-opacity));">
            <!-- <ol class="breadcrumb" style="background-color: rgba(243, 244, 246, var(--tw-bg-opacity));">
                <li class="breadcrumb-item"><a href="javascript:void(0)"><h4>Marketplace</h4></a></li>
            </ol>
            <p>Integrate your marketplace with AppealLab</p> -->
        </div>
        <div class="text-center">
            <h3>Connect to your Selected Marketplace</h3>
            <p>we help you to connect securely with multiple plateform</p>
        </div>
        <!-- Connect with Integration Headinge -->
        <div class="row justify-content-center p-2">
            <div class="col-xl-2"></div>
            <div class="col-xl-6">
                <a href="{{ url('dashboard/marketplace/walmart') }}">
                    <div class="card HoverCard bg-image-walmart">
                        <div class="card-header">
                            <button type="button" class="btn btn-primary shadow btn-xs sharp mr-1"><i class="fa fa-check" aria-hidden="true"></i></button>
                        </div>
                        <div class="pdleft">
                            <img class="mg5" src="{{ asset('AppealLab/images/walmart-logo.png') }}" width="60px" alt="">
                            <h4 class="mg5">Connect Walmart</h4>
                            <p class="mg5">Let's start setting up your system <i class="fa fa-arrow-right" aria-hidden="true"></i></p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-2">
{{--                <a href="{{ url('dashboard/marketplace/amazone') }}">--}}
{{--                    <div class="card HoverCard bg-image-amazone">--}}
{{--                        <div class="card-header">--}}
{{--                            <button type="button" class="btn btn-primary shadow btn-xs sharp mr-1"><i class="fa fa-check" aria-hidden="true"></i></button>--}}
{{--                        </div>--}}
{{--                        <div class="pdleft">--}}
{{--                            <img class="mg5" src="{{ asset('AppealLab/images/amazon-logo.png') }}" width="60px" alt="">--}}
{{--                            <h4 class="mg5">Connect Amazone</h4>--}}
{{--                            <p class="mg5">Let's start setting up your system <i class="fa fa-arrow-right" aria-hidden="true"></i></p>--}}
{{--                        </div>--}}

{{--                    </div>--}}
{{--                </a>--}}
            </div>

        </div>
    </div>
</x-app-layout>

<style>
    .container
    {
        padding-top: 7.5rem;
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

    .HoverCard:hover{

        border: 2px solid black;

    }

    .sharp
    {
        background-color: #00BFA5; border: 1px solid #00BFA5;
    }

</style>
