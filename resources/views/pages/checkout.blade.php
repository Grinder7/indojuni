@extends('layouts.app')
@section('title', 'Checkout - IndoJuni')
@section('styles')
    <style>
        .container {
            max-width: 960px;
        }

        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }

        .b-example-divider {
            width: 100%;
            height: 3rem;
            background-color: rgba(0, 0, 0, .1);
            border: solid rgba(0, 0, 0, .15);
            border-width: 1px 0;
            box-shadow: inset 0 .5em 1.5em rgba(0, 0, 0, .1), inset 0 .125em .5em rgba(0, 0, 0, .15);
        }

        .b-example-vr {
            flex-shrink: 0;
            width: 1.5rem;
            height: 100vh;
        }

        .bi {
            vertical-align: -.125em;
            fill: currentColor;
        }

        .nav-scroller {
            position: relative;
            z-index: 2;
            height: 2.75rem;
            overflow-y: hidden;
        }

        .nav-scroller .nav {
            display: flex;
            flex-wrap: nowrap;
            padding-bottom: 1rem;
            margin-top: -1px;
            overflow-x: auto;
            text-align: center;
            white-space: nowrap;
            -webkit-overflow-scrolling: touch;
        }

        .btn-bd-primary {
            --bd-violet-bg: #712cf9;
            --bd-violet-rgb: 112.520718, 44.062154, 249.437846;
            --bs-btn-font-weight: 600;
            --bs-btn-color: var(--bs-white);
            --bs-btn-bg: var(--bd-violet-bg);
            --bs-btn-border-color: var(--bd-violet-bg);
            --bs-btn-hover-color: var(--bs-white);
            --bs-btn-hover-bg: #6528e0;
            --bs-btn-hover-border-color: #6528e0;
            --bs-btn-focus-shadow-rgb: var(--bd-violet-rgb);
            --bs-btn-active-color: var(--bs-btn-hover-color);
            --bs-btn-active-bg: #5a23c8;
            --bs-btn-active-border-color: #5a23c8;
        }

        .bd-mode-toggle {
            z-index: 1500;
        }
    </style>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css" />
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/default.min.css" />
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/css/bootstrap-datepicker.min.css"
        integrity="sha512-34s5cpvaNG3BknEWSuOncX28vz97bRI59UnVtEEpFX536A7BtZSJHsDyFoCl8S7Dt2TPzcrCEoHBGeM4SUBDBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection

