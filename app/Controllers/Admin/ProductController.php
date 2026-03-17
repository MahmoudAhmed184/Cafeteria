<?php

namespace App\Controllers\Admin;

use App\Services\Contracts\ProductServiceInterface;

class ProductController
{
    private ProductServiceInterface $productService;

    public function __construct(ProductServiceInterface $productService)
    {
        $this->productService = $productService;
    }

    public function index(): void
    {
        $products = $this->productService->getAllProducts();
        require_once __DIR__ . '/../../Views/admin/products/index.php';
    }

    public function create(): void
    {
        $categories = $this->productService->getAllCategories();
        require_once __DIR__ . '/../../Views/admin/products/form.php';
    }

    public function store(): void
    {
        $data = [
            'name' => filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            'price' => filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT),
            'category_id' => filter_input(INPUT_POST, 'category_id', FILTER_VALIDATE_INT),
        ];

        try {
            $this->productService->createProduct($data, $_FILES['image'] ?? null);
            $_SESSION['success'] = 'Product created successfully.';
            header('Location: /admin/products');
        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: /admin/products/create');
        }
        exit;
    }

    public function edit(int $id): void
    {
        $product = $this->productService->getProductById($id);
        if (!$product) {
            header('Location: /admin/products');
            exit;
        }
        $categories = $this->productService->getAllCategories();
        require_once __DIR__ . '/../../Views/admin/products/form.php';
    }

    public function update(int $id): void
    {
        $data = [
            'name' => filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            'price' => filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT),
            'category_id' => filter_input(INPUT_POST, 'category_id', FILTER_VALIDATE_INT),
        ];

        try {
            $this->productService->updateProduct($id, $data, $_FILES['image'] ?? null);
            $_SESSION['success'] = 'Product updated successfully.';
        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }
        header('Location: /admin/products');
        exit;
    }

    public function toggleAvailability(int $id): void
    {
        $this->productService->toggleAvailability($id);
        header('Location: /admin/products');
        exit;
    }

    public function delete(int $id): void
    {
        $this->productService->deleteProduct($id);
        header('Location: /admin/products');
        exit;
    }
    
    public function storeCategory(): void
    {
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ($name) {
            $this->productService->createCategory($name);
        }
        header('Location: /admin/products/create');
        exit;
    }
}
