<?php

namespace App\Http\Controllers;

use App\Modules\Product\ProductService;

class ProductController extends Controller
{

    private ProductService $productService;
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }
    public function index()
    {
        $products = $this->productService->getPaginatedProduct(6, 'name');
        return view('pages.catalogue', compact('products'));
    }
}
