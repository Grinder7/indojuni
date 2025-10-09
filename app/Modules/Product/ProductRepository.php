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
    public function getPaginatedProduct(int $page, string $column, string $searchKeyword): LengthAwarePaginator
    {
        if ($searchKeyword === null || trim($searchKeyword) === '') {
            return Product::orderby($column, 'asc')->paginate($page);
        }
        return Product::searchBySimilarityPaginated("name", $searchKeyword, $page);
    }
    public function getAllProducts($limit, $page): Collection
    {
        if ($limit !== null) {
            return Product::orderby('id', 'asc')->paginate($limit, ['*'], 'page', $page)->getCollection();
        }
        return Product::all();
    }
    public function searchSimilarProductByName(string $productName): Collection
    {
        return Product::searchBySimilarity("name", $productName);
    }
    public function searchContainProductByName(string $productName): Collection
    {
        return Product::where("name", 'ILIKE', '%' . $productName . '%')->get();
    }
}
