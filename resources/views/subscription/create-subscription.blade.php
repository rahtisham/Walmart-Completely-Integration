<x-app-layout>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
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
                                {{-- @error('subscriptionName')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror --}}
                                <div class="col-xs-12 col-md-6 card p-4" style="border-radius: 5px; padding: 10px;">

                                    <div class="panel panel-primary">
                                        <div class="creditCardForm">
                                            <div class="payment">
                                                <form role="form" action="{{ url('subscription/subscription-create') }}" method="post" class="require-validation" data-cc-on-file="false"
                                                        data-stripe-publishable-key="pk_test_51JLDlJJFs9GUB8DUhKQDEODLsIpsHrFB2SuYhxpKch4OKdlYqwBsZL8Zuao5z0MvNtUC2cfgoWrjwQEPgwVaYYso00CcOlXbOU"
                                                        id="payment-form">
                                                    @csrf

                                                    <div class='form-row row'>
                                                        <div class='col-xs-12 form-group required'>
                                                            <label class='control-label'>Name on Card</label>
                                                            <input class='form-control card-holder' size='4' type='text' value="asif ali" name="owner">
                                                            @error('owner')
                                                            <span class="text-danger">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class='form-row row'>
                                                        <div class='col-xs-12 form-group card required'>
                                                            <label class='control-label'>Card Number</label> <input
                                                                autocomplete='off' class='form-control card-number' value="4242424242424242" name="cardNumber" size='20'
                                                                type='text'>
                                                                @error('cardNumber')
                                                                <span class="text-danger">{{ $message }}</span>
                                                                @enderror
                                                        </div>
                                                    </div>

                                                    <div class='form-row row'>
                                                        <div class='col-xs-12 col-md-4 form-group cvc required'>
                                                            <label class='control-label'>CVC</label>
                                                            <input autocomplete='off' class='form-control card-cvc' value="123" name="cvc" placeholder='ex. 311' size='4' type='text'>
                                                            @error('cvc')
                                                            <span class="text-danger">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                        <div class='col-xs-12 col-md-4 form-group expiration required'>
                                                            <label class='control-label'>Expiration Month</label>
                                                            <input class='form-control card-expiry-month' name="expiration-month" value="09" placeholder='MM' size='2' type='text'>
                                                            @error('expiration-month')
                                                            <span class="text-danger">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                        <div class='col-xs-12 col-md-4 form-group expiration required'>
                                                            <label class='control-label'>Expiration Year</label> <input
                                                                class='form-control card-expiry-year' name="expiration-year" value="2022" placeholder='YYYY' size='4'
                                                                type='text'>
                                                                @error('expiration-year')
                                                                <span class="text-danger">{{ $message }}</span>
                                                                @enderror
                                                                <input type="hidden" value="{{ $amount }}" name="amount">
                                                                <input type="hidden" value="{{ $Stripe_plan }}" name="stripePlan">
                                                                <input type="hidden" value="{{ $subscriptionName }}" name="subscriptionName">
                                                        </div>
                                                    </div>

                                                    {{-- <div class='form-row row'>
                                                        <div class='col-md-12 error form-group hide'>
                                                            <div class='alert-danger alert'>Please correct the errors and try
                                                                again.</div>
                                                        </div>
                                                    </div><br> --}}
                                                        <br>
                                                    <div class="row">
                                                        <div class="col-xs-12">
                                                            <button class="btn btn-primary btn-lg btn-block"  type="submit">Pay Now ($100)</button>
                                                        </div>
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


        <script type="text/javascript" src="https://js.stripe.com/v2/"></script>

        <script type="text/javascript">
        $(function() {

            var $form = $(".require-validation");

            $('form.require-validation').bind('submit', function(e) {

                var $form     = $(".require-validation"),
                inputSelector = ['input[type=email]', 'input[type=password]',
                                'input[type=text]', 'input[type=file]',
                                'textarea'].join(', '),
                $inputs       = $form.find('.required').find(inputSelector),
                $errorMessage = $form.find('div.error'),
                valid         = true;
                $errorMessage.addClass('hide');

                $('.has-error').removeClass('has-error');
                $inputs.each(function(i, el) {
                var $input = $(el);
                if ($input.val() === '') {
                    $input.parent().addClass('has-error');
                    $errorMessage.removeClass('hide');
                    e.preventDefault();
                }
                });

                if (!$form.data('cc-on-file')) {
                e.preventDefault();
                Stripe.setPublishableKey($form.data('stripe-publishable-key'));
                Stripe.createToken({
                    // text: $('.card-holder').val(),
                    number: $('.card-number').val(),
                    cvc: $('.card-cvc').val(),
                    exp_month: $('.card-expiry-month').val(),
                    exp_year: $('.card-expiry-year').val()
                }, stripeResponseHandler);
                }

        });

        function stripeResponseHandler(status, response) {
            // alert('testin');
                if (response.error) {
                    $('.error')
                        .removeClass('hide')
                        .find('.alert')
                        .text(response.error.message);
                } else {
                    /* token contains id, last4, and card type */
                    var token = response['id'];

                    $form.find('input[type=text]').empty();
                    $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
                    $form.get(0).submit();
                }
            }

        });
        </script>

        <style>

element.style {
}
.form-row > .col, .form-row > [class*="col-"] {
    padding-right: 5px;
    width: 100% !important;
    padding-left: 5px;
}
        </style>
</x-app-layout>
