@extends('layouts.admin')
@section('title', 'View Products - IndoJuni')
@section('content')
    <div class="album">
        <div class="container">
            <h1 class="border-bottom mb-3 pb-2">Products</h1>
            <div class="d-flex justify-content-between mb-3">
                <a href="#" class="btn btn-primary me-2">Add Product</a>
                <form class="d-flex" action="" method="POST">
                    @csrf
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" name="search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>
            </div>

            <div class="row row-cols-1 row-cols-sm-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 row-cols-xxl-5 g-3">
                @if ($products)
                    @foreach ($products as $product)
                        <div class="col d-flex align-items-stretch mb-3">
                            <div class="card shadow-sm">
                                {{-- <img class="img-src" src="{{ asset($product->img) }}" alt="cover" height="225"
                                    style="object-fit: contain" data-imgsrc="{{ asset($product->img) }}"> --}}
                                <x-cld-image public-id="{{ $product->img }}" height="225" alt="cover" />

                                <div class="card-body d-flex flex-column">
                                    <div class="fw-bolder card-text">
                                        <p class="name-src">
                                            {{ $product->name }}</p>
                                        <p>Rp<span
                                                class="price-src">{{ number_format($product->price, 0, ',', '.') }}</span>
                                        </p>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mt-auto">
                                        <div class="btn-group">
                                            <button type="button"
                                                class="btn btn-sm btn-outline-secondary showSidebar editButton"
                                                value="{{ $product->id }}">Edit</button>
                                            <form action="{{ route('admin.product.delete', $product->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="btn btn-sm btn-outline-secondary delButton">Delete</button>
                                            </form>
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
            </div>
        </div>
    </div>
    @include('partials.sweet-alert')
@endsection
