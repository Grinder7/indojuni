<?php

declare(strict_types=1);

namespace App\Modules\Product;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ProductRepository
{
    public function getProductByName(string $name): Product | null
    {
        return Product::where('is_active', true)->where('name', $name)->first();
    }
    public function createProduct(array $data): Product
    {
        return Product::create($data);
    }
    public function getProductByID(int $productID): Product | null
    {
        return Product::where('is_active', true)->find($productID);
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
            $query->where('is_active', true);
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
            return $query->orderBy($column, 'asc')->paginate($page);
        }
        return Product::searchBySimilarityPaginated("name", $searchKeyword, $page, $filter);
    }
    public function getAllProducts(int|null $limit, int|null $page, array $filter): Collection
    {
        $params = [];
        $nameParams = [];
        $otherParams = [];

        $nameQuery = null;
        $otherQuery = null;
        if (!empty($filter['name'])) {
            $name = $filter['name'];
            $wordCount = str_word_count($name);
            $threshold = match ($wordCount) {
                1 => 0.1,
                2 => 0.275,
                3 => 0.375,
                default => 0.45,
            };
            $nameQuery = "
            SELECT
                p.*
            FROM products p
            WHERE
                p.search_vector @@ (
                    websearch_to_tsquery('indonesian', ?) ||
                    websearch_to_tsquery('english', ?)
                )
                OR similarity(name, ?) >= {$threshold}
            ORDER BY ts_rank_cd(p.search_vector, websearch_to_tsquery('indonesian', ?) || websearch_to_tsquery('english', ?))
        ";
            $nameParams = [$name, $name, $name, $name, $name];
        }
        $otherQuery = "SELECT p.* FROM products p";
        $otherWhere = [];
        $otherWhere[] = "p.is_active = true";
        if (!empty($filter['category'])) {
            $otherWhere[] = "p.category = ?";
            $otherParams[] = $filter['category'];
        }
        if (!empty($filter['subcategory'])) {
            $otherWhere[] = "p.subcategory = ?";
            $otherParams[] = $filter['subcategory'];
        }
        if (!empty($filter['type'])) {
            $otherWhere[] = "p.type = ?";
            $otherParams[] = $filter['type'];
        }
        if (!empty($filter['variant'])) {
            $otherWhere[] = "p.variant = ?";
            $otherParams[] = $filter['variant'];
        }
        if (!empty($filter['brand'])) {
            $otherWhere[] = "p.brand = ?";
            $otherParams[] = $filter['brand'];
        }
        if (!empty($filter['size'])) {
            $otherWhere[] = "p.size = ?";
            $otherParams[] = $filter['size'];
        }
        if (!empty($filter['unit'])) {
            $otherWhere[] = "p.unit = ?";
            $otherParams[] = $filter['unit'];
        }
        if (isset($filter['stock_min'])) {
            $otherWhere[] = "p.quantity >= ?";
            $otherParams[] = $filter['stock_min'];
        }
        if (isset($filter['stock_max'])) {
            $otherWhere[] = "p.quantity <= ?";
            $otherParams[] = $filter['stock_max'];
        }
        if (isset($filter['price_min'])) {
            $otherWhere[] = "p.price >= ?";
            $otherParams[] = $filter['price_min'];
        }
        if (isset($filter['price_max'])) {
            $otherWhere[] = "p.price <= ?";
            $otherParams[] = $filter['price_max'];
        }
        if (!empty($filter['description'])) {
            $otherWhere[] = "p.description ILIKE ?";
            $otherParams[] = '%' . $filter['description'] . '%';
        }

        if (!empty($otherWhere)) {
            $otherQuery .= " WHERE " . implode(" AND ", $otherWhere);
        }
        if ($nameQuery !== null && !empty($otherWhere)) {
            $query = "
            ($nameQuery)
            UNION
            ($otherQuery)
        ";
            $params = array_merge($nameParams, $otherParams);
        } elseif ($nameQuery !== null) {
            $query = $nameQuery;
            $params = $nameParams;
        } else {
            $query = $otherQuery;
            $params = $otherParams;
        }
        if ($limit !== null && $page !== null) {
            $offset = ($page - 1) * $limit;
            $query .= " LIMIT {$limit} OFFSET {$offset}";
        }
        $rows = DB::select($query, $params);
        return Product::hydrate($rows);
    }


    public function searchSimilarProductByName(string $productName): Collection
    {
        return Product::searchBySimilarity("name", $productName);
    }
    public function searchContainProductByName(string $productName): Collection
    {
        return Product::where('is_active', true)->where("name", 'ILIKE', '%' . $productName . '%')->get();
    }
    public function getProductFilterOptions(): array
    {
        $results = Product::select('category', 'subcategory', 'brand')->where('is_active', true)->get();

        $data = [
            'kategori' => $results->pluck('category')->filter()->unique()->values()->all(),
            'sub kategori' => $results->pluck('subcategory')->filter()->unique()->values()->all(),
            'brand' => $results->pluck('brand')->filter()->unique()->values()->all(),
        ];

        return $data;
    }
}
