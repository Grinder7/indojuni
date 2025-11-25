<?php

declare(strict_types=1);

namespace App\Modules\Product;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductRepository
{
    public function getProductByName(string $name): Product | null
    {
        return Product::where('name', $name)->first();
    }
    public function createProduct(array $data): Product
    {
        return Product::create($data);
    }
    public function getProductByID(int $productID): Product | null
    {
        return Product::find($productID);
    }
    public function subtractQuantityByID(int $productID, int $quantity): bool
    {
        $product = Product::find($productID);
        if ($product) {
            if ($product->quantity >= $quantity) {
                $product->quantity -= $quantity;
                return $product->save();
            }
        }
        return false;
    }
    public function updateProduct(array $data): bool
    {
        $product = Product::find($data['id']);
        if ($product) {
            $product->update($data);
            return true;
        }
        return false;
    }
    public function getPaginatedProduct(int $page, string $column, string $searchKeyword, array $filter): LengthAwarePaginator
    {
        if ($searchKeyword === null || trim($searchKeyword) === '') {
            $query = Product::query();
            // Apply filters
            if (isset($filter['category']) && !empty($filter['category'])) {
                $query->where('category', $filter['category']);
            }
            if (isset($filter['subcategory']) && !empty($filter['subcategory'])) {
                $query->where('subcategory', $filter['subcategory']);
            }
            if (isset($filter['brand']) && !empty($filter['brand'])) {
                $query->where('brand', $filter['brand']);
            }
            return $query->orderby($column, 'asc')->paginate($page);
        }
        return Product::searchBySimilarityPaginated("name", $searchKeyword, $page, $filter);
    }
    public function getAllProducts(int|null $limit, int|null $page, array $filter): Collection
    {
        $query = Product::query();

        // Apply filters
        if (isset($filter['category']) && !empty($filter['category'])) {
            $query->where('category', $filter['category']);
        }
        if (isset($filter['subcategory']) && !empty($filter['subcategory'])) {
            $query->where('subcategory', $filter['subcategory']);
        }
        if (isset($filter['type']) && !empty($filter['type'])) {
            $query->where('type', $filter['type']);
        }
        if (isset($filter['variant']) && !empty($filter['variant'])) {
            $query->where('variant', $filter['variant']);
        }
        if (isset($filter['brand']) && !empty($filter['brand'])) {
            $query->where('brand', $filter['brand']);
        }
        if (isset($filter['size']) && !empty($filter['size'])) {
            $query->where('size', $filter['size']);
        }
        if (isset($filter['unit']) && !empty($filter['unit'])) {
            $query->where('unit', $filter['unit']);
        }
        if (isset($filter['name']) && !empty($filter['name'])) {
            $query->where('name', 'ILIKE', '%' . $filter['name'] . '%');
        }
        if (isset($filter['stock_min']) && is_numeric($filter['stock_min'])) {
            $query->where('quantity', '>=', $filter['stock_min']);
        }
        if (isset($filter['stock_max']) && is_numeric($filter['stock_max'])) {
            $query->where('quantity', '<=', $filter['stock_max']);
        }
        if (isset($filter['price_min']) && is_numeric($filter['price_min'])) {
            $query->where('price', '>=', $filter['price_min']);
        }
        if (isset($filter['price_max']) && is_numeric($filter['price_max'])) {
            $query->where('price', '<=', $filter['price_max']);
        }
        if (isset($filter['description']) && !empty($filter['description'])) {
            $query->where('description', 'ILIKE', '%' . $filter['description'] . '%');
        }

        if ($limit !== null && $page !== null) {
            return $query->orderby('id', 'asc')->paginate($limit, ['*'], 'page', $page)->getCollection();
        }
        return $query->get();
    }
    public function searchSimilarProductByName(string $productName): Collection
    {
        return Product::searchBySimilarity("name", $productName);
    }
    public function searchContainProductByName(string $productName): Collection
    {
        return Product::where("name", 'ILIKE', '%' . $productName . '%')->get();
    }
    public function getProductFilterOptions(): array
    {
        $results = Product::select('category', 'subcategory', 'brand')->get();

        $data = [
            'kategori' => $results->pluck('category')->filter()->unique()->values()->all(),
            'sub kategori' => $results->pluck('subcategory')->filter()->unique()->values()->all(),
            'brand' => $results->pluck('brand')->filter()->unique()->values()->all(),
        ];

        return $data;
    }
}
