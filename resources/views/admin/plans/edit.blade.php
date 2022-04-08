<x-admin-layout>

    <div class="container mobileVersion">

        <div class="page-titles" style="background-color: rgba(243, 244, 246, var(--tw-bg-opacity));">
            <ol class="breadcrumb" style="background-color: rgba(243, 244, 246, var(--tw-bg-opacity));">
                <li class="breadcrumb-item"><a href="javascript:void(0)"><h4>Dashboard / Plans</h4></a></li>
            </ol>
            <div class="text-right">
                <input type="button" data-toggle="modal" data-target="#modalGrid" class="btn btn-info btn-style" value="CREATE A NEW PLAN">
            </div>
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
            <div class="col-xl-6">
                <div class="modal fade" id="modalGrid">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">CREATE PLAN</h5>
                                <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ url('dashboard/admin/create-plan') }}" method="post">
                                    @csrf
                                    <div class="form-group">
                                        <input type="text" name="plan" class="form-control" placeholder="Plan Name">
                                    </div>
                                    <div class="form-group" >
                                        <select name="marketplace" class="form-control">
                                        <option value="">Select a Plan</option>
                                        <option value="amazon_option1">Amazon Plan 1</option>
                                        <option value="amazon_option2">Amazon Plan 2</option>
                                        <option value="walmart_option1">Walmart Plan 1</option>
                                        <option value="walmart_option2">Walmart Plan 2</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" name="amount" class="form-control" placeholder="Amount">
                                    </div>
                                    <button type="submit" class="btn btn-primary light">Submit</button>
                                </form> <!--end of form-->
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger light btn-style" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6"></div>
        </div><!-- end of r0w-->
    </div><!--end of container-->
</x-app-layout>

