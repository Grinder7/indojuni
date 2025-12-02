@extends('layouts.adm')
@section('title', 'Admin Dashboard')

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
                    <form id="form-inp" action="{{ route('adm.edit') }}" method="POST" enctype="multipart/form-data"
                        class="d-flex w-100 gap-4">
                        @csrf
                        <div id="imgBox"
                            style="width:40%; max-height:600px; display:flex;place-items:center;overflow:hidden;  background: #f8f8f8">
                        </div>
                        <div class="d-flex flex-column" style="width:60%">
                            <input type="hidden" value=0 id="id-inp" name="id">
                            <div class="row align-items-center mb-3">
                                <div class="col-6 autocomplete mb-3">
                                    <label for="category-inp" class="form-label">Category</label>
                                    <input id="category-inp" name="category" class="form-control" autocomplete="off">
                                </div>
                                <div class="col-6 autocomplete mb-3">
                                    <label for="subcategory-inp" class="form-label">Sub Category</label>
                                    <input id="subcategory-inp" name="subcategory" class="form-control" autocomplete="off">
                                </div>
                                <div class="col-6 autocomplete mb-3">
                                    <label for="type-inp" class="form-label">Type</label>
                                    <input id="type-inp" name="type" class="form-control" autocomplete="off">
                                </div>
                                <div class="col-6 autocomplete mb-3">
                                    <label for="brand-inp" class="form-label">Brand</label>
                                    <input id="brand-inp" name="brand" class="form-control" autocomplete="off">
                                </div>
                                <div class="col-6 mb-3">
                                    <label for="size-inp" class="form-label">Size</label>
                                    <input id="size-inp" name="size" class="form-control">
                                </div>
                                <div class="col-6 autocomplete mb-3">
                                    <label for="unit-inp" class="form-label">Unit</label>
                                    <input id="unit-inp" name="unit" class="form-control" autocomplete="off">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="variant-inp" class="form-label">Variant</label>
                                <input id="variant-inp" name="variant" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="name-inp" class="form-label">Name</label>
                                <input class="form-control" id="name-inp" name="name" @required(true)>
                            </div>

                            <div class="mb-3">
                                <label for="desc-inp" class="form-label">Description</label>
                                <textarea class="form-control" id="desc-inp" rows="5" name="description" @required(true)></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="stock-inp" class="form-label">Stock</label>
                                <input type="number" class="form-control" id="stock-inp" name="stock" min="0"
                                    @required(true)>
                            </div>

                            <div class="mb-3">
                                <label for="price-inp" class="form-label">Price</label>
                                <input type="number" class="form-control" id="price-inp" name="price"
                                    @required(true)>
                            </div>

                            <div class="mb-3">
                                <label for="img-inp" class="form-label">Image</label>
                                <input type="file" name="img" class="form-control" id="img-inp"
                                    accept="image/jpeg, image/png" onchange="fileProcess(event)">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button id="closeModalBtn" type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">Close</button>
                    <button id="submitFormRef" class="btn btn-outline-success" type="submit">Confirm</button>
                </div>
            </div>

        </div>
    </div>

    <main class="container-fluid">
        <div class="album">
            <button class="btn btn-primary open-modal my-3" value="0" data-bs-modalType="Create"
                data-bs-target="#mainBox" data-bs-toggle="modal">Create product</button>
            <div class="mb-3">
                @include('components.catalogue-filters', [
                    'filters' => [
                        'kategori' => $productFilters['category'] ?? [],
                        'sub kategori' => $productFilters['subcategory'] ?? [],
                        'brand' => $productFilters['brand'] ?? [],
                    ],
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
                    <div class="col-12 col-sm-6 col-md-4 col-xl-3 col-xxl-2 d-flex align-items-stretch mb-3">
                        <div class="card shadow-sm" style="width: 100%">
                            @if ($product->img)
                                <img src="{{ $product->img_path }}" alt="productimg" height="200"
                                    class="img-src object-fit-contain">
                            @else
                                <svg class="bd-placeholder-img card-img-top" width="100%" height="200"
                                    xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail"
                                    preserveAspectRatio="xMidYMid slice" focusable="false">
                                    <title>Placeholder</title>
                                    <rect width="100%" height="100%" fill="#55595c" /><text text-anchor="middle"
                                        x="50%" y="50%" fill="#eceeef" dy=".3em">Image</text>
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
        const formRef = document.getElementById("form-inp");
        const categoryInputRef = document.getElementById("category-inp");
        const subcategoryInputRef = document.getElementById("subcategory-inp");
        const typeInputRef = document.getElementById("type-inp");
        const brandInputRef = document.getElementById("brand-inp");
        const sizeInputRef = document.getElementById("size-inp");
        const unitInputRef = document.getElementById("unit-inp");
        const variantInputRef = document.getElementById("variant-inp");
        const nameInputRef = document.getElementById("name-inp");
        const descInputRef = document.getElementById("desc-inp");
        const stockInputRef = document.getElementById("stock-inp");
        const priceInputRef = document.getElementById("price-inp");
        const idInputRef = document.getElementById("id-inp");
        const imageInputRef = document.getElementById("img-inp");

        const editButton = document.getElementsByClassName("editButton");
        const delButton = document.getElementsByClassName("delButton");
        const submitFormRef = document.getElementById("submitFormRef");
        const closeModalBtnRef = document.getElementById("closeModalBtn");

        const mainBox = document.getElementById("mainBox");
        const imgBox = document.getElementById("imgBox");

        function createSvgPlaceholder() {
            const svgPlaceholder = document.createElementNS("http://www.w3.org/2000/svg", "svg");
            svgPlaceholder.setAttribute("class", "bd-placeholder-img card-img-top");
            svgPlaceholder.setAttribute("width", "100%");
            svgPlaceholder.setAttribute("height", "100%");
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
            return svgPlaceholder;
        };
        const svgPlaceholder = createSvgPlaceholder();

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

                    imgDOM.style.width = "100%";
                    imgDOM.style.height = "100%";
                    imgDOM.style.objectFit = "contain"; // maintain aspect ratio + leave blank space
                    imgDOM.style.objectPosition = "center";

                    imgBox.innerHTML = '';
                    imgBox.appendChild(imgDOM);
                } else {
                    imgBox.innerHTML = '';
                    imgBox.appendChild(svgPlaceholder);
                }

            })
            mainBox.addEventListener('hidden.bs.modal', e => {
                imgBox.innerHTML = '';
                imgBox.appendChild(svgPlaceholder);
                formRef.reset();
            })
        }

        if (editButton?.length) {
            for (let i = 0; i < editButton.length; i++) {
                const button = editButton[i];
                button.addEventListener("click", (e) => {
                    const data = datas.find(item => item.id == e.target.value) || {};
                    categoryInputRef.value = data.category || '';
                    subcategoryInputRef.value = data.subcategory || '';
                    typeInputRef.value = data.type || '';
                    brandInputRef.value = data.brand || '';
                    sizeInputRef.value = data.size || '';
                    unitInputRef.value = data.unit || '';
                    variantInputRef.value = data.variant || '';
                    nameInputRef.value = data.name || '';
                    descInputRef.value = data.description || '';
                    stockInputRef.value = data.stock || '';
                    priceInputRef.value = data.price || '';
                    idInputRef.value = data.id || '0';
                });
            }
        }

        if (submitFormRef) {
            submitFormRef.addEventListener("click", (e) => {
                e.preventDefault();
                // validate inputs
                if (nameInputRef.value.trim() == '') {
                    Swal.fire('Error', 'Product name is required', 'error');
                    return;
                }
                if (descInputRef.value.trim() == '') {
                    Swal.fire('Error', 'Product description is required', 'error');
                    return;
                }
                if (stockInputRef.value.trim() == '' || isNaN(stockInputRef.value) || Number(stockInputRef.value) <
                    0) {
                    Swal.fire('Error', 'Product stock must be a non-negative number', 'error');
                    return;
                }
                if (priceInputRef.value.trim() == '' || isNaN(priceInputRef.value) || Number(priceInputRef.value) <
                    0) {
                    Swal.fire('Error', 'Product price must be a non-negative number', 'error');
                    return;
                }
                submitFormRef.disabled = true;
                closeModalBtnRef.disabled = true;
                formRef.submit();
            });
        }

        function fileProcess(event) {
            const input = event.target;
            const file = input.files[0];
            const reader = new FileReader();
            reader.onload = function(e) {
                const imgSrc = e.target.result;
                const imgDOM = document.createElement("img");
                imgDOM.src = imgSrc;
                imgDOM.alt = "cover";

                imgDOM.style.width = "100%";
                imgDOM.style.height = "100%";
                imgDOM.style.objectFit = "contain";
                imgDOM.style.objectPosition = "center";

                imgBox.innerHTML = "";
                imgBox.appendChild(imgDOM);
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
                                    'Product has been deleted.',
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

        const filterOptions = @json($productFilters, JSON_UNESCAPED_UNICODE) || {};

        function attachAutocomplete(input, options) {
            const wrapper = input.parentElement;
            let list = document.createElement("div");
            list.className = "autocomplete-items";
            wrapper.appendChild(list);

            function renderList(value = "") {
                list.innerHTML = "";
                const v = value.toLowerCase().trim();

                const filtered = v ?
                    options.filter(item => item.toLowerCase().includes(v)) :
                    options; // show all options if no value

                if (!filtered.length) {
                    list.style.display = "none";
                    return;
                }

                filtered.forEach(option => {
                    const div = document.createElement("div");
                    div.className = "autocomplete-item";
                    div.textContent = option;

                    div.addEventListener("click", () => {
                        input.value = option;
                        list.style.display = "none";
                    });

                    list.appendChild(div);
                });

                list.style.display = "block";
            }

            // Show all options when input is clicked
            input.addEventListener("focus", () => {
                renderList(input.value);
            });

            // Filter while typing
            input.addEventListener("input", () => {
                renderList(input.value);
            });

            // Hide when clicking outside
            document.addEventListener("click", (e) => {
                if (!wrapper.contains(e.target)) {
                    list.style.display = "none";
                }
            });
        }

        // Map filterOptions
        const autocompleteMap = {
            "category-inp": filterOptions.category || [],
            "subcategory-inp": filterOptions.subcategory || [],
            "type-inp": filterOptions.type || [],
            "variant-inp": filterOptions.variant || [],
            "brand-inp": filterOptions.brand || [],
            "size-inp": filterOptions.size || [],
            "unit-inp": filterOptions.unit || [],
        };

        // Attach autocomplete
        Object.keys(autocompleteMap).forEach(id => {
            const inp = document.getElementById(id);
            if (inp) {
                attachAutocomplete(inp, autocompleteMap[id]);
            }
        });
    </script>
@endsection
