<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductDetailResource;
use App\Http\Resources\ProductSummaryResource;
use App\Modules\Product\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    private ProductService $productService;
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }
    public function index()
    {
        $products = $this->productService->getPaginatedProduct(8, 'name');
        return view('pages.catalogue', compact('products'));
    }
    public function getProducts()
    {
        $products = $this->productService->getAllProducts();
        return response()->json([
            'status' => 200,
            'data' => ProductSummaryResource::collection($products),
            "message" => "Successfully retrieved products"
        ]);
    }
    public function getProductById(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
        ]);
        $product = $this->productService->getProductById($validated['product_id']);
        return response()->json([
            'status' => 200,
            'data' => ProductDetailResource::make($product),
            "message" => "Successfully retrieved product"
        ]);
    }
}
