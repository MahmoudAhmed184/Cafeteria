<?php

namespace App\Controllers;

use App\Models\Room;
use App\Services\Contracts\CartServiceInterface;
use App\Services\Contracts\OrderServiceInterface;
use App\Services\Contracts\ProductServiceInterface;

class DashboardController extends BaseController
{
    private CartServiceInterface $cartService;
    private ProductServiceInterface $productService;
    private OrderServiceInterface $orderService;
    private Room $roomModel;

    public function __construct(
        CartServiceInterface $cartService,
        ProductServiceInterface $productService,
        OrderServiceInterface $orderService,
        Room $roomModel
    ) {
        $this->cartService = $cartService;
        $this->productService = $productService;
        $this->orderService = $orderService;
        $this->roomModel = $roomModel;
    }

    public function index(): void
    {
        $this->ensureAuthenticated();

        $userId = (int) ($_SESSION['user_id'] ?? 0);
        $products = $this->productService->getAllProducts(true);
        $rooms = $this->roomModel->fetchAll();
        $cartState = $this->cartService->getCartState();
        $cart = $cartState['items'];
        $grandTotal = $cartState['total'];
        $orders = $this->orderService->getUserOrders($userId, null, null, 1, 0);
        $latestOrder = $orders[0] ?? null;
        $currentUser = ['name' => $_SESSION['user_name'] ?? 'User'];

        require_once __DIR__ . '/../Views/user/dashboard.php';
    }
}
