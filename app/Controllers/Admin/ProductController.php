<?php

require_once __DIR__ . '/../../Services/ProductService.php';

class ProductController
{
    private $productService;

    public function __construct()
    {
        $this->productService = new ProductService();
    }
    public function create()
    {
        $categoryModel = new Category();
        $categories = $categoryModel->getAll();

        require __DIR__ . '/../../Views/admin/products/create.php';
    }
    public function index()
    {
        $products = $this->productService->getAllProducts();

        require __DIR__ . '/../../Views/admin/products/index.php';
    }
    public function edit()
{
    $id = $_GET['id'];

    $product = $this->productService->getProductById($id);

    $categoryModel = new Category();
    $categories = $categoryModel->getAll();

    require __DIR__ . '/../../Views/admin/products/edit.php';
}
public function update()
{
    try {

        $id = $_GET['id'];

        $this->productService->updateProduct(
            $id,
            $_POST,
            $_FILES['image']
        );

        header("Location: /admin/products");
        exit;

    } catch (Exception $e) {

        echo $e->getMessage();
    }
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
