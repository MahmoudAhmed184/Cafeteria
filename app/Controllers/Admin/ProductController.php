<?php

require_once __DIR__ . '/../../Services/ProductService.php';

class ProductController
{
    private $productService;

    public function __construct()
    {
        $this->productService = new ProductService();
    }

    public function index()
    {
        $products = $this->productService->getAllProducts();

        require __DIR__ . '/../../Views/admin/products/index.php';
    }

    public function store()
    {
        try {

            $this->productService->createProduct($_POST, $_FILES['image']);

            header("Location: /admin/products");
            exit;

        } catch (Exception $e) {

            echo $e->getMessage();
        }
    }
}