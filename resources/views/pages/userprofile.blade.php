@extends('layouts.app')
@section('title', 'Profil - IndoJuni')
@section('styles')
    <link rel="stylesheet" href="{{ asset('css/vendor/alertify/alertify.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/vendor/alertify/default.min.css') }}">
    <style>
        .buttons {
            width: 10rem;
        }

        .input-row {
            display: flex;
        }

        .tab {
            margin-left: 1rem;
            padding-right: 0.5rem;
        }

        .labels {
            width: 14rem;
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
            max-width: 4em;
            width: 100%;
        }

        .small-form {
            max-width: 10em;
            width: 100%;
        }

        .medium-form {
            max-width: 16em;
            width: 100%;
        }

        .long-form {
            max-width: 60rem;
            width: 100%;
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

        .normal-view {
            display: block;
        }

        .mobile-view {
            display: none;
        }


        /* @media (max-width: 1150px) {
                                                                                                                                                                            .normal-view {
                                                                                                                                                                                display: none;
                                                                                                                                                                            }

                                                                                                                                                                            .mobile-view {
                                                                                                                                                                                display: block;
                                                                                                                                                                            }
                                                                                                                                                                        } */

        @media (max-width: 700px) {
            .separator {
                display: none;
            }

            .input-row {
                display: block;
            }

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

                    <div class="tab input-row mb-3">
                        <div class="d-flex">
                            <p class="labels mb-0">Username</p>
                            <p class="separator mb-0">:</p>
                        </div>
                        <div class="input-group long-form mb-0">
                            <input type="text" class="form-control" id="username"
                                value="{{ old('username', $userdata->username) }}" disabled>
                        </div>
                    </div>

                    <div class="tab input-row mb-3">
                        <div class="d-flex">
                            <p class="labels mb-0">Nama</p>
                            <p class="separator mb-0">:</p>
                        </div>
                        <div class="input-group long-form mb-0">
                            <input type="text" class="form-control" name="firstname" id="firstname"
                                placeholder="Nama Depan" value="{{ old('firstname', $userdata->firstname) }}" required>
                            <input type="text" class="form-control" name="lastname" id="lastname"
                                placeholder="Nama Belakang" value="{{ old('lastname', $userdata->lastname) }}" required>
                        </div>
                    </div>

                    <div class="tab input-row mb-3">
                        <div class="d-flex">
                            <p class="labels mb-0">Email</p>
                            <p class="separator mb-0">:</p>
                        </div>
                        <div class="input-group medium-form mb-0">
                            <input type="email" class="form-control" id="email" name="email"
                                placeholder="email@contoh.com" value="{{ old('email', $userdata->email) }}" required>
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

                    <div class="tab input-row mb-3">
                        <div class="d-flex">
                            <p class="labels mb-0">Alamat</p>
                            <p class="separator mb-0">:</p>
                        </div>
                        <div class="input-group long-form mb-0">
                            <input type="text" class="form-control" id="address" name="address" placeholder="Alamat"
                                value="{{ old('address', $userdata->address) }}" required>
                        </div>
                    </div>

                    <div class="tab input-row mb-3">
                        <div class="d-flex">
                            <p class="labels mb-0">Kota</p>
                            <p class="separator mb-0">:</p>
                        </div>
                        <div class="input-group long-form mb-0">
                            <input type="text" class="form-control" id="city" name="city"
                                placeholder="Kota / Kab." value="{{ old('city', $userdata->city) }}" required>
                        </div>
                    </div>
                    <div class="tab input-row mb-3">
                        <div class="d-flex">
                            <p class="labels mb-0">Provinsi</p>
                            <p class="separator mb-0">:</p>
                        </div>
                        <div class="input-group long-form mb-0">
                            <input type="text" class="form-control" id="province" name="province" placeholder="Provinsi"
                                value="{{ old('province', $userdata->province) }}" required>
                        </div>
                    </div>

                    <div class="tab input-row mb-3">
                        <div class="d-flex">
                            <p class="labels mb-0">Kode Pos</p>
                            <p class="separator mb-0">:</p>
                        </div>
                        <div class="input-group long-form mb-0">
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
                    <div class="tab input-row mb-3">
                        <div class="d-flex">
                            <p class="labels mb-0">Tipe Kartu</p>
                            <p class="separator mb-0">:</p>
                        </div>
                        <div class="input-group mb-0">
                            <select class="form-select" id="card_type" name="card_type" required>
                                <option disabled hidden
                                    {{ old('card_type', $userdata->card_type) == '' ? 'selected' : '' }}>Pilih Tipe
                                    Kartu</option>
                                <option value='1'
                                    {{ old('card_type', $userdata->card_type) == '1' ? 'selected' : '' }}>Kartu
                                    Kredit
                                </option>
                                <option value='2'
                                    {{ old('card_type', $userdata->card_type) == '2' ? 'selected' : '' }}>Kartu
                                    Debit
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="tab input-row mb-3">
                        <div class="d-flex">
                            <p class="labels mb-0">Nama Pemegang Kartu</p>
                            <p class="separator mb-0">:</p>
                        </div>
                        <div class="input-group long-form mb-0">
                            <input type="text" class="form-control" id="card_name" name="card_name"
                                placeholder="Nama pemegang kartu" value="{{ old('card_name', $userdata->card_name) }}"
                                required>
                        </div>
                    </div>
                    <div class="tab input-row mb-3">
                        <div class="d-flex">
                            <p class="labels mb-0">Nomor Kartu</p>
                            <p class="separator mb-0">:</p>
                        </div>
                        <div class="input-group long-form mb-0">
                            <input type="text" class="form-control card_number" name="card_number"
                                placeholder="xxxx xxxx xxxx xxxx" maxlength="19"
                                value="{{ old('card_number', $userdata->card_number) }}" required>
                        </div>
                    </div>
                    <div class="tab input-row mb-3">
                        <div class="d-flex">
                            <p class="labels mb-0">Tanggal Kadaluarsa</p>
                            <p class="separator mb-0">:</p>
                        </div>
                        <div class="input-group long-form mb-0">
                            <input type="text" class="form-control card_expiration" style="padding-left:0.8em;"
                                placeholder="mm/yy" name="card_expiration"
                                value="{{ old('card_expiration', $userdata->card_expiration) }}" required>
                        </div>
                    </div>
                    <div class="tab input-row mb-3">
                        <div class="d-flex">
                            <p class="labels mb-0">CVV</p>
                            <p class="separator mb-0">:</p>
                        </div>
                        <div class="input-group long-form mb-0">
                            <input type="text" class="form-control" id="card_cvv" name="card_cvv" placeholder="xxx"
                                value="{{ old('card_cvv', $userdata->card_cvv) }}" required>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="d-flex justify-content-end tab mt-3">
                    <a type="button" href="{{ route('app.home.page') }}"
                        class="btn btn-danger buttons me-4">Batalkan</a>
                    <button type="submit" class="btn btn-primary buttons">Simpan</button>
                </div>
            </form>

        </main>
    </div>
@endsection

@section('scripts')
    <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.js" integrity="sha256-JlqSTELeR4TLqP0OG9dxM7yDPqX1ox/HfgiSLBj8+kM="
        crossorigin="anonymous"></script>

    <script type="text/javascript">
        // Card Expiration Formatting
        const card_expiration = document.querySelectorAll('.card_expiration');

        for (let i = 0; i < card_expiration.length; i++) {
            card_expiration[i].addEventListener('input', function(e) {
                let v = e.target.value.replace(/\D/g, ''); // only digits

                if (v.length >= 2) {
                    e.target.value = v.slice(0, 2) + '/' + v.slice(2, 4);
                } else {
                    e.target.value = v;
                }
            });

            // Delete two chars if backspace on char length = 3
            card_expiration[i].addEventListener('keydown', function(e) {
                if (e.key === 'Backspace') {
                    const v = card_expiration[i].value;

                    // Length 3 means: "12/" or "1/2" depending on user edits
                    if (v.length === 3) {
                        e.preventDefault(); // stop normal backspace

                        // Remove last two chars
                        card_expiration[i].value = v.slice(0, 1);
                    }
                }
            });
        }

        // Card Number Formatting
        const card_number = document.querySelectorAll('.card_number');

        function formatGroups(value) {
            // remove non-digits
            value = value.replace(/\D/g, '');

            // group every 4 digits
            return value.replace(/(.{4})/g, '$1 ').trim();
        }

        for (let i = 0; i < card_number.length; i++) {
            card_number[i].addEventListener('input', function(e) {
                e.target.value = formatGroups(e.target.value);
            });
            // Format initial value when page loads
            card_number[i].value = formatGroups(card_number[i].value);
        }

        function patternMatch({
            input,
            template
        }) {
            try {
                let j = 0;
                let plaintext = "";
                let countj = 0;
                while (j < template.length) {
                    if (countj > input.length - 1) {
                        template = template.substring(0, j);
                        break;
                    }

                    if (template[j] == input[j]) {
                        j++;
                        countj++;
                        continue;
                    }

                    if (template[j] == "x") {
                        template =
                            template.substring(0, j) + input[countj] + template.substring(j + 1);
                        plaintext = plaintext + input[countj];
                        countj++;
                    }
                    j++;
                }

                return template;
            } catch {
                return "";
            }
        }
        document.getElementById("postcode").oninput = (e) => {
            e.target.value = patternMatch({
                input: e.target.value,
                template: "xxxxx",
            });
        };

        document.getElementById("card_cvv").oninput = (e) => {
            e.target.value = patternMatch({
                input: e.target.value,
                template: "xxx",
            });
        };
    </script>
@endsection
