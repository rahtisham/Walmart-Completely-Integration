<!-- Datatable -->
<link href="{{ asset('AppealLab/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">


<!-- Custom Stylesheet -->
<x-app-layout>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <div class="container-fluid">
        <div class="page-titles">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">Subscription</a></li>
            </ol>
        </div>
        <!-- row -->


        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">
                            Subscriptions
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
                                    <th>Card Holder Name</th>
                                    <th>Amount</th>
                                    <th>Subscription</th>
                                    <th>Marketplace</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach ($paymentLog as $paymentLogs)
                                    <tr>
                                        <td>{{ $paymentLogs['name_on_card'] }}</td>
                                        <td>{{ $paymentLogs['amount'] }}.00</td>
                                        <td>{{ $paymentLogs['subscriptionName'] }}</td>
                                        <td>
                                            @if ($paymentLogs['message_code'] == "walmart_option1")
                                            <span class="badge light badge-success">
                                                <i class="fa fa-circle text-success mr-1"></i>
                                                Walmart
                                            </span>
                                            @endif
                                            @if ($paymentLogs['message_code'] == "walmart_option2")
                                            <span class="badge light badge-success">
                                                <i class="fa fa-circle text-success mr-1"></i>
                                                Walmart
                                            </span>
                                                @endif
                                            @if ($paymentLogs['message_code'] == "amazon_option1")
                                            <span class="badge light badge-info">
                                                <i class="fa fa-circle text-info mr-1"></i>
                                                Amazon
                                            </span>
                                                @endif
                                            @if ($paymentLogs['message_code'] == "amazon_option2")
                                            <span class="badge light badge-info">
                                                <i class="fa fa-circle text-info mr-1"></i>
                                                Amazon
                                            </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($paymentLogs['status'] == "cancel")
                                            <span class="badge light badge-warning">
                                                <i class="fa fa-circle text-warning mr-1"></i>
                                                {{ $paymentLogs['status'] }}
                                            </span>
                                            @endif

                                            @if ($paymentLogs['status'] == "active")
                                            <span class="badge light badge-primary">
                                                <i class="fa fa-circle text-primary mr-1"></i>
                                                {{ $paymentLogs['status'] }}
                                            </span>
                                            @endif
                                        </td>
                                        @if ($paymentLogs['status'] == "cancel")
                                        <td>
                                            -------
                                        </td>
                                        @endif
                                        @if ($paymentLogs['status'] == "active")
                                        <td><br>
                                            <form method="post" action="{{ url('user/cancel' , $paymentLogs->subscription ) }}">
                                                @csrf
                                                {{-- <input name="_method" type="hidden" value="DELETE"> --}}
                                                <button type="submit" class="btn btn-xs btn-danger show_confirm" data-toggle="tooltip" title='Delete'>Cancel</button>
                                            </form>
                                        </td>
                                        @endif
                                    </tr>
                                    @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
    <script type="text/javascript">

         $('.show_confirm').click(function(event) {
              var form =  $(this).closest("form");
              var name = $(this).data("name");
              event.preventDefault();
              swal({
                  title: `Are you sure you want to delete your subscription?`,
                  text: "If you delete this, it will be gone forever.",
                  icon: "warning",
                  buttons: true,
                  dangerMode: true,
              })
              .then((willDelete) => {
                if (willDelete) {
                  form.submit();
                }
              });
          });

    </script>
</x-app-layout>
<!-- Datatable -->
<script src="{{ asset('AppealLab/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('AppealLab/js/plugins-init/datatables.init.js') }}"></script>

