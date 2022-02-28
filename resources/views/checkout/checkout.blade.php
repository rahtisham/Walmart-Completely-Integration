<!-- Latest compiled and minified CSS -->
<x-guest-layout>
    @php
        $months = array(1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Aug', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec');
    @endphp
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <div class="container">
        <div class="row">
            <div class="col-md-7 py-3">

                <img src="{{ asset('AppealLab/images/logoa.png') }}" alt="" width="242px">

                <div class="support-text py-4">
                    <p class="px-0">Need support?</p>
                    <p class="px-3 mail-to"><a href="mailto:hello@appeallab.com"><i class="fa fa-envelope px-2"></i>hello@appeallab.com</a></p>
                </div>

                <h4 class="info-h1 py-1">YOUR INFORMATION</h4>
                @if(Session::has('cardError'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        <strong>Transaction Failed !</strong>  {{ Session::get('cardError') }}
                        <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span>
                        </button>
                    </div>
                @endif
                <form method="POST" action="{{ route('create') }}">
                    @csrf
                    <div class="form-div">
                        <div class="form-group">
                            <x-jet-input id="email" class="form-control email-form" type="email" name="email" placeholder="Enter Email" value="{{ old('email') }}" autofocus autocomplete="name" />
                            @error('email')
                            <span class="text-danger"> {{ $message }}</span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <x-jet-input type="text" name="fname" placeholder="First Name" class="form-control fname name-form" value="{{ old('fname') }}" autofocus autocomplete="name"/>
                                @error('fname')
                                <span class="text-danger"> {{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <x-jet-input type="text" id="lname" name="lname" placeholder="Last Name" class="form-control lname name-form" value="{{ old('lname') }}" autofocus autocomplete="last_name"/>
                                @error('lname')
                                <span class="text-danger"> {{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <x-jet-input type="text" id="address" name="address" placeholder="Street Address" class="form-control name-form" value="{{ old('address') }}" autofocus autocomplete="address"/>
                                @error('address')
                                <span class="text-danger"> {{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <x-jet-input type="text" id="city" name="city" placeholder="City" class="form-control lname name-form" value="{{ old('city') }}" autofocus autocomplete="city" />
                                @error('city')
                                <span class="text-danger"> {{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-4">
                                <x-jet-input type="number" id="postal" name="postal" placeholder="Postcode / Zip" class="form-control name-form" value="{{ old('postal') }}" autofocus autocomplete="postal" />
                                @error('postal')
                                <span class="text-danger"> {{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <select id="country" name="country" class="" style="width: 100%; border: 1px solid #ced4da;">
                                    <option value="">Select Country</option>
                                    <option value="Afganistan">Afghanistan</option>
                                    <option value="Albania">Albania</option>
                                    <option value="Algeria">Algeria</option>
                                    <option value="American Samoa">American Samoa</option>
                                    <option value="Andorra">Andorra</option>
                                    <option value="Angola">Angola</option>
                                    <option value="Anguilla">Anguilla</option>
                                    <option value="Antigua & Barbuda">Antigua & Barbuda</option>
                                    <option value="Argentina">Argentina</option>
                                    <option value="Armenia">Armenia</option>
                                    <option value="Aruba">Aruba</option>
                                    <option value="Australia">Australia</option>
                                    <option value="Austria">Austria</option>
                                    <option value="Azerbaijan">Azerbaijan</option>
                                    <option value="Bahamas">Bahamas</option>
                                    <option value="Bahrain">Bahrain</option>
                                    <option value="Bangladesh">Bangladesh</option>
                                    <option value="Barbados">Barbados</option>
                                    <option value="Belarus">Belarus</option>
                                    <option value="Belgium">Belgium</option>
                                    <option value="Belize">Belize</option>
                                    <option value="Benin">Benin</option>
                                    <option value="Bermuda">Bermuda</option>
                                    <option value="Bhutan">Bhutan</option>
                                    <option value="Bolivia">Bolivia</option>
                                    <option value="Bonaire">Bonaire</option>
                                    <option value="Bosnia & Herzegovina">Bosnia & Herzegovina</option>
                                    <option value="Botswana">Botswana</option>
                                    <option value="Brazil">Brazil</option>
                                    <option value="British Indian Ocean Ter">British Indian Ocean Ter</option>
                                    <option value="Brunei">Brunei</option>
                                    <option value="Bulgaria">Bulgaria</option>
                                    <option value="Burkina Faso">Burkina Faso</option>
                                    <option value="Burundi">Burundi</option>
                                    <option value="Cambodia">Cambodia</option>
                                    <option value="Cameroon">Cameroon</option>
                                    <option value="Canada">Canada</option>
                                    <option value="Canary Islands">Canary Islands</option>
                                    <option value="Cape Verde">Cape Verde</option>
                                    <option value="Cayman Islands">Cayman Islands</option>
                                    <option value="Central African Republic">Central African Republic</option>
                                    <option value="Chad">Chad</option>
                                    <option value="Channel Islands">Channel Islands</option>
                                    <option value="Chile">Chile</option>
                                    <option value="China">China</option>
                                    <option value="Christmas Island">Christmas Island</option>
                                    <option value="Cocos Island">Cocos Island</option>
                                    <option value="Colombia">Colombia</option>
                                    <option value="Comoros">Comoros</option>
                                    <option value="Congo">Congo</option>
                                    <option value="Cook Islands">Cook Islands</option>
                                    <option value="Costa Rica">Costa Rica</option>
                                    <option value="Cote DIvoire">Cote DIvoire</option>
                                    <option value="Croatia">Croatia</option>
                                    <option value="Cuba">Cuba</option>
                                    <option value="Curaco">Curacao</option>
                                    <option value="Cyprus">Cyprus</option>
                                    <option value="Czech Republic">Czech Republic</option>
                                    <option value="Denmark">Denmark</option>
                                    <option value="Djibouti">Djibouti</option>
                                    <option value="Dominica">Dominica</option>
                                    <option value="Dominican Republic">Dominican Republic</option>
                                    <option value="East Timor">East Timor</option>
                                    <option value="Ecuador">Ecuador</option>
                                    <option value="Egypt">Egypt</option>
                                    <option value="El Salvador">El Salvador</option>
                                    <option value="Equatorial Guinea">Equatorial Guinea</option>
                                    <option value="Eritrea">Eritrea</option>
                                    <option value="Estonia">Estonia</option>
                                    <option value="Ethiopia">Ethiopia</option>
                                    <option value="Falkland Islands">Falkland Islands</option>
                                    <option value="Faroe Islands">Faroe Islands</option>
                                    <option value="Fiji">Fiji</option>
                                    <option value="Finland">Finland</option>
                                    <option value="France">France</option>
                                    <option value="French Guiana">French Guiana</option>
                                    <option value="French Polynesia">French Polynesia</option>
                                    <option value="French Southern Ter">French Southern Ter</option>
                                    <option value="Gabon">Gabon</option>
                                    <option value="Gambia">Gambia</option>
                                    <option value="Georgia">Georgia</option>
                                    <option value="Germany">Germany</option>
                                    <option value="Ghana">Ghana</option>
                                    <option value="Gibraltar">Gibraltar</option>
                                    <option value="Great Britain">Great Britain</option>
                                    <option value="Greece">Greece</option>
                                    <option value="Greenland">Greenland</option>
                                    <option value="Grenada">Grenada</option>
                                    <option value="Guadeloupe">Guadeloupe</option>
                                    <option value="Guam">Guam</option>
                                    <option value="Guatemala">Guatemala</option>
                                    <option value="Guinea">Guinea</option>
                                    <option value="Guyana">Guyana</option>
                                    <option value="Haiti">Haiti</option>
                                    <option value="Hawaii">Hawaii</option>
                                    <option value="Honduras">Honduras</option>
                                    <option value="Hong Kong">Hong Kong</option>
                                    <option value="Hungary">Hungary</option>
                                    <option value="Iceland">Iceland</option>
                                    <option value="Indonesia">Indonesia</option>
                                    <option value="India">India</option>
                                    <option value="Iran">Iran</option>
                                    <option value="Iraq">Iraq</option>
                                    <option value="Ireland">Ireland</option>
                                    <option value="Isle of Man">Isle of Man</option>
                                    <option value="Israel">Israel</option>
                                    <option value="Italy">Italy</option>
                                    <option value="Jamaica">Jamaica</option>
                                    <option value="Japan">Japan</option>
                                    <option value="Jordan">Jordan</option>
                                    <option value="Kazakhstan">Kazakhstan</option>
                                    <option value="Kenya">Kenya</option>
                                    <option value="Kiribati">Kiribati</option>
                                    <option value="Korea North">Korea North</option>
                                    <option value="Korea Sout">Korea South</option>
                                    <option value="Kuwait">Kuwait</option>
                                    <option value="Kyrgyzstan">Kyrgyzstan</option>
                                    <option value="Laos">Laos</option>
                                    <option value="Latvia">Latvia</option>
                                    <option value="Lebanon">Lebanon</option>
                                    <option value="Lesotho">Lesotho</option>
                                    <option value="Liberia">Liberia</option>
                                    <option value="Libya">Libya</option>
                                    <option value="Liechtenstein">Liechtenstein</option>
                                    <option value="Lithuania">Lithuania</option>
                                    <option value="Luxembourg">Luxembourg</option>
                                    <option value="Macau">Macau</option>
                                    <option value="Macedonia">Macedonia</option>
                                    <option value="Madagascar">Madagascar</option>
                                    <option value="Malaysia">Malaysia</option>
                                    <option value="Malawi">Malawi</option>
                                    <option value="Maldives">Maldives</option>
                                    <option value="Mali">Mali</option>
                                    <option value="Malta">Malta</option>
                                    <option value="Marshall Islands">Marshall Islands</option>
                                    <option value="Martinique">Martinique</option>
                                    <option value="Mauritania">Mauritania</option>
                                    <option value="Mauritius">Mauritius</option>
                                    <option value="Mayotte">Mayotte</option>
                                    <option value="Mexico">Mexico</option>
                                    <option value="Midway Islands">Midway Islands</option>
                                    <option value="Moldova">Moldova</option>
                                    <option value="Monaco">Monaco</option>
                                    <option value="Mongolia">Mongolia</option>
                                    <option value="Montserrat">Montserrat</option>
                                    <option value="Morocco">Morocco</option>
                                    <option value="Mozambique">Mozambique</option>
                                    <option value="Myanmar">Myanmar</option>
                                    <option value="Nambia">Nambia</option>
                                    <option value="Nauru">Nauru</option>
                                    <option value="Nepal">Nepal</option>
                                    <option value="Netherland Antilles">Netherland Antilles</option>
                                    <option value="Netherlands">Netherlands (Holland, Europe)</option>
                                    <option value="Nevis">Nevis</option>
                                    <option value="New Caledonia">New Caledonia</option>
                                    <option value="New Zealand">New Zealand</option>
                                    <option value="Nicaragua">Nicaragua</option>
                                    <option value="Niger">Niger</option>
                                    <option value="Nigeria">Nigeria</option>
                                    <option value="Niue">Niue</option>
                                    <option value="Norfolk Island">Norfolk Island</option>
                                    <option value="Norway">Norway</option>
                                    <option value="Oman">Oman</option>
                                    <option value="Pakistan">Pakistan</option>
                                    <option value="Palau Island">Palau Island</option>
                                    <option value="Palestine">Palestine</option>
                                    <option value="Panama">Panama</option>
                                    <option value="Papua New Guinea">Papua New Guinea</option>
                                    <option value="Paraguay">Paraguay</option>
                                    <option value="Peru">Peru</option>
                                    <option value="Phillipines">Philippines</option>
                                    <option value="Pitcairn Island">Pitcairn Island</option>
                                    <option value="Poland">Poland</option>
                                    <option value="Portugal">Portugal</option>
                                    <option value="Puerto Rico">Puerto Rico</option>
                                    <option value="Qatar">Qatar</option>
                                    <option value="Republic of Montenegro">Republic of Montenegro</option>
                                    <option value="Republic of Serbia">Republic of Serbia</option>
                                    <option value="Reunion">Reunion</option>
                                    <option value="Romania">Romania</option>
                                    <option value="Russia">Russia</option>
                                    <option value="Rwanda">Rwanda</option>
                                    <option value="St Barthelemy">St Barthelemy</option>
                                    <option value="St Eustatius">St Eustatius</option>
                                    <option value="St Helena">St Helena</option>
                                    <option value="St Kitts-Nevis">St Kitts-Nevis</option>
                                    <option value="St Lucia">St Lucia</option>
                                    <option value="St Maarten">St Maarten</option>
                                    <option value="St Pierre & Miquelon">St Pierre & Miquelon</option>
                                    <option value="St Vincent & Grenadines">St Vincent & Grenadines</option>
                                    <option value="Saipan">Saipan</option>
                                    <option value="Samoa">Samoa</option>
                                    <option value="Samoa American">Samoa American</option>
                                    <option value="San Marino">San Marino</option>
                                    <option value="Sao Tome & Principe">Sao Tome & Principe</option>
                                    <option value="Saudi Arabia">Saudi Arabia</option>
                                    <option value="Senegal">Senegal</option>
                                    <option value="Seychelles">Seychelles</option>
                                    <option value="Sierra Leone">Sierra Leone</option>
                                    <option value="Singapore">Singapore</option>
                                    <option value="Slovakia">Slovakia</option>
                                    <option value="Slovenia">Slovenia</option>
                                    <option value="Solomon Islands">Solomon Islands</option>
                                    <option value="Somalia">Somalia</option>
                                    <option value="South Africa">South Africa</option>
                                    <option value="Spain">Spain</option>
                                    <option value="Sri Lanka">Sri Lanka</option>
                                    <option value="Sudan">Sudan</option>
                                    <option value="Suriname">Suriname</option>
                                    <option value="Swaziland">Swaziland</option>
                                    <option value="Sweden">Sweden</option>
                                    <option value="Switzerland">Switzerland</option>
                                    <option value="Syria">Syria</option>
                                    <option value="Tahiti">Tahiti</option>
                                    <option value="Taiwan">Taiwan</option>
                                    <option value="Tajikistan">Tajikistan</option>
                                    <option value="Tanzania">Tanzania</option>
                                    <option value="Thailand">Thailand</option>
                                    <option value="Togo">Togo</option>
                                    <option value="Tokelau">Tokelau</option>
                                    <option value="Tonga">Tonga</option>
                                    <option value="Trinidad & Tobago">Trinidad & Tobago</option>
                                    <option value="Tunisia">Tunisia</option>
                                    <option value="Turkey">Turkey</option>
                                    <option value="Turkmenistan">Turkmenistan</option>
                                    <option value="Turks & Caicos Is">Turks & Caicos Is</option>
                                    <option value="Tuvalu">Tuvalu</option>
                                    <option value="Uganda">Uganda</option>
                                    <option value="United Kingdom">United Kingdom</option>
                                    <option value="Ukraine">Ukraine</option>
                                    <option value="United Arab Erimates">United Arab Emirates</option>
                                    <option value="United States of America">United States of America</option>
                                    <option value="Uraguay">Uruguay</option>
                                    <option value="Uzbekistan">Uzbekistan</option>
                                    <option value="Vanuatu">Vanuatu</option>
                                    <option value="Vatican City State">Vatican City State</option>
                                    <option value="Venezuela">Venezuela</option>
                                    <option value="Vietnam">Vietnam</option>
                                    <option value="Virgin Islands (Brit)">Virgin Islands (Brit)</option>
                                    <option value="Virgin Islands (USA)">Virgin Islands (USA)</option>
                                    <option value="Wake Island">Wake Island</option>
                                    <option value="Wallis & Futana Is">Wallis & Futana Is</option>
                                    <option value="Yemen">Yemen</option>
                                    <option value="Zaire">Zaire</option>
                                    <option value="Zambia">Zambia</option>
                                    <option value="Zimbabwe">Zimbabwe</option>
                                </select>
                                @error('country')
                                <span class="text-danger"> {{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <x-jet-input type="text" id="state" name="state" placeholder="State" class="form-control name-form" value="{{ old('state') }}" autofocus autocomplete="state" />
                                @error('state')
                                <span class="text-danger"> {{ $message }}</span>
                                @enderror
                            </div>
                        </div>


                        <div class="form-group mt-3">
                            <x-jet-input type="number" id="contact" name="contact" placeholder="Phone Number" class="form-control name-form" value="{{ old('contact') }}" autofocus autocomplete="contact"  />
                            @error('contact')
                            <span class="text-danger"> {{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <x-jet-input type="password" id="password" name="password" placeholder="Password" class="form-control name-form" autocomplete="new-password" />
                            @error('password')
                            <span class="text-danger"> {{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <x-jet-input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password" class="form-control name-form" autocomplete="new-password" />
                            @error('password_confirmation')
                            <span class="text-danger"> {{ $message }}</span>
                            @enderror
                        </div>

                        <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                            {{ __('Already registered?') }}
                        </a>


                        @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                            <div class="mt-4">
                                <x-jet-label for="terms">
                                    <div class="flex items-center">
                                        <x-jet-checkbox name="terms" id="terms"/>

                                        <div class="ml-2">
                                            {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                                    'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-sm text-gray-600 hover:text-gray-900">'.__('Terms of Service').'</a>',
                                                    'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-sm text-gray-600 hover:text-gray-900">'.__('Privacy Policy').'</a>',
                                            ]) !!}
                                        </div>
                                    </div>
                                </x-jet-label>
                            </div>
                        @endif

                    </div>

                    <div class="payment-sec py-3">
                        <h4 class="info-h1 py-1">PAYMENT INFORMATION</h4>
                        <p class="payment-para">All transactions are secure and encrypted. Credit card information is never
                            stored on our servers.</p>
                        <div class="card" style="width:100%;">
                            <div class="card-header">
                                <h6 style="font-weight: 400;">Credit Card</h6>
                            </div>

                            <div class="container py-3">
                                <div class="row">
                                    <div class="col-md-12">
                                        {{--                                    <label class="card-labels" for="card-num">Owner Name</label>--}}
                                        <input type="text" id="owner" name="owner"  placeholder="Owner Name" class="form-control card-num name-form" value="{{ old('owner') }}">
                                        @error('owner')
                                        <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-12 mt-3">
                                        {{--                                    <label class="card-labels" for="card-num">Card Number</label>--}}
                                        <input type="number" id="cardNumber" name="cardNumber" placeholder="1234 1234 1234 1234" class="form-control card-num name-form" value="{{ old('cardNumber') }}">
                                        @error('cardNumber')
                                        <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 py-3">
                                        {{--                                    <label class="card-labels" for="card-cvc">Card Code (CVC)</label>--}}
                                        <input type="number" id="cvv" name="cvv" placeholder="CVC" class="form-control lname card-cvc name-form" value="{{ old('cvv') }}" >
                                        @error('cvv')
                                        <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 py-3">
                                        {{--                                    <label class="card-labels" for="card-exp">Amount</label>--}}
                                        <input type="text" id="amount" name="amount" class="form-control fname card-exp name-form" value="${{ $amount }}"  readonly>
                                        @error('amount')
                                        <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
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
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end">
                        <x-jet-button class="btn-form-submit my-4 text-center">
                            {{ __('Subscribe Now') }}
                        </x-jet-button>
                    </div>

                </form>
                <p class="payment-para">Your personal data will be used to process your order, support your experience
                    throughout this website, and for other purposes described in our <a href="#">privacy policy</a>.</p>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                    <label class="form-check-label" for="flexCheckDefault">
                        I have read and agree to the website's <a href="#">terms and conditions*</a>
                    </label>
                </div>
                <div class="last-sec py-3">
                    <div class="last-sec-heading">We Respect Your privacy & Information</div>

                    <div class="container py-4">
                        <div class="row">
                            <div class="three-column col-sm-4">
                                <img src=".\images\privacy.png" alt="" width="50%">
                                <p class="py-3 three-col-text">We Protect Your Privacy</p>

                            </div>
                            <div class="three-column col-sm-4">
                                <img src=".\images\ribbon.png" alt="" width="50%">
                                <p class="py-3 three-col-text">100% Satisfaction Guaranteed</p>

                            </div>
                            <div class="three-column col-sm-4">
                                <img src=".\images\secure.png" alt="" width="50%">
                                <p class="py-3 three-col-text">Your Information Is Secure</p>

                            </div>
                        </div>
                    </div>
                </div>

            </div> <!-- end od col-sm-7 -->

            <div class="col-md-5 bgColor">

                <div class="row">
                    <div class="col-md-9 pull-left" style="display: flex;">
                        <img src="{{ asset('AppealLab/images/checkout.jpg') }}">
                        <p class="txtColor">Account Protection - Walmart WFS <br><span class="txtColor">${{ $amount }}/month</span></p>
                    </div>
                    <div class="col-md-3 pull-right justify-content-center">
                        <p class="txtColor">${{ $amount }}</p>
                    </div>
                </div> <!--end of row-->

                <hr>


                <hr>

                <div class="bg-light clearfix">
                    <div class="pull-left txtColor"><h5>Subtotal</h5></div>
                    <div class="pull-right txtColor"><h5>${{ $amount }}</h5></div>
                </div>

                <hr>

                <div class="bg-light clearfix">
                    <div class="pull-left txtColor"><h5>Total</h5></div>
                    <div class="pull-right txtColor"><h5>${{ $amount }}</h5></div>
                </div>

                <hr>

                <div class="mainCent">

                    <h5 class="txtColor">WHY BUY FROM US</h5>

                    <h6 class="mg txt txtColor"><i class="fa fa-check" style="color: cornflowerblue; margin-top: 10px;"></i> 100% Safe and Secure</h6><br>
                    <h6 class="mg txtColor"><i class="fa fa-check" style="color: cornflowerblue;"></i> 2+ years experience</h6><br>
                    <h6 class="mg txtColor"><i class="fa fa-check" style="color: cornflowerblue;"></i> Hundreds of accounts reinstated</h6><br>
                    <h6 class="mg txtColor"><i class="fa fa-check" style="color: cornflowerblue;"></i> Fastest delivery time in the industry</h6><br>
                </div>
                <h5 class="hdTXT txtColor">WHAT THEY'RE SAYING</h5>

                <h6 class="mgTop txtColor"><b>Fast & reliable</b></h6>
                <i class="fa fa-star" style="color: orange;"></i>
                <i class="fa fa-star" style="color: orange;"></i>
                <i class="fa fa-star" style="color: orange;"></i>
                <i class="fa fa-star" style="color: orange;"></i>
                <i class="fa fa-star" style="color: orange;"></i>

                <p class="txtColor">
                    My account was suspended on Walmart and I couldn't
                    find anyone to help because of how new the platform
                    is. Appeal lab was able to assist me with my ODR appeal
                    so that I could begin selling again.
                </p>
                <h6>Christine M.</h6>

                <h6 class="mgTop txtColor"><b>up and running again</b></h6>
                <i class="fa fa-star" style="color: orange;"></i>
                <i class="fa fa-star" style="color: orange;"></i>
                <i class="fa fa-star" style="color: orange;"></i>
                <i class="fa fa-star" style="color: orange;"></i>
                <i class="fa fa-star" style="color: orange;"></i>

                <p class="txtColor">
                    I have a dropshipping account and was suspended but was able to get it back up because of your appeals.
                </p>
                <h6>Adam P.</h6>

            </div> <!-- end of col-md-5 -->
        </div> <!--end of row -->
    </div> <!--end of container -->

    <style>
        .txtColor
        {
            color: #737373;
        }
        .bgColor
        {
            background: linear-gradient(90deg, #FFFFFF 0%, #F7F7F7 0%);
            padding: 25px;
        }
        .mainCent
        {
            margin-top: 50px;
        }
        .check
        {
            color: red;
        }
        .txt
        {
            margin-top: 20px !important;
        }
        .mg
        {
            margin-top: -12px;
        }
        .mgTop
        {
            margin-top: 21px;
        }

    </style>

    {{--    <x-jet-authentication-card>--}}
    {{--        <x-slot name="logo">--}}
    {{--            <x-jet-authentication-card-logo />--}}
    {{--        </x-slot>--}}





    {{--        <form method="POST" action="{{ route('register') }}">--}}
    {{--            @csrf--}}

    {{--            <div>--}}
    {{--                <label for="name" value="{{ __('Name') }}" />--}}
    {{--                <input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" autofocus autocomplete="name" />--}}
    {{--                @error('name')--}}
    {{--                <span class="text-danger"> {{ $message }}</span>--}}
    {{--                @enderror--}}
    {{--            </div>--}}

    {{--            <div class="mt-4">--}}
    {{--                <label for="email" value="{{ __('Email') }}" />--}}
    {{--                <input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" />--}}
    {{--                @error('email')--}}
    {{--                <span class="text-danger"> {{ $message }}</span>--}}
    {{--                @enderror--}}
    {{--            </div>--}}

    {{--            <div class="mt-4">--}}
    {{--                <label for="password" value="{{ __('Password') }}" />--}}
    {{--                <input id="password" class="block mt-1 w-full" type="password" name="password" autocomplete="new-password" />--}}
    {{--                @error('password')--}}
    {{--                <span class="text-danger"> {{ $message }}</span>--}}
    {{--                @enderror--}}
    {{--            </div>--}}

    {{--            <div class="mt-4">--}}
    {{--                <label for="password_confirmation" value="{{ __('Confirm Password') }}" />--}}
    {{--                <input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" autocomplete="new-password" />--}}
    {{--                @error('password_confirmation')--}}
    {{--                <span class="text-danger"> {{ $message }}</span>--}}
    {{--                @enderror--}}
    {{--            </div>--}}


    {{--            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())--}}
    {{--                <div class="mt-4">--}}
    {{--                    <x-jet-label for="terms">--}}
    {{--                        <div class="flex items-center">--}}
    {{--                            <x-jet-checkbox name="terms" id="terms"/>--}}

    {{--                            <div class="ml-2">--}}
    {{--                                {!! __('I agree to the :terms_of_service and :privacy_policy', [--}}
    {{--                                        'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-sm text-gray-600 hover:text-gray-900">'.__('Terms of Service').'</a>',--}}
    {{--                                        'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-sm text-gray-600 hover:text-gray-900">'.__('Privacy Policy').'</a>',--}}
    {{--                                ]) !!}--}}
    {{--                            </div>--}}
    {{--                        </div>--}}
    {{--                    </x-jet-label>--}}
    {{--                </div>--}}
    {{--            @endif--}}

    {{--            <div class="flex items-center justify-end mt-4">--}}
    {{--                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">--}}
    {{--                    {{ __('Already registered?') }}--}}
    {{--                </a>--}}

    {{--                <x-jet-button class="ml-4">--}}
    {{--                    {{ __('Register') }}--}}
    {{--                </x-jet-button>--}}
    {{--            </div>--}}
    {{--        </form>--}}
    {{--    </x-jet-authentication-card>--}}
</x-guest-layout>


