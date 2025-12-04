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
    public function index(Request $request)
    {
        $searchQuery = $request->query('search');
        if ($searchQuery === null) $searchQuery = '';
        $filter = [];
        $filter["category"] = $request->query('category', '');
        $filter["subcategory"] = $request->query("subcategory", '');
        $filter["brand"] = $request->query("brand", '');

        $products = $this->productService->getPaginatedProduct(24, 'name', $searchQuery, $filter);
        $productFilters = $this->productService->getProductFilterOptions();
        return view('pages.catalogue', compact('products'))->with('productFilters', $productFilters);
    }
    public function getProducts(Request $request)
    {
        try {
            $limitQuery = $request->query('limit');
            $pageQuery = $request->query('page');
            $limit  = null;
            $page = null;
            if (is_numeric($limitQuery)) {
                $limit = (int)$limitQuery;
                if ($limit < 1) {
                    response()->json([
                        'status' => 400,
                        'message' => "Limit must be greater than 0"
                    ], 400);
                    return;
                }
                $page = 1; // Default to page 1 if limit is provided
            }
            if (is_numeric($pageQuery)) {
                $page = (int)$pageQuery;
                if ($page < 1) {
                    response()->json([
                        'status' => 400,
                        'message' => "Page must be greater than 0"
                    ], 400);
                    return;
                } else if ($limit === null) {
                    response()->json([
                        'status' => 400,
                        'message' => "Limit must be provided when page is specified"
                    ], 400);
                    return;
                }
            }
            $filter = [];
            $filter["category"] = $request->query('category');
            $filter["subcategory"] = $request->query('subcategory');
            $filter["type"] = $request->query('type');
            $filter["variant"] = $request->query('variant');
            $filter["brand"] = $request->query('brand');
            $filter["size"] = $request->query('size');
            $filter["unit"] = $request->query('unit');
            $filter["name"] = $request->query('name');
            $filter["stock_min"] = $request->query('stock_min');
            $filter["stock_max"] = $request->query('stock_max');
            $filter["price_min"] = $request->query('price_min');
            $filter["price_max"] = $request->query('price_max');
            $filter["description"] = $request->query('description');

            $products = $this->productService->getAllProducts($limit, $page, $filter);
            return response()->json([
                'status' => 200,
                'data' => ProductSummaryResource::collection($products),
                "message" => "Successfully retrieved products"
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => "Failed to retrieve products: " . $th->getMessage()
            ], 500);
        }
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
    public function searchSimilarProductByName(Request $request)
    {
        $validated = $request->validate([
            'product_name' => 'required|string',
        ]);
        $products = $this->productService->searchSimilarProductByName($validated['product_name']);
        return response()->json([
            'status' => 200,
            'data' => ProductSummaryResource::collection($products),
            "message" => "Successfully retrieved products"
        ]);
    }

    public function searchContainProductByName(Request $request)
    {
        $validated = $request->validate([
            'product_name' => 'required|string',
        ]);
        $products = $this->productService->searchContainProductByName($validated['product_name']);
        return response()->json([
            'status' => 200,
            'data' => ProductSummaryResource::collection($products),
            "message" => "Successfully retrieved products"
        ]);
    }
    public function getProductFilterOptions(Request $request)
    {
        try {
            $options = $this->productService->getProductFilterOptions();
            return response()->json([
                'status' => 200,
                'data' => $options,
                "message" => "Successfully retrieved product filter options"
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => "Failed to retrieve product filter options: " . $th->getMessage()
            ], 500);
        }
    }
}
