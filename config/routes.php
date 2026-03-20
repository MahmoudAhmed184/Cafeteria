<?php

require_once __DIR__ . '/app.php';
require_once __DIR__ . '/database.php';

spl_autoload_register(function ($class) {
    if (str_starts_with($class, 'App\\')) {
        $file = dirname(__DIR__) . '/app/' . str_replace('\\', '/', substr($class, 4)) . '.php';
        if (file_exists($file)) {
            require_once $file;
        }
    }
});

use App\Controllers\Admin\AdminOrderController;
use App\Controllers\Admin\CheckController;
use App\Controllers\Admin\ManualOrderController;
use App\Controllers\Admin\ProductController;
use App\Controllers\Admin\UserController;
use App\Controllers\AuthController;
use App\Controllers\CartController;
use App\Controllers\OrderController;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Room;
use App\Models\User;
use App\Services\AuthService;
use App\Services\CartService;
use App\Services\CheckService;
use App\Services\ManualOrderService;
use App\Services\OrderService;
use App\Services\ProductService;
use App\Services\UserService;

$pdo = database_connection();

$userModel = new User($pdo);
$productModel = new Product($pdo);
$categoryModel = new Category($pdo);
$orderModel = new Order($pdo);
$orderItemModel = new OrderItem($pdo);
$roomModel = new Room($pdo);

$authService = new AuthService($userModel);
$productService = new ProductService($productModel, $categoryModel);
$orderService = new OrderService($pdo, $orderModel, $orderItemModel);
$userService = new UserService($userModel, $pdo);
$checkService = new CheckService($pdo);
$cartService = new CartService($productModel, 'user_cart');
$manualOrderService = new ManualOrderService($pdo, $orderService);

$authController = new AuthController($authService);
$cartController = new CartController($cartService, $productService, $orderService, $roomModel);
$userOrderController = new OrderController($orderService);
$adminOrderController = new AdminOrderController($orderService);
$adminManualOrderController = new ManualOrderController($manualOrderService, $productService, $cartService, $roomModel);
$adminProductController = new ProductController($productService);
$adminUserController = new UserController($userService);
$adminCheckController = new CheckController($checkService, $userModel);

$router->get('/', function (): void {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (isset($_SESSION['role_id']) && (int) $_SESSION['role_id'] === 1) {
        header('Location: /admin/orders');
        exit;
    }

    if (isset($_SESSION['user_id'])) {
        header('Location: /dashboard');
        exit;
    }

    header('Location: /login');
    exit;
});

$router->get('/login', fn() => $authController->showLoginForm());
$router->post('/login', fn() => $authController->login());
$router->get('/logout', fn() => $authController->logout());
$router->get('/forget-password', fn() => $authController->showForgetPasswordForm());
$router->post('/forget-password', fn() => $authController->resetPassword());

$router->get('/dashboard', fn() => $cartController->dashboard());
$router->get('/cart', fn() => $cartController->state());
$router->post('/cart/add', fn() => $cartController->add());
$router->post('/cart/update', fn() => $cartController->update());
$router->post('/cart/remove', fn() => $cartController->remove());
$router->post('/cart/clear', fn() => $cartController->clear());
$router->post('/orders/confirm', fn() => $cartController->confirm());

$router->get('/orders', fn() => $userOrderController->index());
$router->get('/orders/items', fn() => $userOrderController->items((int) ($_GET['order_id'] ?? 0)));
$router->post('/orders/cancel', fn() => $userOrderController->cancel((int) ($_POST['order_id'] ?? 0)));

$router->get('/admin/orders', fn() => $adminOrderController->index());
$router->post('/admin/orders/deliver', fn() => $adminOrderController->deliver((int) ($_POST['order_id'] ?? 0)));
$router->post('/admin/orders/done', fn() => $adminOrderController->done((int) ($_POST['order_id'] ?? 0)));
$router->get('/admin/orders/items', fn() => $adminOrderController->items((int) ($_GET['order_id'] ?? 0)));

$router->get('/admin/manual-order', fn() => $adminManualOrderController->index());
$router->get('/admin/manual-order/search', fn() => $adminManualOrderController->searchUsers());
$router->post('/admin/manual-order/store', fn() => $adminManualOrderController->store());

$router->get('/admin/products', fn() => $adminProductController->index());
$router->get('/admin/products/create', fn() => $adminProductController->create());
$router->post('/admin/products/store', fn() => $adminProductController->store());
$router->get('/admin/products/edit', fn() => $adminProductController->edit((int) ($_GET['id'] ?? 0)));
$router->post('/admin/products/update', fn() => $adminProductController->update((int) ($_POST['id'] ?? 0)));
$router->post('/admin/products/toggle', fn() => $adminProductController->toggleAvailability((int) ($_POST['id'] ?? 0)));
$router->post('/admin/products/delete', fn() => $adminProductController->delete((int) ($_POST['id'] ?? 0)));
$router->post('/admin/categories/store', fn() => $adminProductController->storeCategory());

$router->get('/admin/users', fn() => $adminUserController->index());
$router->get('/admin/users/create', fn() => $adminUserController->create());
$router->post('/admin/users/store', fn() => $adminUserController->store());
$router->get('/admin/users/edit', fn() => $adminUserController->edit((int) ($_GET['id'] ?? 0)));
$router->post('/admin/users/update', fn() => $adminUserController->update((int) ($_POST['id'] ?? 0)));
$router->post('/admin/users/delete', fn() => $adminUserController->delete((int) ($_POST['id'] ?? 0)));

$router->get('/admin/checks', fn() => $adminCheckController->index());
$router->get('/admin/checks/user-orders', fn() => $adminCheckController->userOrders((int) ($_GET['user_id'] ?? 0)));
$router->get('/admin/checks/order-items', fn() => $adminCheckController->orderItems((int) ($_GET['order_id'] ?? 0)));
