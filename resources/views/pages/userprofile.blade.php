@extends('layouts.app')
@section('styles')
    <link rel="stylesheet" href="{{ asset('css/vendor/alertify/alertify.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/vendor/alertify/default.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/vendor/ajax/bootstrap-datepicker.min.css') }}">
    <style>
        .buttons{
            width:10rem;
        }
        .tab{
            margin-left:1rem;
        }
        .labels{
            width:12rem;
        }
        .short-labels{
            width:4rem;
        }
        .medium-labels{
            width:8rem;
        }
        .separator{
            width: 0.5rem;
        }
        .tiny-form{
            width: 4em;
        }
        .small-form{
            width: 10em;
        }
        .medium-form{
            width: 16em;
        }
        .long-form{
            width: 60rem;
        }
        /* Chrome, Safari, Edge, Opera */
        .no-spin::-webkit-outer-spin-button,
        .no-spin::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        .no-spin {
            -moz-appearance: textfield;
        }
    </style>
@endsection
@section('content')
    <div class="container" style="margin-top:6rem">
        <main style="font-size:1.2em;">
            <h1>My Profile</h1>

            <hr>
            <form class="needs-validation" method="POST" action="{{ route('app.profile.store') }}" novalidate>
                @csrf
                <div class="tab">
                    <h2 class="mb-4">Credentials</h2>

                    <div class="tab d-flex mb-3">
                        <div class="d-flex">
                            <p class="labels mb-0">Username</p>
                            <p class="separator mb-0">:</p>
                        </div>
                        <div class="input-group mb-0 long-form">
                            <input type="text" class="form-control" id="username" value="{{ old('email', $userdata->username) }}" disabled>
                        </div>
                    </div>

                    <div class="tab d-flex mb-3">
                        <div class="d-flex">
                            <p class="labels mb-0">Name</p>
                            <p class="separator mb-0">:</p>
                        </div>
                        <div class="input-group mb-0 long-form">
                            <input type="text" class="form-control" name="firstname" id="firstname" placeholder="firstname" value="{{ old('firstname', $userdata->firstname) }}" required>
                            <input type="text" class="form-control" name="lastname" id="lastname" placeholder="lastname" value="{{ old('lastname', $userdata->lastname) }}" required>
                        </div>
                    </div>

                    <div class="tab d-flex mb-3">
                        <div class="d-flex">
                            <p class="labels mb-0">Email</p>
                            <p class="separator mb-0">:</p>
                        </div>
                        <div class="input-group mb-0 medium-form">
                            <input type="email" class="form-control" id="email" name="email" placeholder="email@example.com" value="{{ old('email', $userdata->email) }}">
                        </div>
                    </div>
                    <div class="d-flex justify-content-end mb-5 mt-4 me-5">
                        <button type="button" class="btn btn-danger align-right buttons">Change Password</button>
                    </div>

                </div>

                <hr>

                <div class="tab" id="defaultBilling">
                    <h2 class="mb-4">
                        Address
                    </h2>

                    <div class="tab d-flex mb-3">
                        <div class="d-flex">
                            <p class="labels mb-0">Address</p>
                            <p class="separator mb-0">:</p>
                        </div>
                        <div class="input-group mb-0 long-form">
                            <input type="text" class="form-control" id="address" name="address" placeholder="address" value="{{ old('address', $userdata->address) }}" required>
                        </div>
                    </div>

                    <div class="tab d-flex mb-3">
                        <div class="d-flex me-5">
                            <div class="d-flex">
                                <p class="labels mb-0">City</p>
                                <p class="separator mb-0">:</p>
                            </div>
                            <div class="input-group mb-0 small-form">
                                <input type="text" class="form-control" id="city" name="city" placeholder="Kota / Kab." value="{{ old('city', $userdata->city) }}" required>
                            </div>
                        </div>
                        <div class="d-flex me-5">
                            <div class="d-flex">
                                <p class="medium-labels mb-0">Province</p>
                                <p class="separator mb-0">:</p>
                            </div>
                            <div class="input-group mb-0 small-form">
                                <input type="text" class="form-control" id="province" name="province" placeholder="Province" value="{{ old('province', $userdata->province) }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="tab d-flex mb-3">
                        <div class="d-flex">
                            <p class="labels mb-0">Zip Code</p>
                            <p class="separator mb-0">:</p>
                        </div>
                        <div class="input-group mb-0 tiny-form">
                            <input type="number" class="form-control no-spin" id="zip" name="zip" placeholder="xxxxxx" value="{{ old('zip', $userdata->zip) }}" required>
                        </div>
                    </div>

                    <div class="mb-5"></div>

                </div>
                
                <hr>

                <div class="tab">
                    <h2 class="mb-4">
                        Billing
                    </h2>

                    <div class="tab d-flex mb-3">
                        <div class="d-flex">
                            <p class="labels mb-0">Card Holder Name</p>
                            <p class="separator mb-0">:</p>
                        </div>
                        <div class="input-group mb-0 long-form">
                            <input type="text" class="form-control" id="card-name" name="card_name" placeholder="card holder name" value="{{ old('card_name', $userdata->card_name) }}" required>
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="tab d-flex mb-3">
                            <div class="d-flex">
                                <p class="labels mb-0">Card Number</p>
                                <p class="separator mb-0">:</p>
                            </div>
                            <div class="input-group mb-0 medium-form">
                                <input type="text" class="form-control" id="card_number" name="card_number" placeholder="xxxx xxxx xxxx xxxx" maxlength="19" value="{{ old('card_no', $userdata->card_no) }}" required>
                            </div>
                        </div>

                        <div class="tab d-flex mb-3 ms-5">
                            <div class="d-flex">
                                <p class="medium-labels mb-0">Card Type</p>
                                <p class="separator mb-0">:</p>
                            </div>
                            <div class="input-group mb-0">
                                <select class="form-select" id="card_type" name="card_type" required>
                                    <option disabled hidden {{ old('card_type', $userdata->card_type) == '' ? 'selected' : '' }}>Choose Card Type</option>
                                    <option value='1' {{ old('card_type', $userdata->card_type) == '1' ? 'selected' : '' }}>Credit Card</option>
                                    <option value='2' {{ old('card_type', $userdata->card_type) == '2' ? 'selected' : '' }}>Debit Card</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex">
                        <div class="tab d-flex mb-3">
                            <div class="d-flex">
                                <p class="labels mb-0">Expiration Date</p>
                                <p class="separator mb-0">:</p>
                            </div>
                            <div class="input-group mb-0 tiny-form ">
                                <input type="text" class="form-control datepicker" style="padding-left:0.8em;" id="card_expiration"
                                    placeholder="mm/yy" name="card_expiration" value="{{ old('card_expiration', $userdata->card_expiration) }}"
                                    required>
                            </div>
                        </div>
        
                        <div class="tab d-flex mb-3 ms-5">
                            <div class="d-flex">
                                <p class="short-labels mb-0">CVV</p>
                                <p class="separator mb-0">:</p>
                            </div>
                            <div class="input-group mb-0 tiny-form">
                                <input type="text" class="form-control" id="card_cvv" name="card_cvv" placeholder="xxx" value="{{ old('card_cvv', $userdata->card_cvv) }}" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-5"></div>

                </div>

                <hr>
                
                <div class="d-flex justify-content-end" style="margin-bottom:1rem;margin-top:5rem;">
                    <a type="button" href="{{ route('app.home.page') }}" class="btn btn-danger buttons me-4">Abort Changes</a>
                    <button type="submit" class="btn btn-primary buttons">Save Changes</button>
                </div>
            </form>

        </main>
    </div>
@endsection

@section('scripts')
    <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.js" integrity="sha256-JlqSTELeR4TLqP0OG9dxM7yDPqX1ox/HfgiSLBj8+kM="
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/js/bootstrap-datepicker.min.js"
        integrity="sha512-LsnSViqQyaXpD4mBBdRYeP6sRwJiJveh2ZIbW41EBrNmKxgr/LFZIiWT6yr+nycvhvauz8c2nYMhrP80YhG7Cw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script type="text/javascript">
        // Datepicker
        $('.datepicker').datepicker({
            format: "mm/yy",
            startView: "months",
            minViewMode: "months"
        });
        // card number formatter
        function formatGroups(value) {
            // remove non-digits
            value = value.replace(/\D/g, '');

            // group every 4 digits
            return value.replace(/(.{4})/g, '$1 ').trim();
        }

        const card_no = document.getElementById('card_number');

        // Format on input
        card_no.addEventListener('input', function (e) {
            e.target.value = formatGroups(e.target.value);
        });

        // ✅ Format initial value when page loads
        card_no.value = formatGroups(card_no.value);
    </script>
@endsection
