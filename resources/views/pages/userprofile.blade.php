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
        .small-form{
            width: 7rem;
        }
        .medium-form{
            width: 20rem;
        }
        .long-form{
            width: 60rem;
        }
    </style>
@endsection
@section('content')
    <div class="container" style="margin-top:6rem">
        <main style="font-size:1.2em;">
            <h1>My Profile</h1>

            <hr>
            <div class="tab">
                <h2 class="mb-4">Credentials</h2>

                <div class="tab d-flex mb-3">
                    <div class="d-flex">
                        <p class="labels mb-0">Username</p>
                        <p class="separator mb-0">:</p>
                    </div>
                    <div class="input-group mb-0 long-form">
                        <input type="text" class="form-control" id="address" disabled>
                    </div>
                </div>

                <div class="tab d-flex mb-3">
                    <div class="d-flex">
                        <p class="labels mb-0">Name</p>
                        <p class="separator mb-0">:</p>
                    </div>
                    <div class="input-group mb-0 long-form">
                        <input type="text" class="form-control" id="firstname" placeholder="firstname">
                        <input type="text" class="form-control" id="lastname" placeholder="lastname">
                    </div>
                </div>

                <div class="tab d-flex mb-3">
                    <div class="d-flex">
                        <p class="labels mb-0">Email</p>
                        <p class="separator mb-0">:</p>
                    </div>
                    <div class="input-group mb-0 medium-form">
                        <input type="email" class="form-control" id="email" placeholder="email@example.com">
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
                        <input type="text" class="form-control" id="address" placeholder="address">
                    </div>
                </div>

                <div class="tab d-flex mb-3">
                    <div class="d-flex">
                        <p class="labels mb-0">Alternate Address</p>
                        <p class="separator mb-0">:</p>
                    </div>
                    <div class="input-group mb-0 long-form">
                        <input type="text" class="form-control" id="alt-address" placeholder="alternate address">
                    </div>
                </div>

                <div class="tab d-flex mb-3">
                    <div class="d-flex">
                        <p class="labels mb-0">Postal Code</p>
                        <p class="separator mb-0">:</p>
                    </div>
                    <div class="input-group mb-0 small-form">
                        <input type="text" class="form-control" id="postcode" placeholder="xxxxxx">
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
                        <input type="text" class="form-control" id="card-name" placeholder="card holder name">
                    </div>
                </div>
                <div class="d-flex">
                    <div class="tab d-flex mb-3">
                        <div class="d-flex">
                            <p class="labels mb-0">Card Number</p>
                            <p class="separator mb-0">:</p>
                        </div>
                        <div class="input-group mb-0 medium-form">
                            <input type="text" class="form-control" id="card-number" placeholder="xxxx xxxx xxxx xxxx">
                        </div>
                    </div>

                    <div class="tab d-flex mb-3 ms-5">
                        <div class="d-flex">
                            <p class="medium-labels mb-0">Card Type</p>
                            <p class="separator mb-0">:</p>
                        </div>
                        <div class="input-group mb-0">
                            <select class="form-select" id="inputGroupSelect01">
                                <option selected disabled hidden>Choose Card Type</option>
                                <option value="1">Credit Card</option>
                                <option value="2">Debit Card</option>
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
                        <div class="input-group mb-0 small-form ">
                            <input type="text" class="form-control datepicker" id="cc-expiration"
                                placeholder="mm/yy" name="card_expiration" value="{{ old('card_expiration') }}"
                                required>
                        </div>
                    </div>
    
                    <div class="tab d-flex mb-3 ms-5">
                        <div class="d-flex">
                            <p class="short-labels mb-0">CVV</p>
                            <p class="separator mb-0">:</p>
                        </div>
                        <div class="input-group mb-0 small-form">
                            <input type="text" class="form-control" id="cvv" placeholder="xxx">
                        </div>
                    </div>
                </div>
                
                <div class="mb-5"></div>

            </div>

            <hr>
            
            <div class="d-flex justify-content-end" style="margin-bottom:1rem;margin-top:5rem;">
                <button type="button" class="btn btn-danger buttons me-4">Abort Changes</button>
                <button type="button" class="btn btn-primary buttons">Save Changes</button>
            </div>

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
    </script>
@endsection
