<?php

require_once __DIR__ . '/../Models/Product.php';
require_once __DIR__ . '/../Helpers/upload.php';

class ProductService
{
    private $productModel;

    public function __construct()
    {
        $this->productModel = new Product();
    }

    public function getAllProducts()
    {
        return $this->productModel->getAll();
    }

    public function createProduct($data, $file)
    {
        $this->validate($data);

        $imagePath = uploadImage($file, 'products');

        $productData = [
            'name' => $data['name'],
            'price' => $data['price'],
            'category_id' => $data['category_id'],
            'image' => $imagePath
        ];

        return $this->productModel->create($productData);
    }

    private function validate($data)
    {
        if (empty($data['name'])) {
            throw new Exception("Product name is required");
        }

        if (!is_numeric($data['price']) || $data['price'] <= 0) {
            throw new Exception("Invalid price");
        }

        if (empty($data['category_id'])) {
            throw new Exception("Category is required");
        }
    }
}