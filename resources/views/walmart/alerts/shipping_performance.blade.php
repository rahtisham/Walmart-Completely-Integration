<x-app-layout>

    <div class="container-fluid">
        <div class="page-titles">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">Shipping Performance</a></li>
            </ol>
        </div>
        <!-- row -->
        <div class="row">
            <div class="col-xl-12 col-lg-12">
                @if(\Session::has('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Appeal Lab!</strong> {{ \Session::get('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Shipping Performance</h4>
                        <a href="{{ route('dashboard.shipping-performance-add') }}" class="btn btn-warning">Shipping Performance Items</a>
                        <a href="{{ route('dashboard.shipping-performance-order') }}" class="btn btn-success">Shipping Performance Order</a>
                    </div>

                </div>
            </div>
        </div>
</x-app-layout>
