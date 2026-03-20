<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Category;
use App\Services\Contracts\ProductServiceInterface;
use Exception;

class ProductService implements ProductServiceInterface
{
    private Product $productModel;
    private Category $categoryModel;
    private FileUploadService $fileUploadService;

    public function __construct(Product $productModel, Category $categoryModel, FileUploadService $fileUploadService)
    {
        $this->productModel = $productModel;
        $this->categoryModel = $categoryModel;
        $this->fileUploadService = $fileUploadService;
    }

    public function getAllProducts(bool $availableOnly = false): array
    {
        return $this->productModel->fetchAll($availableOnly);
    }

    public function getProductById(int $productId): ?array
    {
        $product = $this->productModel->findById($productId);
        return $product ?: null;
    }

    public function createProduct(array $data, ?array $file = null): int
    {
        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $data['image'] = $this->uploadImage($file);
        }
        else {
            throw new Exception("Image is required for new products.");
        }

        return $this->productModel->create($data);
    }

    public function updateProduct(int $productId, array $data, ?array $file = null): bool
    {
        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $data['image'] = $this->uploadImage($file);
        }

        return $this->productModel->update($productId, $data);
    }

    public function deleteProduct(int $productId): bool
    {
        return $this->productModel->delete($productId);
    }

    public function toggleAvailability(int $productId): bool
    {
        return $this->productModel->toggleAvailability($productId);
    }

    public function getAllCategories(): array
    {
        return $this->categoryModel->fetchAll();
    }

    public function createCategory(string $name): int
    {
        return $this->categoryModel->create($name);
    }

    private function uploadImage(array $file): string
    {
        return $this->fileUploadService->uploadImage($file, 'products', 'prod');
    }
}