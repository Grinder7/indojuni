<?php
@extends('layouts.app')
@section('title', 'Profil - IndoJuni')
@section('styles')
    <link rel="stylesheet" href="{{ asset('css/vendor/alertify/alertify.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/vendor/alertify/default.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/vendor/ajax/bootstrap-datepicker.min.css') }}">
    <style>
        .buttons {
            width: 10rem;
        }

        .tab {
            margin-left: 1rem;
        }

        .labels {
            width: 12rem;
        }

        .short-labels {
            width: 4rem;
        }

        .medium-labels {
            width: 8rem;
        }

        .separator {
            width: 0.5rem;
        }

        .tiny-form {
            width: 4em;
        }

        .small-form {
            width: 10em;
        }

        .medium-form {
            width: 16em;
        }

        .long-form {
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
            <h1>Profil Saya</h1>

            <hr>
            <form class="needs-validation" method="POST" action="{{ route('app.profile.store') }}" novalidate>
                @csrf
                <div class="tab">
                    <h2 class="mb-4">Kredensial</h2>

                    <div class="tab d-flex mb-3">
                        <div class="d-flex">
                            <p class="labels mb-0">Username</p>
                            <p class="separator mb-0">:</p>
                        </div>
                        <div class="input-group long-form mb-0">
                            <input type="text" class="form-control" id="username"
                                value="{{ old('username', $userdata->username) }}" disabled>
                        </div>
                    </div>

                    <div class="tab d-flex mb-3">
                        <div class="d-flex">
                            <p class="labels mb-0">Nama</p>
                            <p class="separator mb-0">:</p>
                        </div>
                        <div class="input-group long-form mb-0">
                            <input type="text" class="form-control" name="firstname" id="firstname"
                                placeholder="Nama Depan" value="{{ old('firstname', $userdata->firstname) }}" required>
                            <input type="text" class="form-control" name="lastname" id="lastname" placeholder="Nama Belakang"
                                value="{{ old('lastname', $userdata->lastname) }}" required>
                        </div>
                    </div>

                    <div class="tab d-flex mb-3">
                        <div class="d-flex">
                            <p class="labels mb-0">Email</p>
                            <p class="separator mb-0">:</p>
                        </div>
                        <div class="input-group medium-form mb-0">
                            <input type="email" class="form-control" id="email" name="email"
                                placeholder="email@contoh.com" value="{{ old('email', $userdata->email) }}">
                        </div>
                    </div>
                    {{-- <div class="d-flex justify-content-end mb-5 mt-4 me-5">
                        <button type="button" class="btn btn-danger align-right buttons">Change Password</button>
                    </div> --}}
                </div>

                <hr>

                <div class="tab" id="defaultBilling">
                    <h2 class="mb-4">
                        Alamat
                    </h2>

                    <div class="tab d-flex mb-3">
                        <div class="d-flex">
                            <p class="labels mb-0">Alamat</p>
                            <p class="separator mb-0">:</p>
                        </div>
                        <div class="input-group long-form mb-0">
                            <input type="text" class="form-control" id="address" name="address" placeholder="Alamat"
                                value="{{ old('address', $userdata->address) }}" required>
                        </div>
                    </div>

                    <div class="tab d-flex mb-3">
                        <div class="d-flex me-5">
                            <div class="d-flex">
                                <p class="labels mb-0">Kota</p>
                                <p class="separator mb-0">:</p>
                            </div>
                            <div class="input-group small-form mb-0">
                                <input type="text" class="form-control" id="city" name="city"
                                    placeholder="Kota / Kab." value="{{ old('city', $userdata->city) }}" required>
                            </div>
                        </div>
                        <div class="d-flex me-5">
                            <div class="d-flex">
                                <p class="medium-labels mb-0">Provinsi</p>
                                <p class="separator mb-0">:</p>
                            </div>
                            <div class="input-group small-form mb-0">
                                <input type="text" class="form-control" id="province" name="province"
                                    placeholder="Provinsi" value="{{ old('province', $userdata->province) }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="tab d-flex mb-3">
                        <div class="d-flex">
                            <p class="labels mb-0">Kode Pos</p>
                            <p class="separator mb-0">:</p>
                        </div>
                        <div class="input-group tiny-form mb-0">
                            <input type="number" class="form-control no-spin" id="postcode" name="postcode"
                                placeholder="xxxxxx" value="{{ old('postcode', $userdata->postcode) }}" required>
                        </div>
                    </div>

                    <div class="mb-5"></div>

                </div>

                <hr>

                <div class="tab">
                    <h2 class="mb-4">
                        Penagihan
                    </h2>

                    <div class="tab d-flex mb-3">
                        <div class="d-flex">
                            <p class="labels mb-0">Nama Pemegang Kartu</p>
                            <p class="separator mb-0">:</p>
                        </div>
                        <div class="input-group long-form mb-0">
                            <input type="text" class="form-control" id="card-name" name="card_name"
                                placeholder="Nama pemegang kartu" value="{{ old('card_name', $userdata->card_name) }}"
                                required>
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="tab d-flex mb-3">
                            <div class="d-flex">
                                <p class="labels mb-0">Nomor Kartu</p>
                                <p class="separator mb-0">:</p>
                            </div>
                            <div class="input-group medium-form mb-0">
                                <input type="text" class="form-control" id="card_number" name="card_number"
                                    placeholder="xxxx xxxx xxxx xxxx" maxlength="19"
                                    value="{{ old('card_no', $userdata->card_no) }}" required>
                            </div>
                        </div>

                        <div class="tab d-flex mb-3 ms-5">
                            <div class="d-flex">
                                <p class="medium-labels mb-0">Tipe Kartu</p>
                                <p class="separator mb-0">:</p>
                            </div>
                            <div class="input-group mb-0">
                                <select class="form-select" id="card_type" name="card_type" required>
                                    <option disabled hidden
                                        {{ old('card_type', $userdata->card_type) == '' ? 'selected' : '' }}>Pilih Tipe Kartu</option>
                                    <option value='1'
                                        {{ old('card_type', $userdata->card_type) == '1' ? 'selected' : '' }}>Kartu Kredit
                                    </option>
                                    <option value='2'
                                        {{ old('card_type', $userdata->card_type) == '2' ? 'selected' : '' }}>Kartu Debit
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex">
                        <div class="tab d-flex mb-3">
                            <div class="d-flex">
                                <p class="labels mb-0">Tanggal Kadaluarsa</p>
                                <p class="separator mb-0">:</p>
                            </div>
                            <div class="input-group tiny-form mb-0">
                                <input type="text" class="form-control datepicker" style="padding-left:0.8em;"
                                    id="card_expiration" placeholder="mm/yy" name="card_expiration"
                                    value="{{ old('card_expiration', $userdata->card_expiration) }}" required>
                            </div>
                        </div>

                        <div class="tab d-flex mb-3 ms-5">
                            <div class="d-flex">
                                <p class="short-labels mb-0">CVV</p>
                                <p class="separator mb-0">:</p>
                            </div>
                            <div class="input-group tiny-form mb-0">
                                <input type="text" class="form-control" id="card_cvv" name="card_cvv"
                                    placeholder="xxx" value="{{ old('card_cvv', $userdata->card_cvv) }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="mb-5"></div>

                </div>

                <hr>

                <div class="d-flex justify-content-end" style="margin-bottom:1rem;margin-top:5rem;">
                    <a type="button" href="{{ route('app.home.page') }}" class="btn btn-danger buttons me-4">Batal Perubahan</a>
                    <button type="submit" class="btn btn-primary buttons">Simpan Perubahan</button>
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
        card_no.addEventListener('input', function(e) {
            e.target.value = formatGroups(e.target.value);
        });

        // ✅ Format initial value when page loads
        card_no.value = formatGroups(card_no.value);
    </script>
@endsection
