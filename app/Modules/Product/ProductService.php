<?php

declare(strict_types=1);

namespace App\Modules\Product;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductService
{
    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }
    public function createProduct(array $data): Product
    {
        // check if exists
        $existingProduct = $this->productRepository->getProductByName($data['name']);
        if ($existingProduct) {
            throw new \Exception('Product already exists');
        }
        return $this->productRepository->createProduct($data);
    }
    public function updateProduct(array $data): bool
    {
        $product = $this->productRepository->getProductByID($data['id']);
        if (!$product) {
            throw new \Exception('Product not found');
        }
        $product = $this->productRepository->getProductByName($data['name']);
        if ($product && $product->id !== $data['id']) {
            throw new \Exception('Product with this name already exists');
        }
        return $this->productRepository->updateProduct($data);
    }
    public function getProductByName(string $name): Product | null
    {
        return $this->productRepository->getProductByName($name);
    }
    public function getProductByID(int $id): Product | null
    {
        return $this->productRepository->getProductByID($id);
    }
    public function getPaginatedProduct(int $page, string $column): LengthAwarePaginator
    {
        return $this->productRepository->getPaginatedProduct($page, $column);
    }
    public function getAllProducts(): Collection
    {
        return $this->productRepository->getAllProducts();
    }
}
