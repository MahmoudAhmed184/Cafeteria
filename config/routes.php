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
use App\Controllers\DashboardController;
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
use App\Services\FileUploadService;

require_once __DIR__ . '/../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../middleware/AdminMiddleware.php';

$pdo = database_connection();

$userModel = new User($pdo);
$productModel = new Product($pdo);
$categoryModel = new Category($pdo);
$orderModel = new Order($pdo);
$orderItemModel = new OrderItem($pdo);
$roomModel = new Room($pdo);

$authService = new AuthService($userModel);
$fileUploadService = new FileUploadService();
$productService = new ProductService($productModel, $categoryModel, $fileUploadService);
$orderServiceInstance = new OrderService($pdo, $orderModel, $orderItemModel);
$userService = new UserService($userModel, $fileUploadService);
$checkService = new CheckService($orderServiceInstance);
$cartService = new CartService($productModel, $orderServiceInstance, 'user_cart');
$manualOrderService = new ManualOrderService($userModel, $orderServiceInstance);

$authController = new AuthController($authService);
$dashboardController = new DashboardController($cartService, $productService, $orderServiceInstance, $roomModel);
$cartController = new CartController($cartService, $productService, $orderServiceInstance, $roomModel);
$userOrderController = new OrderController($orderServiceInstance);
$adminOrderController = new AdminOrderController($orderServiceInstance);
$adminManualOrderController = new ManualOrderController($manualOrderService, $productService, $cartService, $roomModel);
$adminProductController = new ProductController($productService);
$adminUserController = new UserController($userService, $roomModel);
$adminCheckController = new CheckController($checkService, $userModel, $orderServiceInstance);

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

$router->get('/dashboard', function() use ($dashboardController) { \AuthMiddleware::handle(); $dashboardController->index(); });
$router->get('/cart', function() use ($cartController) { \AuthMiddleware::handle(); $cartController->state(); });
$router->post('/cart/add', function() use ($cartController) { \AuthMiddleware::handle(); $cartController->add(); });
$router->post('/cart/update', function() use ($cartController) { \AuthMiddleware::handle(); $cartController->update(); });
$router->post('/cart/remove', function() use ($cartController) { \AuthMiddleware::handle(); $cartController->remove(); });
$router->post('/cart/clear', function() use ($cartController) { \AuthMiddleware::handle(); $cartController->clear(); });
$router->post('/orders/confirm', function() use ($cartController) { \AuthMiddleware::handle(); $cartController->confirm(); });

$router->get('/orders', function() use ($userOrderController) { \AuthMiddleware::handle(); $userOrderController->index(); });
$router->get('/orders/items', function() use ($userOrderController) { \AuthMiddleware::handle(); $userOrderController->items((int) ($_GET['order_id'] ?? 0)); });
$router->post('/orders/cancel', function() use ($userOrderController) { \AuthMiddleware::handle(); $userOrderController->cancel((int) ($_POST['order_id'] ?? 0)); });

$router->get('/admin/orders', function() use ($adminOrderController) { \AdminMiddleware::handle(); $adminOrderController->index(); });
$router->post('/admin/orders/deliver', function() use ($adminOrderController) { \AdminMiddleware::handle(); $adminOrderController->deliver((int) ($_POST['order_id'] ?? 0)); });
$router->post('/admin/orders/done', function() use ($adminOrderController) { \AdminMiddleware::handle(); $adminOrderController->done((int) ($_POST['order_id'] ?? 0)); });
$router->get('/admin/orders/items', function() use ($adminOrderController) { \AdminMiddleware::handle(); $adminOrderController->items((int) ($_GET['order_id'] ?? 0)); });

$router->get('/admin/manual-order', function() use ($adminManualOrderController) { \AdminMiddleware::handle(); $adminManualOrderController->index(); });
$router->get('/admin/manual-order/search', function() use ($adminManualOrderController) { \AdminMiddleware::handle(); $adminManualOrderController->searchUsers(); });
$router->post('/admin/manual-order/store', function() use ($adminManualOrderController) { \AdminMiddleware::handle(); $adminManualOrderController->store(); });

$router->get('/admin/products', function() use ($adminProductController) { \AdminMiddleware::handle(); $adminProductController->index(); });
$router->get('/admin/products/create', function() use ($adminProductController) { \AdminMiddleware::handle(); $adminProductController->create(); });
$router->post('/admin/products/store', function() use ($adminProductController) { \AdminMiddleware::handle(); $adminProductController->store(); });
$router->get('/admin/products/edit', function() use ($adminProductController) { \AdminMiddleware::handle(); $adminProductController->edit((int) ($_GET['id'] ?? 0)); });
$router->post('/admin/products/update', function() use ($adminProductController) { \AdminMiddleware::handle(); $adminProductController->update((int) ($_POST['id'] ?? 0)); });
$router->post('/admin/products/toggle', function() use ($adminProductController) { \AdminMiddleware::handle(); $adminProductController->toggleAvailability((int) ($_POST['id'] ?? 0)); });
$router->post('/admin/products/delete', function() use ($adminProductController) { \AdminMiddleware::handle(); $adminProductController->delete((int) ($_POST['id'] ?? 0)); });
$router->post('/admin/categories/store', function() use ($adminProductController) { \AdminMiddleware::handle(); $adminProductController->storeCategory(); });

$router->get('/admin/users', function() use ($adminUserController) { \AdminMiddleware::handle(); $adminUserController->index(); });
$router->get('/admin/users/create', function() use ($adminUserController) { \AdminMiddleware::handle(); $adminUserController->create(); });
$router->post('/admin/users/store', function() use ($adminUserController) { \AdminMiddleware::handle(); $adminUserController->store(); });
$router->get('/admin/users/edit', function() use ($adminUserController) { \AdminMiddleware::handle(); $adminUserController->edit((int) ($_GET['id'] ?? 0)); });
$router->post('/admin/users/update', function() use ($adminUserController) { \AdminMiddleware::handle(); $adminUserController->update((int) ($_POST['id'] ?? 0)); });
$router->post('/admin/users/delete', function() use ($adminUserController) { \AdminMiddleware::handle(); $adminUserController->delete((int) ($_POST['id'] ?? 0)); });

$router->get('/admin/checks', function() use ($adminCheckController) { \AdminMiddleware::handle(); $adminCheckController->index(); });
$router->get('/admin/checks/user-orders', function() use ($adminCheckController) { \AdminMiddleware::handle(); $adminCheckController->userOrders((int) ($_GET['user_id'] ?? 0)); });
$router->get('/admin/checks/order-items', function() use ($adminCheckController) { \AdminMiddleware::handle(); $adminCheckController->orderItems((int) ($_GET['order_id'] ?? 0)); });
