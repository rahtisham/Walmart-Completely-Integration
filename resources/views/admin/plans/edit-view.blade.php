<x-admin-layout>

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
            <div class="col-xl-6 py-3 card">

                <div class="modal-body">
                    <h4 class="info-h1 py-3">UPDATE PLAN FORM</h4>
                    <form action="{{ url('dashboard/admin/plan-edit' , ['id' => $plans['id']] ) }}" method="post">
                        @csrf
                        <div class="form-group">
                            <input type="text" name="plan" class="form-control" value="{{ $plans['planName'] }}" placeholder="Plan Name">
                        </div>
                        <div class="form-group" >
                            <select name="marketplace" class="form-control">
                            <option value="{{ $plans['marketPlace'] }}">{{ $plans['marketPlace'] }}</option>
                            <option value="amazon_option1">Amazon Plan 1</option>
                            <option value="amazon_option2">Amazon Plan 2</option>
                            <option value="walmart_option1">Walmart Plan 1</option>
                            <option value="walmart_option2">Walmart Plan 2</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="number" name="amount" value="{{ $plans['amount'] }}" class="form-control" placeholder="Amount">
                        </div>
                        <button type="submit" class="btn btn-primary light">Submit</button>
                    </form> <!--end of form-->
                </div>
            </div>
            <div class="col-xl-6"></div>
        </div><!-- end of r0w-->
    </div><!--end of container-->
</x-app-layout>

