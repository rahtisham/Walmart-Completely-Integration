<x-app-layout>
    <div class="container">
        <div class="page-titles">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Marketplace</a></li>
                <li class="breadcrumb-item"><a href="javascript:void(0)">Walmart</a></li>
            </ol>
        </div>
        <!-- row -->

        <div class="row">
            <div class="col-xl-4 col-lg-6">
{{--                <div class="card">--}}
{{--                    <div class="card-header pb-0 border-0">--}}
{{--								<span class="p-3 mr-3 rounded bg-secondary text-white">--}}
{{--                                 43--}}
{{--								</span>--}}
{{--                        <div class="mr-auto pr-3">--}}
{{--                            <h4 class="text-black fs-20">ACTIVE</h4>--}}
{{--                            <p class="fs-13 mb-0 text-black">Total Products Active</p>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="card-body pb-0">--}}
{{--                        <div id="chartBar"></div>--}}
{{--                    </div>--}}
{{--                </div>--}}
            </div>
            <div class="col-xl-4 col-lg-6">
{{--                <div class="card">--}}
{{--                    <div class="card-header pb-0 border-0">--}}
{{--								<span class="p-3 mr-3 rounded bg-danger text-white">--}}
{{--                                   43--}}
{{--								</span>--}}
{{--                        <div class="mr-auto pr-3">--}}
{{--                            <h4 class="text-black fs-20">PUBLISHED</h4>--}}
{{--                            <p class="fs-13 mb-0 text-black">Total Products Published</p>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
            </div>
            <div class="col-xl-4">
{{--                <div class="card">--}}
{{--                    <div class="card-header pb-0 border-0">--}}
{{--								<span class="p-3 mr-3 rounded bg-warning text-white">--}}
{{--                                    43--}}
{{--								</span>--}}
{{--                        <div class="mr-auto pr-3">--}}
{{--                            <h4 class="text-black fs-20">UNPUBLISHED</h4>--}}
{{--                            <p class="fs-13 mb-0 text-black">Total Products Unpublished</p>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="card-body pb-0">--}}
{{--                        <div id="chartBar3"></div>--}}
{{--                    </div>--}}
{{--                </div>--}}
            </div>
            <div class="col-12">
                <div class="row">
                    <div class="col-xl-1 col-lg-1"></div>
                    <div class="col-xl-10 col-lg-10">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Walmart Marketplace</h4>
                            </div>
                            <div class="card-body">
                                <div class="basic-form">
                                    <form class="form-valide-with-icon" action="#" method="post">
                                        <div class="form-group">
                                            <label class="text-label">Name</label>
                                            <div class="input-group">
                                                {{-- <div class="input-group-prepend">
                                                    <span class="input-group-text"> <i class="fa fa-user"></i> </span>
                                                </div> --}}
                                                <input type="text" disabled class="form-control" value="{{ $marketPlace['name'] }}" id="name" name="val-username" placeholder="Enter a username..">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="text-label">Client ID</label>
                                            <div class="input-group transparent-append">
                                                {{-- <div class="input-group-prepend">
                                                    <span class="input-group-text"> <i class="fa fa-lock"></i> </span>
                                                </div> --}}
                                                <input type="text" disabled class="form-control" id="clientID" value="{{ $marketPlace['client_id'] }}" name="val-password" placeholder="Choose a safe one..">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="text-label">Client Secret</label>
                                            <div class="input-group transparent-append">
                                                {{-- <div class="input-group-prepend">
                                                    <span class="input-group-text"> <i class="fa fa-lock"></i> </span>
                                                </div> --}}
                                                <input type="text" disabled class="form-control" id="clientSecret" value="{{ $marketPlace['client_secret'] }}" name="val-password" placeholder="Choose a safe one..">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-1 col-lg-1"></div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<!-- Chart Morris plugin files -->
<script src="{{ asset('AppealLab/vendor/morris/raphael-min.js') }}"></script>
<script src="{{ asset('AppealLab/vendor/morris/morris.min.js') }}"></script>
<script src="{{ asset('AppealLab/js/plugins-init/morris-init.js') }}"></script>

<script>
    $(document).read(function(){
        document.getElementById("clientSecret").disabled = true;
    })
</script>
