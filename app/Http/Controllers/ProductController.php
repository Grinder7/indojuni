<?php

namespace App\Http\Controllers;

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
        if ($request->expectsJson()) {
            try {
                $limitQuery = $request->query('limit');
                $pageQuery = $request->query('page');
                $limit  = null;
                $page = null;
                if (is_numeric($limitQuery)) {
                    $limit = (int)$limitQuery;
                    if ($limit < 1) {
                        return response()->json([
                            'status' => 400,
                            'message' => "Limit must be greater than 0"
                        ], 400);
                    }
                    $page = 1; // Default to page 1 if limit is provided
                }
                if (is_numeric($pageQuery)) {
                    $page = (int)$pageQuery;
                    if ($page < 1) {
                        return response()->json([
                            'status' => 400,
                            'message' => "Page must be greater than 0"
                        ], 400);
                    } else if ($limit === null) {
                        return response()->json([
                            'status' => 400,
                            'message' => "Limit must be provided when page is specified"
                        ], 400);
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
        } else {
            $searchQuery = $request->query('search');
            if ($searchQuery === null) $searchQuery = '';
            $filter = [];
            $filter["category"] = $request->query('kategori', '');
            $filter["subcategory"] = $request->query("sub_kategori", '');
            $filter["brand"] = $request->query("brand", '');
            // dd($filter);
            $products = $this->productService->getPaginatedProduct(24, 'name', $searchQuery, $filter);
            $productFilters = $this->productService->getProductFilterOptions();
            return view('pages.catalogue', compact('products'))->with('productFilters', $productFilters);
        }
    }
}
