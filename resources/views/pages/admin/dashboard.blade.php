@extends('layouts.adm')

@section('content')
    <div class="modal fade modal-dialog-scrollable modal-xl" id="mainBox" tabindex="-1" aria-labelledby="mainBoxLabel"
        aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="form_inp" action="{{ route('adm.edit') }}" method="POST" enctype="multipart/form-data"
                        class="d-flex gap-4">
                        @csrf
                        <div id="imgBox" class="h-full w-1/2"></div>
                        <div class="d-flex flex-column w-1/2">
                            <input type="hidden" value=0 id="id-inp" name="id">
                            <div class="mb-3">
                                <label for="name-inp" class="form-label">Product Name</label>
                                <input class="form-control" id="name-inp" name="name" @required(true)>
                            </div>

                            <div class="mb-3">
                                <label for="desc-inp" class="form-label">Product Description</label>
                                <textarea class="form-control" id="desc-inp" rows="5" name="description" @required(true)></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="stock-inp" class="form-label">Product Stock</label>
                                <input type="number" class="form-control" id="stock-inp" name="stock" min="0"
                                    @required(true)>
                            </div>

                            <div class="mb-3">
                                <label for="price-inp" class="form-label">Product Price</label>
                                <input type="number" class="form-control" id="price-inp" name="price" @required(true)>
                            </div>

                            <div class="mb-3">
                                <label for="img-inp" class="form-label">Image</label>
                                <input type="file" name="img" class="form-control" id="img-inp"
                                    accept="image/jpeg, image/png" onchange="fileProcess(event)">
                            </div>
                        </div>
                        {{-- <div class="position-absolute bottom-0 end-0" style="transform: translate(-90%, -75%);">
                            <button class="btn btn-outline-success btn-lg align-right" id="confirmButton"
                                type="submit">Confirm</button>
                        </div> --}}
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-outline-success btn-lg align-right" id="confirmButton"
                        type="submit">Confirm</button>
                </div>
            </div>

        </div>
    </div>

    <main class="container-fluid">
        <div class="album bg-light">
            <button class="btn btn-primary open-modal my-3" value="0" data-bs-modalType="Create"
                data-bs-target="#mainBox" data-bs-toggle="modal">Create product</button>
            <div class="mb-3">
                @include('components.catalogue-filters', [
                    'filters' => $productFilters,
                    'route' => route('app.catalogue.page'),
                ])
            </div>
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
                                <img src="{{ $product->img_path }}" alt="productimg" height="200"
                                    class="img-src object-fit-contain">
                            @else
                                <svg class="bd-placeholder-img card-img-top" width="100%" height="200"
                                    xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail"
                                    preserveAspectRatio="xMidYMid slice" focusable="false">
                                    <title>Placeholder</title>
                                    <rect width="100%" height="100%" fill="#55595c" /><text text-anchor="middle" x="50%"
                                        y="50%" fill="#eceeef" dy=".3em">Image</text>
                                </svg>
                            @endif
                            <div class="card-body d-flex flex-column">
                                <p class="card-text fw-bolder name-src">{{ $product->name }}</p>
                                <p class="card-text text-body-secondary desc-src mb-4">
                                    {{ $product->description }}</p>
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
                                                class="btn btn-sm btn-outline-secondary open-modal editButton"
                                                value="{{ $product->id }}" data-bs-toggle="modal"
                                                data-bs-target="#mainBox" data-bs-modalType="Edit">Edit</button>
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
        </div>
    </main>
@endsection

@section('script')
    <script>
        const datas = @json($products, JSON_UNESCAPED_UNICODE).data || [];
        const formRef = document.getElementById("form_inp");
        const editButton = document.getElementsByClassName("editButton");
        const delButton = document.getElementsByClassName("delButton");
        const confirmButton = document.getElementById("confirmButton");
        const mainBox = document.getElementById("mainBox");
        const image = document.getElementById("image-inp");
        const imgBox = document.getElementById("imgBox");
        const svgPlaceholder = document.createElementNS("http://www.w3.org/2000/svg", "svg");
        svgPlaceholder.setAttribute("class", "bd-placeholder-img card-img-top");
        svgPlaceholder.setAttribute("width", "100%");
        svgPlaceholder.setAttribute("height", "200");
        svgPlaceholder.setAttribute("xmlns", "http://www.w3.org/2000/svg");
        svgPlaceholder.setAttribute("role", "img");
        svgPlaceholder.setAttribute("aria-label", "Placeholder: Thumbnail");
        svgPlaceholder.setAttribute("preserveAspectRatio", "xMidYMid slice");
        svgPlaceholder.setAttribute("focusable", "false");
        const title = document.createElementNS("http://www.w3.org/2000/svg", "title");
        title.textContent = "Placeholder";
        const rect = document.createElementNS("http://www.w3.org/2000/svg", "rect");
        rect.setAttribute("width", "100%");
        rect.setAttribute("height", "100%");
        rect.setAttribute("fill", "#55595c");
        const textSvg = document.createElementNS("http://www.w3.org/2000/svg", "text");
        textSvg.setAttribute("text-anchor", "middle");
        textSvg.setAttribute("x", "50%");
        textSvg.setAttribute("y", "50%");
        textSvg.setAttribute("fill", "#eceeef");
        textSvg.setAttribute("dy", ".3em");
        textSvg.textContent = "Image";
        svgPlaceholder.appendChild(title);
        svgPlaceholder.appendChild(rect);
        svgPlaceholder.appendChild(textSvg);

        if (mainBox) {
            mainBox.addEventListener('show.bs.modal', e => {
                const button = e.relatedTarget
                const title = button.getAttribute('data-bs-modalType')
                if (title == 'Create') {
                    formRef.reset();
                }
                const modalTitle = mainBox.querySelector('.modal-title')
                modalTitle.textContent = `${title} Product`
                const data = datas.find(item => item.id == button.value) || {};
                if (data.img) {
                    const imgDOM = document.createElement("img");
                    imgDOM.src = data.img_path;
                    imgDOM.alt = "cover";
                    imgDOM.style.objectFit = "cover";
                    imgBox.innerHTML = '';
                    imgBox.appendChild(imgDOM);
                } else {
                    imgBox.innerHTML = '';
                    imgBox.appendChild(svgPlaceholder);
                }
            })
        }

        for (let i = 0; i < editButton.length; i++) {
            editButton[i].addEventListener("click", () => {
                const data = datas.find(item => item.id == editButton[i].value) || {};
                document.getElementById("name-inp").value = data.name || '';
                document.getElementById("desc-inp").value = data.description || '';
                document.getElementById("stock-inp").value = data.stock || '';
                document.getElementById("price-inp").value = data.price || '';
                document.getElementById("id-inp").value = editButton[i].value;
            });
        }

        function fileProcess(event) {
            const input = event.target;
            const file = input.files[0];
            const reader = new FileReader();
            reader.onload = function(e) {
                const imgSrc = e.target.result;
                const imagePreview = document.createElement("img");
                imagePreview.src = imgSrc;
                imagePreview.id = "display-inp";
                imagePreview.style.objectFit = "cover";
                imgBox.innerHTML = "";
                imgBox.appendChild(imagePreview);
            };
            reader.readAsDataURL(file);
        }

        async function deleteProduct(product_id) {
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
                        const response = await deleteProduct(data_id);
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
@endsection
