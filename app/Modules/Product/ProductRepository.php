<?php

declare(strict_types=1);

namespace App\Modules\Product;

use Illuminate\Database\Eloquent\Collection;
use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductRepository
{
    public function getAllProduct(): Collection
    {
        return Product::all();
    }
    public function getProductPaginated(int $page, string $column): LengthAwarePaginator
    {
        return Product::orderby($column, 'desc')->paginate($page);
    }
    public function getProductById(int $id): Product
    {
        return Product::find($id);
    }
    public function getById(int $id): Product
    {
        return Product::find($id);
    }
    public function createProduct(array $data): Product
    {
        return Product::create($data);
    }
    public function getProductCount()
    {
        return Product::count();
    }
    public function searchBySimilarity(string $columnName, string $name, int|null $limit): Collection
    {
        return Product::searchBySimilarity($columnName, $name, $limit);
    }
}
