@extends('layouts.app')
@section('title', 'Catalogue - IndoJuni')
@section('styles')
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css" />
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/default.min.css" />
@endsection
@section('content')
    <div class="container-fluid" style="margin-top: 80px;">
        <div class="album bg-body-tertiary">
            <div class="row p-3">
                <div class="col">
                    <form action="{{ route('app.catalogue.page') }}" method="GET">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search product..." name="search"
                                value="{{ request('search') }}">
                            <button class="btn btn-outline-secondary" type="submit" id="button-search">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row p-3">
                <div class="col">
                    {{ $products->links() }}
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
                                    xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail"
                                    preserveAspectRatio="xMidYMid slice" focusable="false">
                                    <title>Placeholder</title>
                                    <rect width="100%" height="100%" fill="#55595c" /><text text-anchor="middle" x="50%"
                                        y="50%" fill="#eceeef" dy=".3em">Image</text>
                                </svg>
                            @endif
                            <div class="card-body d-flex flex-column">
                                <p class="card-text fw-bolder">{{ $product->name }}</p>
                                {{-- <p class="card-text text-body-secondary mb-4">{{ $product->description }}</p> --}}
                                <div class="d-flex justify-content-between align-items-center mt-auto">
                                    <small
                                        class="text-body-secondary">Rp{{ number_format($product->price, 2, ',', '.') }}</small>
                                    <div>
                                        <small class="text-body-secondary stock_product me-2">Stok:
                                            {{ $product->stock }}</small>
                                        <input type="hidden" name="quantity" value=1>
                                        <button type="submit" title="Add to cart"
                                            class="btn btn-sm btn-outline-secondary productAdd" value="{{ $product->id }}"
                                            name="product_id" @if ($product->stock == 0) disabled @endif>
                                            @if ($product->stock == 0)
                                                <i class="fa-solid fa-times"></i>
                                            @else
                                                <i class="fa-solid fa-plus"></i>
                                            @endif
                                        </button>
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
    </div>
    <iframe name="frame" style="display:none"></iframe>
    </main>
@endsection
@section('scripts')
    <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
    <script>
        async function fetchData(product_id) {
            const res = await fetch("{{ route('app.cart.add') }}", {
                    method: 'POST',
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                        "X-Requested-With": "XMLHttpRequest",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    credentials: "same-origin",
                    body: JSON.stringify({
                        "product": [{
                            'product_id': product_id,
                            'quantity': 1
                        }]
                    })
                })
                .then((response) => response.json());
            return res;
        };
        const productAdds = document.querySelectorAll('.productAdd');
        productAdds.forEach(productAdd => {
            productAdd.addEventListener('click', async function() {
                productAdd.innerHTML = '<i class="fa-solid fa-arrow-rotate-right fa-spin"></i>';
                productAdd.disabled = true;
                const stock = this.parentElement.querySelector('.stock_product').innerText.split(' ')[
                    1];
                if (stock < 1) {
                    alertify.error('Stok produk habis');
                    productAdd.innerHTML = '<i class="fa-solid fa-times"></i>';
                    productAdd.disabled = true;
                    return;
                }
                const response = await fetchData(this.value);
                productAdd.innerHTML = '<i class="fa-solid fa-plus"></i>';
                productAdd.disabled = false;
                if (response.status === 200) {
                    alertify.success(response.message);
                } else {
                    alertify.error(response.message);
                }
            });
        });
    </script>
@endsection
