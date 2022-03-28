<!-- Datatable -->
<link href="{{ asset('AppealLab/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<!-- Custom Stylesheet -->
<x-admin-layout>

    <div class="container-fluid">
        <div class="page-titles">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">User</a></li>
            </ol>
        </div>
        <!-- row -->


        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">
                            <a href="{{ url('dashboard/admin/user-registration') }}" class="btn rounded btn-primary">Create New User</a>
                        </h4><br>

                        @if(Session::has('success'))
                            <div class="alert alert-success alert-dismissible fade show">
                                <strong></strong>  {{ Session::get('success') }}
                                <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span>
                                </button>
                            </div>
                        @endif

                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example" class="display min-w850">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name on Card</th>
                                    <th>Subscription</th>
                                    <th>Amount</th>
                                    <th>Marketplace</th>
                                    <th>Status</th>
                                    {{-- <th>Action</th> --}}
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($subscriptions as $subscription)
                                    <tr>
                                        <td>{{ $subscription['id'] }}</td>
                                        <td>{{ $subscription['name_on_card'] }}</td>
                                        <td>{{ $subscription['subscriptionName'] }}</td>
                                        <td>${{ $subscription['amount'] }}</td>
                                        <td>
                                            @if ($subscription['message_code'] == "amazon_option1")
                                                <span class="badge light badge-primary">
                                                    <i class="fa fa-circle text-primary mr-1"></i>
                                                    Amazon
                                                </span>
                                                @endif
                                            @if ($subscription['message_code'] == "amazon_option2")
                                                <span class="badge light badge-primary">
                                                    <i class="fa fa-circle text-primary mr-1"></i>
                                                    Amazon
                                                </span>
                                            @endif
                                            @if ($subscription['message_code'] == "walmart_option1")
                                                <span class="badge light badge-success">
                                                    <i class="fa fa-circle text-success mr-1"></i>
                                                    Walmart
                                                </span>
                                            @endif
                                            @if ($subscription['message_code'] == "walmart_option2")
                                                <span class="badge light badge-success">
                                                    <i class="fa fa-circle text-success mr-1"></i>
                                                    Walmart
                                                </span>
                                            @endif
                                        </td>
                                        <td>{{ $subscription['status'] }}</td>
                                        {{-- <td>
                                            <div class="d-flex">
                                                <a href="#" class="btn btn-primary shadow btn-xs sharp mr-1"><i class="fa fa-pencil"></i></a>
                                                <a href="#" class="btn btn-danger shadow btn-xs sharp"><i class="fa fa-trash"></i></a>
                                            </div>
                                        </td> --}}
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-admin-layout>
<!-- Datatable -->
<script src="{{ asset('AppealLab/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('AppealLab/js/plugins-init/datatables.init.js') }}"></script>

