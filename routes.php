<?php

// Autoloader for our App namespace
spl_autoload_register(function ($class) {
    if (str_starts_with($class, 'App\\')) {
        $file = __DIR__ . '/app/' . str_replace('\\', '/', substr($class, 4)) . '.php';
        if (file_exists($file)) {
            require_once $file;
        }
    }
});

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;

use App\Services\AuthService;
use App\Services\ProductService;
use App\Services\OrderService;
use App\Services\UserService;
use App\Services\CheckService;

use App\Controllers\AuthController;
use App\Controllers\OrderController;
use App\Controllers\Admin\AdminOrderController;
use App\Controllers\Admin\ProductController;
use App\Controllers\Admin\UserController;
use App\Controllers\Admin\CheckController;

// DB configuration (Dummy configuration for wiring demonstration)
try {
    $pdo = new PDO("mysql:host=localhost;dbname=cafeteria;charset=utf8mb4", "root", "", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (\PDOException $e) {
    // Suppress for static analysis / routing test if DB is down.
    $pdo = new PDO("sqlite::memory:");
}

// Models
$userModel = new User($pdo);
$productModel = new Product($pdo);
$categoryModel = new Category($pdo);
$orderModel = new Order($pdo);
$orderItemModel = new OrderItem($pdo);

// Services
$authService = new AuthService($userModel);
$productService = new ProductService($productModel, $categoryModel);
$orderService = new OrderService($pdo, $orderModel, $orderItemModel);
$userService = new UserService($userModel, $pdo);
$checkService = new CheckService($pdo);

// Controllers
$authController = new AuthController($authService);
$userOrderController = new OrderController($orderService);
$adminOrderController = new AdminOrderController($orderService);
$adminProductController = new ProductController($productService);
$adminUserController = new UserController($userService);
$adminCheckController = new CheckController($checkService, $userModel);

// Routes Wiring

// Auth
$router->get('/login', fn() => $authController->showLoginForm());
$router->post('/login', fn() => $authController->login());
$router->get('/logout', fn() => $authController->logout());
$router->get('/forget-password', fn() => $authController->showForgetPasswordForm());
$router->post('/forget-password', fn() => $authController->resetPassword());

// User Orders
$router->get('/orders', fn() => $userOrderController->index());
$router->get('/orders/items', fn() => $userOrderController->items((int)($_GET['order_id'] ?? 0)));
$router->post('/orders/cancel', fn() => $userOrderController->cancel((int)($_POST['order_id'] ?? 0)));

// Admin Orders
$router->get('/admin/orders', fn() => $adminOrderController->index());
$router->post('/admin/orders/deliver', fn() => $adminOrderController->deliver((int)($_POST['order_id'] ?? 0)));
$router->post('/admin/orders/done', fn() => $adminOrderController->done((int)($_POST['order_id'] ?? 0)));
$router->get('/admin/orders/items', fn() => $adminOrderController->items((int)($_GET['order_id'] ?? 0)));

// Admin Products
$router->get('/admin/products', fn() => $adminProductController->index());
$router->get('/admin/products/create', fn() => $adminProductController->create());
$router->post('/admin/products/store', fn() => $adminProductController->store());
$router->get('/admin/products/edit', fn() => $adminProductController->edit((int)($_GET['id'] ?? 0)));
$router->post('/admin/products/update', fn() => $adminProductController->update((int)($_POST['id'] ?? 0)));
$router->post('/admin/products/toggle', fn() => $adminProductController->toggleAvailability((int)($_POST['id'] ?? 0)));
$router->post('/admin/products/delete', fn() => $adminProductController->delete((int)($_POST['id'] ?? 0)));
$router->post('/admin/categories/store', fn() => $adminProductController->storeCategory());

// Admin Users
$router->get('/admin/users', fn() => $adminUserController->index());
$router->get('/admin/users/create', fn() => $adminUserController->create());
$router->post('/admin/users/store', fn() => $adminUserController->store());
$router->get('/admin/users/edit', fn() => $adminUserController->edit((int)($_GET['id'] ?? 0)));
$router->post('/admin/users/update', fn() => $adminUserController->update((int)($_POST['id'] ?? 0)));
$router->post('/admin/users/delete', fn() => $adminUserController->delete((int)($_POST['id'] ?? 0)));

// Admin Checks
$router->get('/admin/checks', fn() => $adminCheckController->index());
$router->get('/admin/checks/user-orders', fn() => $adminCheckController->userOrders((int)($_GET['user_id'] ?? 0)));
$router->get('/admin/checks/order-items', fn() => $adminCheckController->orderItems((int)($_GET['order_id'] ?? 0)));
