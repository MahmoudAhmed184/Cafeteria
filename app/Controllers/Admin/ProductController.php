<?php

require_once __DIR__ . '/../../Services/ProductService.php';
require_once __DIR__ . '/../../Models/Category.php';

class ProductController
{
    private $productService;

    public function __construct()
    {
        $this->productService = new ProductService();
    }

    // ── CSRF helpers ─────────────────────────────────────────────────────────

    private function generateCsrfToken(): string
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    private function validateCsrfToken(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $token = $_POST['csrf_token'] ?? '';
        if (empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
            http_response_code(403);
            throw new Exception('Invalid CSRF token. Please refresh the page and try again.');
        }
    }

    // ── Input helpers ─────────────────────────────────────────────────────────

    private function getValidId(): int
    {
        if (!isset($_GET['id']) || !is_numeric($_GET['id']) || (int)$_GET['id'] <= 0) {
            http_response_code(400);
            throw new Exception('Invalid or missing product ID.');
        }
        return (int)$_GET['id'];
    }
    public function index()
    {
        $products = $this->productService->getAllProducts();

        require __DIR__ . '/../../Views/admin/products/index.php';
    }
    public function edit()
    {
        try {
            $id = $this->getValidId();

            $product = $this->productService->getProductById($id);

            if (!$product) {
                throw new Exception('Product not found.');
            }

            $categoryModel = new Category();
            $categories    = $categoryModel->getAll();
            $csrfToken     = $this->generateCsrfToken();

            require __DIR__ . '/../../Views/admin/products/edit.php';
        } catch (Exception $e) {
            echo htmlspecialchars($e->getMessage());
        }
    }
    public function update()
    {
        try {
            $this->validateCsrfToken();

            $id = $this->getValidId();

            $this->productService->updateProduct(
                $id,
                $_POST,
                $_FILES['image'] ?? null
            );

            header('Location: /admin/products');
            exit;

        } catch (Exception $e) {
            echo htmlspecialchars($e->getMessage());
        }
    }
    public function delete()
    {
        try {
            $this->validateCsrfToken();

            $id = $this->getValidId();

            $this->productService->deleteProduct($id);

            header('Location: /admin/products');
            exit;

        } catch (Exception $e) {
            echo htmlspecialchars($e->getMessage());
        }
    }
    public function toggle()
    {
        try {
            $this->validateCsrfToken();

            $id = $this->getValidId();

            $this->productService->toggleAvailability($id);

            header('Location: /admin/products');
            exit;

        } catch (Exception $e) {
            echo htmlspecialchars($e->getMessage());
        }
    }
    public function create()
    {
        $categoryModel = new Category();
        $categories    = $categoryModel->getAll();
        $csrfToken     = $this->generateCsrfToken();

        require __DIR__ . '/../../Views/admin/products/create.php';
    }

    public function store()
    {
        try {
            $this->validateCsrfToken();

            $this->productService->createProduct($_POST, $_FILES['image'] ?? null);

            header('Location: /admin/products');
            exit;

        } catch (Exception $e) {
            echo htmlspecialchars($e->getMessage());
        }
    }
}
