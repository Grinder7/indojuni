@extends('layouts.adm')

@section('content')
    <div class="card bg-light border-secondary align-self-middle p-3" id="mainBox">
        <div class="d-flex justify-content-between mb-5">
            <h1 id="randomText">Create New Data</h1>
            <a class="btn btn-outline-danger align-right squareButton hideSidebar">X</a>
        </div>
        <form id="form_inp" action="{{ route('adm.edit') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="d-flex">
                <div class="d-flex card bg-secondary border-secondary p-1" id="imgBox">
                    <img src="" class="centerItem" id="display-inp" alt="cover" style="object-fit: cover">
                </div>

                <div class="w-50 ms-5">
                    <div class="input-group mb-3">
                        <span class="input-group-text w-25">Name</span>
                        <input type="text" class="form-control" id="name-inp" value="" name="name"
                            @required(true)>
                    </div>

                    <div class="input-group mb-3">
                        <span class="input-group-text">Description</span>
                        <textarea type="text" class="form-control" id="desc-inp" rows="5" name="description" @required(true)></textarea>
                    </div>

                    <div class="input-group mb-3">
                        <span class="input-group-text">Stock</span>
                        <input type="number" class="form-control" id="stock-inp" value="" name="stock"
                            @required(true)>
                    </div>

                    <div class="input-group mb-3">
                        <span class="input-group-text">Price</span>
                        <input type="number" class="form-control" id="price-inp" value="" name="price"
                            @required(true)>
                    </div>

                    <div class="input-group mb-3">
                        <span class="input-group-text">Image</span>
                        <input type="file" name="img" class="form-control" id="img-inp"
                            accept="image/jpeg, image/png" onchange="fileProcess(event)">
                    </div>

                    <input type="hidden" value=0 id="id-inp" name="id">

                </div>
            </div>

            <div class="position-absolute bottom-0 end-0" style="transform: translate(-90%, -75%);">
                <button class="btn btn-outline-success btn-lg align-right" id="confirmButton"
                    type="submit">Confirm</button>
            </div>

        </form>
    </div>
    <header>
        <div class="navbar navbar-dark bg-dark shadow-sm">
            <div class="container">
                <a href="#" class="navbar-brand d-flex align-items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                        class="bi bi-person-circle" viewBox="0 0 16 16">
                        <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" />
                        <path fill-rule="evenodd"
                            d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z" />
                    </svg>
                    <strong>&nbsp;Admin Page</strong>
                </a>
                <li><a href="/" class="text-white">Log Out</a></li>
            </div>
        </div>
    </header>

    <main>
        <section class="container py-5 text-center">
            <div class="row py-lg-5">
                <div class="col-lg-6 col-md-8 mx-auto">
                    <p>
                        <button class="btn btn-primary showSidebar my-2" value="0">Add new data</button>
                    </p>
                </div>
            </div>
        </section>

        <div class="container-fluid">
            <div class="album bg-light py-5">
                @include('components.catalogue-filters', [
                    'filters' => $productFilters,
                    'route' => route('app.catalogue.page'),
                ])
                <div class="row p-3">
                    <div class="col">
                        {{ $products->appends(request()->query())->links() }}
                    </div>
                </div>
                <div class="row">
                    @foreach ($products as $product)
                        <div class="col-12 col-sm-4 col-md-3 col-xxl-2 d-flex align-items-stretch mb-3">
                            <div class="card shadow-sm" style="width: 100%">
                                @if ($product->img)
                                    <img src="{{ $product->img_path }}" alt="productimg"
                                        height="200"style="object-fit: contain">
                                @else
                                    <svg class="bd-placeholder-img card-img-top" width="100%" height="200"
                                        xmlns="http://www.w3.org/2000/svg" role="img"
                                        aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice"
                                        focusable="false">
                                        <title>Placeholder</title>
                                        <rect width="100%" height="100%" fill="#55595c" /><text text-anchor="middle"
                                            x="50%" y="50%" fill="#eceeef" dy=".3em">Image</text>
                                    </svg>
                                @endif
                                <div class="card-body d-flex flex-column">
                                    <p class="card-text fw-bolder name-src">{{ $product->name }}</p>
                                    <p class="card-text text-body-secondary desc-src mb-4">{{ $product->description }}</p>
                                    <div class="d-flex justify-content-between align-items-center mt-auto">
                                        <small
                                            class="text-body-secondary price-src">Rp{{ number_format($product->price, 2, ',', '.') }}</small>
                                        <div class="d-flex justify-content-between align-items-center mt-auto">
                                            <div>
                                                <small class="text-muted stock-src">{{ $product->stock }}</small>
                                                <span class="text-muted me-1">item(s)</span>
                                            </div>
                                            <div class="btn-group">
                                                <button type="button"
                                                    class="btn btn-sm btn-outline-secondary showSidebar editButton"
                                                    value="{{ $product->id }}">Edit</button>
                                                <button type="button" class="btn btn-sm btn-outline-secondary delButton"
                                                    value="{{ $product->id }}">Delete</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="row p-3">
                    <div class="col">
                        {{ $products->links() }}
                    </div>
                </div>
                {{-- <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                    @if ($products)
                        @foreach ($products as $product)
                            <div class="col-lg-4 d-flex align-items-stretch mb-3">
                                <div class="card shadow-sm">
                                    <img class="img-src" src="{{ asset('images/products/' . $product->img) }}"
                                        alt="cover" height="225" style="object-fit: contain"
                                        data-imgsrc="{{ asset($product->img) }}">

                                    <div class="card-body d-flex flex-column">
                                        <b>
                                            <a class="card-text name-src text-black"
                                                style="text-decoration: none;">{{ $product->name }}</a>
                                            <br>
                                            <a class="text-black" style="text-decoration: none;">( Rp</a>
                                            <a class="card-text price-src price-src text-black"
                                                style="text-decoration: none;">{{ number_format($product->price, 0, ',', '.') }}</a>
                                            <a class="text-black" style="text-decoration: none;">)</a>
                                        </b>
                                        <br>
                                        <p class="card-text desc-src">{{ $product->description }}</p>
                                        <div class="d-flex justify-content-between align-items-center mt-auto">
                                            <div class="btn-group">
                                                <button type="button"
                                                    class="btn btn-sm btn-outline-secondary showSidebar editButton"
                                                    value="{{ $product->id }}">Edit</button>
                                                <button type="button" class="btn btn-sm btn-outline-secondary delButton"
                                                    value="{{ $product->id }}">Delete</button>
                                            </div>
                                            <div>
                                                <small class="text-muted stock-src">{{ $product->stock }}</small>
                                                <span class="text-muted">item(s)</span>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        @endforeach
                    @endif
                </div> --}}
            </div>
        </div>
    </main>
    <script>
        let buttonOpen = document.getElementsByClassName("showSidebar");
        let buttonClose = document.getElementsByClassName("hideSidebar");

        let editButton = document.getElementsByClassName("editButton");
        let delButton = document.getElementsByClassName("delButton");

        let confirmButton = document.getElementById("confirmButton");

        let text = document.getElementById("randomText");

        let image = document.getElementById("image-inp");

        for (let i = 0; i < buttonOpen.length; i++) {
            buttonOpen[i].addEventListener("click", () => {
                mainBox.style.visibility = "visible";
                if (buttonOpen[i].value != 0) {
                    text.innerHTML = "Edit Data";
                } else {
                    text.innerHTML = "Create New Data";
                    input.clearInputValue();
                }
            });
        }

        for (let i = 0; i < buttonClose.length; i++) {
            buttonClose[i].addEventListener("click", () => {
                mainBox.style.visibility = "hidden";
                document.querySelector('#img-inp').value = '';
            });
        }

        for (let i = 0; i < editButton.length; i++) {
            editButton[i].addEventListener("click", () => {
                const editParrent = editButton[i].parentElement.parentElement.parentElement.parentElement;
                document.getElementById("name-inp").value = editParrent.querySelector('.name-src').innerHTML;
                document.getElementById("desc-inp").value = editParrent.querySelector('.desc-src').innerHTML;
                document.getElementById("stock-inp").value = editParrent.querySelector('.stock-src').innerHTML;
                document.getElementById("price-inp").value = editParrent.querySelector('.price-src').innerHTML
                    .match(/[0-9]/g).join("");
                document.getElementById("display-inp").src = editParrent.querySelector('.img-src').src;
                document.getElementById("id-inp").value = editButton[i].value;
            });
        }

        function fileProcess(event) {
            const imagePreview = document.getElementById("display-inp");
            const input = event.target;
            const image = URL.createObjectURL(input.files[0]);
            imagePreview.src = image;
            // document.getElementById("img-temp").value = imagePreview.src;
            // console.log(document.getElementById("img-inp").value)
        }

        async function fetchData(product_id) {
            const res = await fetch("{{ route('adm.delete') }}", {
                    method: 'POST',
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                        "X-Requested-With": "XMLHttpRequest",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    credentials: "same-origin",
                    body: JSON.stringify({
                        'product_id': product_id,
                    })
                })
                .then((response) => response.json());
            return res;
        };


        for (let i = 0; i < delButton.length; i++) {
            delButton[i].addEventListener("click", async function() {
                let data_id = delButton[i].value;
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        const response = await fetchData(data_id);
                        if (response.success == true) {
                            Swal.fire(
                                    'Deleted!',
                                    'Your file has been deleted.',
                                    'success'
                                )
                                .then(() => {
                                    location.reload();
                                });
                        } else {
                            console.log(response);
                            Swal.fire(
                                'Error',
                                'error',
                                'error'
                            )
                        }
                    }
                })
            })
        }
        const input = {
            name: document.getElementById("name-inp"),
            desc: document.getElementById("desc-inp"),
            stock: document.getElementById("stock-inp"),
            price: document.getElementById("price-inp"),
            display: document.getElementById("display-inp"),
            id: document.getElementById("id-inp"),
            clearInputValue: function() {
                this.name.value = "";
                this.desc.value = "";
                this.stock.value = "";
                this.price.value = "";
                this.display.src = "";
                this.id.value = "";
            },
        };

        confirmButton.addEventListener("click", () => {
            const inputData = {
                name: input.name.value,
                desc: input.desc.value,
                stock: input.stock.value,
                price: input.price.value,
                display: image.src,
            }
            editData(inputData);
        });
    </script>
    @include('partials.app-footer')
    @include('partials.sweet-alert')
