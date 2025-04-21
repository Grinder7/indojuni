<?php

declare(strict_types=1);

namespace App\Modules\Product;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductService
{
    public ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getAllData(): Collection
    {
        return $this->productRepository->getAllProduct();
    }

    public function getByProductId(int $id): Product
    {
        return $this->productRepository->getByProductId($id);
    }

    public function getPaginatedProduct(int $page, string $column): LengthAwarePaginator
    {
        return $this->productRepository->getProductPaginated($page, $column);
    }
    public function createData(array $data): Product
    {
        return $this->productRepository->createProduct($data);
    }
    public function getProductCount()
    {
        return $this->productRepository->getProductCount();
    }
    public function searchBySimilarity(string $columnName, string $name, int|null $limit): Collection
    {
        return $this->productRepository->searchBySimilarity($columnName, $name, $limit);
    }
}
