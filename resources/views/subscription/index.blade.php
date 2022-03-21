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
                            Cancel Subscription
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
                                    <th>Amount</th>
                                    <th>Card Holder Name</th>
                                    <th>Subscription Name</th>
                                    <th>Platform</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach ($paymentLog as $paymentLogs)
                                    <tr>
                                        <td>{{ $paymentLogs['id'] }}</td>
                                        <td>{{ $paymentLogs['amount'] }}.00</td>
                                        <td>{{ $paymentLogs['name_on_card'] }}</td>
                                        <td>{{ $paymentLogs['subscriptionName'] }}</td>
                                        <td>
                                            <span class="badge light badge-success">
												<i class="fa fa-circle text-success mr-1"></i>
                                                    Walmart
											</span>
                                        </td>
                                        <td><br>
                                            <form method="post" action="{{ url('user/cancel' , $paymentLogs->subscription ) }}">
                                                @csrf
                                                {{-- <input name="_method" type="hidden" value="DELETE"> --}}
                                                <button type="submit" class="btn btn-xs btn-danger show_confirm" data-toggle="tooltip" title='Delete'>Delete</button>
                                            </form>
                                        </td>
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

