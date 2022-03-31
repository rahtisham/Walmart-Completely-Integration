<x-app-layout>

    <div class="container mobileVersion">
        <div class="page-titles" style="background-color: rgba(243, 244, 246, var(--tw-bg-opacity));">
            <ol class="breadcrumb" style="background-color: rgba(243, 244, 246, var(--tw-bg-opacity));">
                <li class="breadcrumb-item"><a href="javascript:void(0)"><h4>Marketplace</h4></a></li>
            </ol>
            <p>Integrate your marketplace with AppealLab</p>
        </div>

        {{-- <div class="page-titles" style="background-color: rgba(243, 244, 246, var(--tw-bg-opacity));">
            <form method="POST" action="{{ url('/dashboard/marketplace/expiry') }}">
                @csrf
                <input type="submit"/>
            </form>
        </div> --}}
        @if(Session::has('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><circle cx="12" cy="12" r="10"></circle><path d="M8 14s1.5 2 4 2 4-2 4-2"></path><line x1="9" y1="9" x2="9.01" y2="9"></line><line x1="15" y1="9" x2="15.01" y2="9"></line></svg>
            <strong>Error!</strong>  {{ Session::get('error') }}
            <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span>
            </button>
        </div>
        @endif
        @if(\Session::has('success'))
            <div class="alert alert-primary alert-dismissible fade show">
                <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><circle cx="12" cy="12" r="10"></circle><path d="M8 14s1.5 2 4 2 4-2 4-2"></path><line x1="9" y1="9" x2="9.01" y2="9"></line><line x1="15" y1="9" x2="15.01" y2="9"></line></svg>
                <strong>Welcome!</strong> {{ \Session::get('success') }}
                <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span>
                </button>
            </div>
        @endif
        <div class="row">
            @foreach($marketPlace as $walmart)
                <div class="col-xl-4">
                    <a href="{{ url('user/marketplace/edit_view' , $walmart['user_id']) }}">
                        <div class="card HoverCard bg-image-walmart">
                            <div class="card-header">
                                <button type="button" class="btn btn-success btn-xxs">Active</button>
                            </div>
                            <div class="pdleft">
                                <img class="mg5" src="{{ asset('AppealLab/images/walmart-logo.png') }}" width="60px" alt="">
                                <h4 class="mg5">{{ $walmart['platform'] }}</h4>
                                <button type="button" style="background: #3EC2C2;" class="btn btn-info btn-xs mg5">{{ $walmart['name'] }}</button>
                                <p class="mg5">Your configuration goes here <i class="fa fa-arrow-right" aria-hidden="true"></i></p>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
            <div class="col-xl-4">
                <a href="{{ url('user/marketplace/plate-form') }}">
                    <div class="card HoverCard bg-image-apps">
                        <div class="card-header">
                            <button type="button" class="btn btn-primary btn-xxs">New</button>
                        </div>
                        <div class="pdleft">
                            <img class="mg5" src="{{ asset('AppealLab/images/apps.png') }}" width="60px" alt="">
                            <h4 class="mg10 text-white">Add New <br> Integration</h4>
                            <p class="mg10 text-white">Your configuration goes here <i class="fa fa-arrow-right" aria-hidden="true"></i></p>
                        </div>

                    </div>
                </a>
{{--                <a href="#">--}}
{{--                    <div class="card HoverCard bg-image-amazone">--}}
{{--                        <div class="card-header">--}}
{{--                            <button type="button" class="btn btn-danger btn-xxs">Active</button>--}}
{{--                        </div>--}}
{{--                        <div class="pdleft">--}}
{{--                            <img class="mg5" src="{{ asset('AppealLab/images/amazon-logo.png') }}" width="60px" alt="">--}}
{{--                            <h4 class="mg5">Amazone</h4>--}}
{{--                            <button type="button" class="btn btn-primary btn-xs mg5">Tay Store</button>--}}
{{--                            <p class="mg5">Your configuration goes here <i class="fa fa-arrow-right" aria-hidden="true"></i></p>--}}
{{--                        </div>--}}

{{--                    </div>--}}
{{--                </a>--}}
            </div>
            <div class="col-xl-4">

            </div>

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
