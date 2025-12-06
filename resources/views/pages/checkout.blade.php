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
    <link rel="stylesheet" href="{{ asset('css/vendor/alertify/alertify.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/vendor/alertify/default.min.css') }}">

@endsection

@section('content')
    <div class="containers" style="padding-top:6rem">
        <div class="container">
            <main>
                <div class="py-5 text-center">
                    <h2>Checkout</h2>
                    <p class="lead">Lengkapi Formulir di Bawah Sesuai Identitas Anda</p>
                    <div class="alert alert-warning" role="alert">
                        <strong>Perhatian:</strong> Situs ini hanya untuk demonstrasi e-commerce. Jangan masukkan data
                        pribadi atau sensitif (mis. nomor kartu asli, alamat lengkap, KTP). Gunakan data contoh saja.
                    </div>
                </div>

                <div class="row g-5 d-flex flex-column flex-md-row flex-sm-flow-reverse justify-content-center w-100">
                    <div class="col-md-7 col-12 order-2 order-md-1">
                        <div class="d-flex justify-content-between mb-4">
                            <button class="btn btn-success me-2" id="btn-fill">Ambil Dari Profil</button>
                            <button class="btn btn-danger ms-2" id="btn-empty">Kosongkan Detail Pembayaran</button>
                        </div>
                        <hr>
                        <h4 class="mb-3">Alamat Pengiriman</h4>
                        <form class="needs-validation" method="POST" action="{{ route('app.checkout.checkout') }}"
                            novalidate>
                            @csrf
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <label for="firstname" class="form-label">Nama Depan</label>
                                    <input type="text" class="form-control" id="firstname" name="firstname"
                                        placeholder=""  required>
                                    <div class="invalid-feedback">
                                        Nama depan harus valid.
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <label for="lastname" class="form-label">Nama Belakang</label>
                                    <input type="text" class="form-control" id="lastname" placeholder=""
                                         name="lastname" required>
                                    <div class="invalid-feedback">
                                        Nama belakang harus valid.
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" placeholder="you@example.com"
                                         name="email" required>
                                    <div class="invalid-feedback">
                                        Mohon masukkan email yang valid untuk pembaruan pengiriman.
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label for="address" class="form-label">Alamat</label>
                                    <input type="text" class="form-control" id="address" placeholder="1234 Main St"
                                         name="address" required>
                                    <div class="invalid-feedback">
                                        Mohon masukkan alamat pengiriman anda.
                                    </div>
                                </div>

                                <div class="col-md-5">
                                    <label for="province" class="form-label">Provinsi</label>
                                    <input type="text" class="form-control" id="province" placeholder="Provinsi"
                                         name="province" required>
                                    <div class="invalid-feedback">
                                        Mohon masukkan provinsi pengiriman anda.
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label for="city" class="form-label">Kota / Kabupaten</label>
                                    <input type="text" class="form-control" id="city" placeholder="Kota / Kab."
                                         name="city" required>
                                    <div class="invalid-feedback">
                                        Mohon masukkan kota pengiriman anda.
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <label for="postcode" class="form-label">Kode Pos</label>
                                    <input type="text" class="form-control" id="postcode" placeholder="" name="postcode"
                                        required>
                                    <div class="invalid-feedback">
                                        Kode pos diperlukan.
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <h4 class="mb-3">Pembayaran</h4>

                            <div class="my-3">
                                <select class="form-select" id="card_type" name="card_type">
                                    <option disabled hidden
                                        {{ old('card_type', $userdata->card_type) == '' ? 'selected' : '' }}>Pilih Jenis
                                        Kartu</option>
                                    <option value='1'
                                        {{ old('card_type', $userdata->card_type) == '1' ? 'selected' : '' }}>Kartu Kredit
                                    </option>
                                    <option value='2'
                                        {{ old('card_type', $userdata->card_type) == '2' ? 'selected' : '' }}>Kartu Debit
                                    </option>
                                </select>
                            </div>



                            <div class="row gy-3">
                                <div class="col-md-6">
                                    <label for="card_name" class="form-label">Nama pada kartu</label>
                                    <input type="text" class="form-control" id="card_name" placeholder=""
                                        name="card_name"  required>
                                    <small class="text-body-secondary">Nama Lengkap sesuai kartu</small>
                                    <div class="invalid-feedback">
                                        Nama pada kartu diperlukan
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="card_number" class="form-label">Nomor Kartu</label>
                                    <input type="tel" inputmode="numeric" pattern="[0-9\s]{13,19}"
                                        class="form-control" id="card_number" placeholder="xxxx xxxx xxxx xxxx"
                                        name="card_number" 
                                        required>
                                    <div class="invalid-feedback">
                                        Nomor kartu invalid
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <label for="card_expiration" class="form-label">Masa Berlaku</label>
                                    <input type="text" class="form-control" id="card_expiration"
                                        style="padding-left:0.8em;" placeholder="mm/yy" name="card_expiration"
                                         required>
                                    <div class="invalid-feedback">
                                        Tanggal kadaluarsa diperlukan
                                    </div>
                                </div>
                                {{-- <div class="col-md-3">
                                    <label for="card_expiration" class="form-label">Masa Berlaku</label>
                                    <input type="text" class="form-control" id="card_expiration" placeholder="mm/yy" style="padding-left:0.8em;"
                                        name="card_expiration" required>
                                    <div class="invalid-feedback">
                                        Tanggal kadaluarsa diperlukan
                                    </div>
                                </div> --}}

                                <div class="col-md-3">
                                    <label for="card_cvv" class="form-label">CVV</label>
                                    <input type="text" class="form-control" id="card_cvv" placeholder="xxx"
                                        name="card_cvv" required>
                                    <div class="invalid-feedback">
                                        CVV diperlukan
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">
                            <button class="w-100 btn btn-primary btn-lg" type="submit">Checkout</button>
                        </form>
                    </div>
                    <div class="col-md-5 col-12 order-1 order-md-2">
                        <h4 class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-primary">Keranjang Anda</span>
                            <span class="badge bg-primary rounded-pill">{{ $totalItems }}</span>
                        </h4>
                        <ul class="list-group mb-3">
                            @foreach ($items as $item)
                                <li id="item_{{ $item['product_id'] }}"
                                    class="list-group-item d-flex justify-content-md-center lh-sm px-3 py-2">
                                    <div>
                                        <h6 class="mb-2">{{ $item['product_name'] }}</h6>
                                        <div class="d-flex col-8 align-items-center w-100">
                                            <label for="product_{{ $item['product_id'] }}" class="form-label pe-2">
                                                Qty:
                                            </label>
                                            <input type="number" min="0" step="1"
                                                class="form-control form-control-sm quantity-form"
                                                 $item['product_id'] }}">
                                            </input>
                                            <div>
                                                <i id="delete_{{ $item['product_id'] }}"
                                                    class="fa-solid fa-trash-can delete-item ms-3"
                                                    style="cursor: pointer;"></i>
                                                <div id="spinner_{{ $item['product_id'] }}"
                                                    class="spinner-border text-primary product-spinner d-none ms-3"
                                                    role="status">
                                                    <span class="visually-hidden">Loading...</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="text-body-secondary col-4 text-end"
                                        id="sub_total_{{ $item['product_id'] }}">
                                        Rp{{ number_format($item['total'], 2, ',', '.') }}
                                    </span>
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
                                <span>Total (IDR):</span>
                                <strong
                                    id="totalPrice">Rp{{ number_format($shoppingSession->total + floor($shoppingSession->total * 0.11), 2, ',', '.') }}</strong>
                            </li>
                        </ul>
                    </div>
                </div>
            </main>
        </div>
    @endsection
    @section('scripts')
        <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.7.0.js" integrity="sha256-JlqSTELeR4TLqP0OG9dxM7yDPqX1ox/HfgiSLBj8+kM="
            crossorigin="anonymous"></script>
        <script type="text/javascript">
            // Fields
            const data = @json($userdata->toArray()['default_payment_detail']);

            const firstname = document.getElementById("firstname");
            const lastname = document.getElementById("lastname");
            const email = document.getElementById("email");
            const address = document.getElementById("address");
            const province = document.getElementById("province");
            const city = document.getElementById("city");
            const postcode = document.getElementById("postcode");
            const card_type = document.getElementById("card_type");
            const card_name = document.getElementById("card_name");
            const card_number = document.getElementById("card_number");
            const card_expiration = document.getElementById("card_expiration");
            const card_cvv = document.getElementById("card_cvv");

            // Card Expiration Formatting
            card_expiration.addEventListener('input', function (e) {
                let v = e.target.value.replace(/\D/g, ''); // only digits

                if (v.length >= 2) {
                    e.target.value = v.slice(0,2) + '/' + v.slice(2,4);
                } else {
                    e.target.value = v;
                }
            });
            // Delete two chars if backspace on char length = 3
            card_expiration.addEventListener('keydown', function (e) {
                if (e.key === 'Backspace') {
                    const v = card_expiration.value;

                    // Length 3 means: "12/" or "1/2" depending on user edits
                    if (v.length === 3) {
                        e.preventDefault(); // stop normal backspace

                        // Remove last two chars
                        card_expiration.value = v.slice(0,1);  
                    }
                }
            });

            // card number formatter
            function formatGroups(value) {
                // remove non-digits
                value = value.replace(/\D/g, '');

                // group every 4 digits
                return value.replace(/(.{4})/g, '$1 ').trim();
            }

            // Format on input
            card_number.addEventListener('input', function(e) {
                e.target.value = formatGroups(e.target.value);
            });

            // Format initial value when page loads
            card_number.value = formatGroups(card_number.value);

            // Get from Profile Button
            document.getElementById("btn-fill").addEventListener("click", function(){
                firstname.value = data["firstname"] ?? "";
                lastname.value = data["lastname"] ?? "";
                email.value = data["email"] ?? "";
                address.value = data["address"] ?? "";
                province.value = data["province"] ?? "";
                city.value = data["city"] ?? "";
                postcode.value = data["postcode"] ?? "";
                card_type.value = data["card_type"] ?? "";
                card_name.value = data["card_name"] ?? "";
                card_number.value = data["card_number"] ?? "";
                card_expiration.value = data["card_expiration"] ?? "";
                card_cvv.value = data["card_cvv"] ?? "";
            })
            
            
            // Clear Button
            document.getElementById("btn-empty").addEventListener("click", function(){
                firstname.value = "";
                lastname.value = "";
                email.value = "";
                address.value = "";
                province.value = "";
                city.value = "";
                postcode.value = "";
                card_type.value = "";
                card_name.value = "";
                card_number.value = "";
                card_expiration.value = "";
                card_cvv.value = "";
            })

            // Other Functions
            const startProductLoading = (productId) => {
                const qtyInput = document.querySelector(`#product_${productId}`);
                const spinner = document.querySelector(`#spinner_${productId}`);
                const deleteItem = document.querySelector(`#delete_${productId}`);
                spinner.classList.remove('d-none');
                deleteItem.classList.add('d-none');
                qtyInput.setAttribute('disabled', 'disabled');
            }

            const endProductLoading = (productId) => {
                const qtyInput = document.querySelector(`#product_${productId}`);
                const spinner = document.querySelector(`#spinner_${productId}`);
                const deleteItem = document.querySelector(`#delete_${productId}`);
                spinner.classList.add('d-none');
                deleteItem.classList.remove('d-none');
                qtyInput.removeAttribute('disabled');
            }

            // Variable to store the total item
            let totalItems = {{ $totalItems }};

            // DOM
            const taxDOM = document.querySelector('#tax');
            const totalPriceDOM = document.querySelector('#totalPrice');
            // total before tax
            const totalbtDOM = document.querySelector('#totalbt');

            const productSpinners = document.querySelectorAll('.product-spinner');
            const deleteItems = document.querySelectorAll('.delete-item');
            const quantityForms = document.querySelectorAll('.quantity-form');

            // Check if the user want to delete the item
            deleteItems.forEach((deleteItem) => {
                deleteItem.addEventListener('click', async function() {
                    const productId = parseInt(deleteItem.id.split('_')[1])
                    startProductLoading(productId)
                    const res = await fetch("{{ route('app.cart.modify') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "Accept": "application/json",
                            "X-Requested-With": "XMLHttpRequest",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            product: [{
                                product_id: productId,
                                quantity: 0
                            }]
                        })
                    });
                    const response = await res.json();
                    if (response.status === 200) {
                        alertify.success(response.message);
                        const shoppingSession = response.data.shoppingSession;
                        document.getElementById(`item_${productId}`).remove();
                        // Change the total price
                        const tax = shoppingSession.total * 0.11;
                        totalbtDOM.innerHTML =
                            `Rp${new Intl.NumberFormat('id-ID').format(shoppingSession.total)},00`;
                        totalPriceDOM.innerHTML =
                            `Rp${new Intl.NumberFormat('id-ID').format(shoppingSession.total + tax)},00`;
                        // Change the badge
                        totalItems--;
                        document.querySelector('.badge').innerHTML = totalItems;
                        // Change the tax
                        taxDOM.innerHTML =
                            `Rp${new Intl.NumberFormat('id-ID').format(tax)},00`;
                    } else {
                        alertify.error(response.message);
                    }
                    endProductLoading(productId)
                })
            });

            // Check if the quantity is changed
            quantityForms.forEach((quantityForm) => {
                let oldValue;
                quantityForm.addEventListener('focus', async function() {
                    oldValue = parseInt(quantityForm.value);
                }, true);

                quantityForm.addEventListener('change', async function() {
                    const productId = parseInt(quantityForm.id.split('_')[1]);
                    startProductLoading(productId);
                    const oldValueLocal = oldValue
                    const res = await fetch("{{ route('app.cart.modify') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "Accept": "application/json",
                            "X-Requested-With": "XMLHttpRequest",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            product: [{
                                product_id: productId,
                                quantity: quantityForm.value
                            }]
                        })
                    });
                    const response = await res.json();
                    if (response.status === 200) {
                        const shoppingSession = response.data.shoppingSession;
                        const cartItem = response.data.success[0];
                        // Change DOM
                        alertify.success(response.message);
                        const tax = shoppingSession.total * 0.11;
                        const subTotal = cartItem.product_price * parseInt(cartItem.quantity);
                        const subTotalDOM = document.querySelector(`#sub_total_${cartItem.product_id}`);
                        subTotalDOM.innerHTML =
                            `Rp${new Intl.NumberFormat('id-ID').format(subTotal)},00`;
                        totalbtDOM.innerHTML =
                            `Rp${new Intl.NumberFormat('id-ID').format(shoppingSession.total)},00`;
                        totalPriceDOM.innerHTML =
                            `Rp${new Intl.NumberFormat('id-ID').format(shoppingSession.total + tax)},00`;
                        taxDOM.innerHTML =
                            `Rp${new Intl.NumberFormat('id-ID').format(tax)},00`;
                    } else {
                        // Change DOM
                        const cartItem = response.data.failed[0];
                        quantityForm.value = oldValueLocal;
                        alertify.error(`${response.message}: ${cartItem.error}`);
                    }
                    endProductLoading(productId);
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
            const cardNumber = document.querySelector('#card_number');
            cardNumber.oninput = (e) => {
                e.target.value = patternMatch({
                    input: e.target.value,
                    template: "xxxx xxxx xxxx xxxx",
                });
            };
            const cardExpiration = document.querySelector('#card_expiration');
            cardExpiration.oninput = (e) => {
                e.target.value = patternMatch({
                    input: e.target.value,
                    template: "xx/xx",
                });
            };
            const cardCvv = document.querySelector('#card_cvv');
            cardCvv.oninput = (e) => {
                e.target.value = patternMatch({
                    input: e.target.value,
                    template: "xxx",
                });
            };
            const postCode = document.querySelector('#postcode');
            postCode.oninput = (e) => {
                e.target.value = patternMatch({
                    input: e.target.value,
                    template: "xxxxx",
                });
            };
        </script>

    @endsection
