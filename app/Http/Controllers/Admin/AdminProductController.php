<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Product\ProductService;

class AdminProductController extends Controller
{
    public ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }
    public function index()
    {
        $products = $this->productService->getAllData();
        return view('pages.admin.product')->with(compact("products"));
    }

    public function add()
    {
        return view('pages.admin.addProduct');
    }

    public function edit($id) {}

    public function delete($id)
    {
        $product = $this->productService->getById($id);
        if ($product) {
            $product->delete();
            return redirect()->route('admin.product.view')->with('success', 'Product deleted successfully');
        } else {
            return redirect()->route('admin.product.view')->with('error', 'Product not found');
        }
    }
}