@section('content')
    <div class="containers" style="padding-top:6rem">
        <div class="container">
            <main>
                <div class="py-5 text-center">
                    <h2>Checkout</h2>
                    <p class="lead">Lengkapi Formulir di Bawah Sesuai Identitas Anda</p>
                </div>

                <div class="row g-5">
                    <div class="col-md-5 col-lg-4 order-md-last">
                        <h4 class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-primary">Keranjang Anda</span>
                            <span class="badge bg-primary rounded-pill">{{ $totalItems }}</span>
                        </h4>
                        <ul class="list-group mb-3">
                            @foreach ($items as $item)
                                <li class="list-group-item d-flex justify-content-between lh-sm">
                                    <div>
                                        <h6 class="my-0 pb-2">{{ $item['product_name'] }}</h6>
                                        <div class="d-inline-flex col-lg-9 align-items-center">
                                            <label for="product_{{ $item['product_id'] }}" class="form-label pe-2">
                                                Qty:
                                            </label>
                                            <input type="number" min="0" step="1"
                                                class="form-control form-control-sm quantity-form"
                                                value="{{ $item['quantity'] }}" id="product_{{ $item['product_id'] }}">
                                            <i class="fa-solid fa-trash-can ms-3 delete-item" style="cursor: pointer;"></i>
                                        </div>
                                    </div>
                                    <span class="text-body-secondary"
                                        id="sub_total_{{ $item['product_id'] }}">Rp{{ number_format($item['total'], 2, ',', '.') }}</span>
                                </li>
                            @endforeach

                            <li class="list-group-item d-flex justify-content-between">
                                <span>SubTotal: </span>
                                <strong
                                    id="totalbt">Rp{{ number_format(floor($shoppingSession->total), 2, ',', '.') }}</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Pajak (11%): </span>
                                <strong
                                    id="tax">Rp{{ number_format(floor($shoppingSession->total * 0.11), 2, ',', '.') }}</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Total (IDR)</span>
                                <strong
                                    id="totalPrice">Rp{{ number_format($shoppingSession->total + floor($shoppingSession->total * 0.11), 2, ',', '.') }}</strong>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-7 col-lg-8">
                        <h4 class="mb-3">Alamat Pembayaran</h4>
                        <form class="needs-validation" method="POST" action="{{ route('app.checkout.confirm') }}"
                            novalidate>
                            @csrf
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <label for="firstName" class="form-label">Nama Depan</label>
                                    <input type="text" class="form-control" id="firstName" name="firstname"
                                        placeholder="" value="{{ old('firstname') }}" required>
                                    <div class="invalid-feedback">
                                        Nama depan harus valid.
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <label for="lastName" class="form-label">Nama Belakang</label>
                                    <input type="text" class="form-control" id="lastName" placeholder=""
                                        value="{{ old('lastname') }}" name="lastname" required>
                                    <div class="invalid-feedback">
                                        Nama belakang harus valid.
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label for="email" class="form-label">Email <span
                                            class="text-body-secondary">(Opsional)</span></label>
                                    <input type="email" class="form-control" id="email" placeholder="you@example.com"
                                        value="{{ old('name') }}" name="email">
                                    <div class="invalid-feedback">
                                        Mohon masukkan email yang valid untuk pembaruan pengiriman.
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label for="address" class="form-label">Alamat</label>
                                    <input type="text" class="form-control" id="address" placeholder="1234 Main St"
                                        value="{{ old('address') }}" name="address" required>
                                    <div class="invalid-feedback">
                                        Mohon masukkan alamat pengiriman anda.
                                    </div>
                                </div>

                                <div class="col-md-9">
                                    <label for="address2" class="form-label">Alamat 2 <span
                                            class="text-body-secondary">(Opsional)</span></label>
                                    <input type="text" class="form-control" id="address2"
                                        placeholder="Apartment or suite" value="{{ old('address2') }}" name="address2">
                                </div>

                                <div class="col-md-3">
                                    <label for="zip" class="form-label">Kode Pos</label>
                                    <input type="text" class="form-control" id="zip" placeholder=""
                                        name="zip" value="{{ old('zip') }}"required>
                                    <div class="invalid-feedback">
                                        Kode pos diperlukan.
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <h4 class="mb-3">Pembayaran</h4>

                            <div class="my-3">
                                <div class="form-check">
                                    <input id="credit" name="payment_method" type="radio" class="form-check-input"
                                        value="kredit" checked required>
                                    <label class="form-check-label" for="credit">Kartu Kredit</label>
                                </div>
                                <div class="form-check">
                                    <input id="debit" name="payment_method" type="radio" class="form-check-input"
                                        value="debit" required>
                                    <label class="form-check-label" for="debit">Kartu Debit</label>
                                </div>
                            </div>

                            <div class="row gy-3">
                                <div class="col-md-6">
                                    <label for="cc-name" class="form-label">Nama pada kartu</label>
                                    <input type="text" class="form-control" id="cc-name" placeholder=""
                                        name="card_name" value="{{ old('card_name') }}" required>
                                    <small class="text-body-secondary">Nama Lengkap sesuai kartu</small>
                                    <div class="invalid-feedback">
                                        Nama pada kartu diperlukan
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="cc-number" class="form-label">Nomor Kartu</label>
                                    <input type="tel" inputmode="numeric" pattern="[0-9\s]{13,19}"
                                        class="form-control" id="cc-number" placeholder="xxxx xxxx xxxx xxxx"
                                        name="card_number" value="{{ old('card_number') }}" required>
                                    <div class="invalid-feedback">
                                        Nomor kartu invalid
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <label for="cc-expiration" class="form-label">Masa Berlaku</label>
                                    <input type="text" class="form-control datepicker" id="cc-expiration"
                                        placeholder="mm/yy" name="card_expiration" value="{{ old('card_expiration') }}"
                                        required>
                                    <div class="invalid-feedback">
                                        Tanggal kadaluarsa diperlukan
                                    </div>
                                </div>
                                {{-- <div class="col-md-3">
                                    <label for="cc-expiration" class="form-label">Masa Berlaku</label>
                                    <input type="text" class="form-control" id="cc-expiration" placeholder="mm/yy"
                                        name="card_expiration"value="{{ old('card_expiration') }}" required>
                                    <div class="invalid-feedback">
                                        Tanggal kadaluarsa diperlukan
                                    </div>
                                </div> --}}

                                <div class="col-md-3">
                                    <label for="cc-cvv" class="form-label">CVV</label>
                                    <input type="text" class="form-control" id="cc-cvv" placeholder="xxx"
                                        name="card_cvv" value="{{ old('card_cvv') }}"required>
                                    <div class="invalid-feedback">
                                        CVV diperlukan
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">
                            <button class="w-100 btn btn-primary btn-lg" type="submit">Lanjutkan Pembayaran</button>
                        </form>
                    </div>
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

            // Example starter JavaScript for disabling form submissions if there are invalid fields
            (() => {
                'use strict'

                // Fetch all the forms we want to apply custom Bootstrap validation styles to
                const forms = document.querySelectorAll('.needs-validation')

                // Loop over them and prevent submission
                Array.from(forms).forEach(form => {
                    form.addEventListener('submit', event => {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }

                        form.classList.add('was-validated')
                    }, false)
                })
            })()
            // Variable to store the total item
            let totalItems = {{ $totalItems }};

            // DOM
            const taxDOM = document.querySelector('#tax');
            const totalPriceDOM = document.querySelector('#totalPrice');
            // total before tax
            const totalbtDOM = document.querySelector('#totalbt');

            // Check if the quantity is changed
            const quantityForms = document.querySelectorAll('.quantity-form');
            quantityForms.forEach((quantityForm) => {
                quantityForm.addEventListener('change', async function() {
                    const res = await fetch("{{ route('app.checkout.qtyupdate') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "Accept": "application/json",
                            "X-Requested-With": "XMLHttpRequest",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            product_id: quantityForm.id.split('_')[1],
                            quantity: quantityForm.value
                        })
                    });
                    const data = await res.json();
                    if (data.success == true) {
                        // Change DOM
                        alertify.success(data.message);
                        const tax = data.total * 0.11;
                        const subTotal = document.querySelector(`#sub_total_${data.product_id}`);
                        subTotal.innerHTML =
                            `Rp${new Intl.NumberFormat('id-ID').format(data.subtotal)},00`;
                        totalbtDOM.innerHTML =
                            `Rp${new Intl.NumberFormat('id-ID').format(data.total)},00`;
                        totalPriceDOM.innerHTML =
                            `Rp${new Intl.NumberFormat('id-ID').format(data.total+tax)},00`;
                        taxDOM.innerHTML =
                            `Rp${new Intl.NumberFormat('id-ID').format(tax)},00`;
                    } else {
                        // Change DOM
                        quantityForm.value = data.quantity;
                        alertify.error(data.message);
                    }
                })
            });
            // Check if the user want to delete the item
            const deleteItems = document.querySelectorAll('.delete-item');
            deleteItems.forEach((deleteItem) => {
                deleteItem.addEventListener('click', async function() {
                    const res = await fetch("{{ route('app.checkout.deleteitem') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "Accept": "application/json",
                            "X-Requested-With": "XMLHttpRequest",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            product_id: deleteItem.parentElement.querySelector(
                                '.quantity-form').id.split('_')[1]
                        })
                    });
                    const data = await res.json();
                    if (data.success == true) {
                        alertify.success(data.message);
                        // Change the DOM
                        deleteItem.parentElement.parentElement.parentElement.remove();
                        // Change the total price
                        const tax = data.total * 0.11;
                        totalbtDOM.innerHTML =
                            `Rp${new Intl.NumberFormat('id-ID').format(data.total)},00`;
                        totalPriceDOM.innerHTML =
                            `Rp${new Intl.NumberFormat('id-ID').format(data.total+tax)},00`;
                        // Change the badge
                        totalItems--;
                        document.querySelector('.badge').innerHTML = totalItems;
                        // Change the tax
                        taxDOM.innerHTML =
                            `Rp${new Intl.NumberFormat('id-ID').format(tax)},00`;
                    } else {
                        alertify.error(data.message);
                    }
                })
            });

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
            const cardNumber = document.querySelector('#cc-number');
            cardNumber.oninput = (e) => {
                e.target.value = patternMatch({
                    input: e.target.value,
                    template: "xxxx xxxx xxxx xxxx",
                });
            };
            const cardExpiration = document.querySelector('#cc-expiration');
            cardExpiration.oninput = (e) => {
                e.target.value = patternMatch({
                    input: e.target.value,
                    template: "xx/xx",
                });
            };
            const cardCvv = document.querySelector('#cc-cvv');
            cardCvv.oninput = (e) => {
                e.target.value = patternMatch({
                    input: e.target.value,
                    template: "xxx",
                });
            };
            const zip = document.querySelector('#zip');
            zip.oninput = (e) => {
                e.target.value = patternMatch({
                    input: e.target.value,
                    template: "xxxxx",
                });
            };
        </script>
    @endsection
