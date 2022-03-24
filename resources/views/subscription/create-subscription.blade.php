<x-app-layout>

    @php
        $months = array(1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Aug', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec');
    @endphp
    <!-- Company Overview section START -->
        <div class="container-fluid" >
            <div class="page-titles">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Authorize Payment Method</a></li>
                </ol>
            </div>
            <div class="card-panel">
                <div class="media wow fadeInUp" data-wow-duration="1s">
                    <div class="companyIcon">
                    </div>
                    <div class="media-body">

                        <div class="container">
                            @if(Session::has('success'))
                            <div class="alert alert-success alert-dismissible fade show">
                                <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><circle cx="12" cy="12" r="10"></circle><path d="M8 14s1.5 2 4 2 4-2 4-2"></path><line x1="9" y1="9" x2="9.01" y2="9"></line><line x1="15" y1="9" x2="15.01" y2="9"></line></svg>
                                <strong>Success!</strong>  {{ Session::get('success') }}
                                <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span>
                                </button>
                            </div>
                            @endif


                            <div class="row">
                                <div class="col-xs-12 col-md-6 card p-4" style="border-radius: 5px; padding: 10px;">

                                    <div class="panel panel-primary">
                                        <div class="creditCardForm">
                                            <div class="payment">
                                                <form id="payment-card-info" method="post" action="{{ url('subscription/subscription-create') }}">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="form-group owner col-md-8">
                                                            <label for="owner">Owner</label>
                                                            <input type="text" class="form-control" id="owner" name="owner" value="{{ old('owner') }}" >
                                                            @error('owner')
                                                            <span class="text-danger">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                        <div class="form-group CVV col-md-4">
                                                            <label for="cvv">CVV</label>
                                                            <input type="number" class="form-control" id="cvv" name="cvv" value="{{ old('cvv') }}" >
                                                            @error('cvv')
                                                            <span class="text-danger">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group col-md-8" id="card-number-field">
                                                            <label for="cardNumber">Card Number</label>
                                                            <input type="text" class="form-control" id="cardNumber" name="cardNumber" value="{{ old('cardNumber') }}" >
                                                            @error('cardNumber')
                                                            <span class="text-danger">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                        <div class="form-group col-md-4" >
                                                            <input type="hidden" id="amount" name="amount" class="form-control fname card-exp name-form" value="{{ $amount }}"  readonly>
                                                            <input type="hidden" id="platform" name="platform" class="form-control fname card-exp name-form" value="{{ $platform }}"  readonly>
                                                            <input type="hidden" id="subscriptionName" name="subscriptionName" class="form-control fname card-exp name-form" value="{{ $subscriptionName }}"  readonly>
                                                            {{--<span id="amount-error" class="error text-red">Please enter amount</span>--}}
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group col-md-6" id="expiration-date">
                                                            <label>Expiration Date</label><br/>
                                                            <select class="form-control" id="expiration-month" name="expiration-month" style="float: left; width: 100px; margin-right: 10px;">
                                                                @foreach($months as $k=>$v)
                                                                    <option value="{{ $k }}" {{ old('expiration-month') == $k ? 'selected' : '' }}>{{ $v }}</option>
                                                                @endforeach
                                                            </select>
                                                            <select class="form-control" id="expiration-year" name="expiration-year"  style="float: left; width: 100px;">

                                                                @for($i = date('Y'); $i <= (date('Y') + 15); $i++)
                                                                    <option value="{{ $i }}">{{ $i }}</option>
                                                                @endfor
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-6" id="credit_cards" style="margin-top: 22px;">
                                                            <img src="{{ asset('images/visa.jpg') }}" id="visa">
                                                            <img src="{{ asset('images/mastercard.jpg') }}" id="mastercard">
                                                            <img src="{{ asset('images/amex.jpg') }}" id="amex">
                                                        </div>
                                                    </div>

                                                    <br/>
                                                    <div class="form-group" id="pay-now">
                                                        <button type="submit" class="btn btn-primary themeButton" id="confirm-purchase">Confirm Payment</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-1"></div>
                                <div class="col-md-5"></div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="clearfix"></div>
</x-app-layout>
