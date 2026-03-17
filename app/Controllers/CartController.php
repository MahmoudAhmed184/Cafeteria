<?php

namespace App\Controllers;

use App\Models\Room;
use App\Services\Contracts\CartServiceInterface;
use App\Services\Contracts\OrderServiceInterface;
use App\Services\Contracts\ProductServiceInterface;
use Exception;

class CartController
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

    public function dashboard(): void
    {
        if (!$this->ensureAuthenticated(false)) {
            return;
        }

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

    public function state(): void
    {
        if (!$this->ensureAuthenticated(true)) {
            return;
        }

        $this->respondJson($this->cartService->getCartState());
    }

    public function add(): void
    {
        if (!$this->ensureAuthenticated(true)) {
            return;
        }

        $productId = (int) filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);
        $quantity = (int) (filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT) ?: 1);

        if ($productId <= 0) {
            $this->respondJson(['success' => false, 'message' => 'Invalid product.'], 422);
            return;
        }

        try {
            $this->cartService->addItem($productId, $quantity);
            $this->respondJson(['success' => true] + $this->cartService->getCartState());
        } catch (Exception $exception) {
            $this->respondJson(['success' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function update(): void
    {
        if (!$this->ensureAuthenticated(true)) {
            return;
        }

        $productId = (int) filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);
        $quantity = (int) filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT);

        if ($productId <= 0 || $quantity < 0) {
            $this->respondJson(['success' => false, 'message' => 'Invalid cart update.'], 422);
            return;
        }

        try {
            $this->cartService->updateItemQuantity($productId, $quantity);
            $this->respondJson(['success' => true] + $this->cartService->getCartState());
        } catch (Exception $exception) {
            $this->respondJson(['success' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function remove(): void
    {
        if (!$this->ensureAuthenticated(true)) {
            return;
        }

        $productId = (int) filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);
        if ($productId <= 0) {
            $this->respondJson(['success' => false, 'message' => 'Invalid product.'], 422);
            return;
        }

        $this->cartService->removeItem($productId);
        $this->respondJson(['success' => true] + $this->cartService->getCartState());
    }

    public function clear(): void
    {
        if (!$this->ensureAuthenticated(true)) {
            return;
        }

        $this->cartService->clearCart();
        $this->respondJson(['success' => true] + $this->cartService->getCartState());
    }

    public function confirm(): void
    {
        if (!$this->ensureAuthenticated(true)) {
            return;
        }

        $userId = (int) ($_SESSION['user_id'] ?? 0);
        $roomNo = trim((string) ($_POST['room_no'] ?? ''));
        $notes = isset($_POST['notes']) ? trim((string) $_POST['notes']) : null;

        if ($roomNo === '') {
            $this->respondJson(['success' => false, 'message' => 'Room is required.'], 422);
            return;
        }

        if (!$this->cartService->validateCart()) {
            $this->respondJson(['success' => false, 'message' => 'Cart contains invalid items.'], 422);
            return;
        }

        $cartState = $this->cartService->getCartState();
        if ($cartState['items'] === []) {
            $this->respondJson(['success' => false, 'message' => 'Cart is empty.'], 422);
            return;
        }

        $orderItems = array_map(
            static fn(array $item): array => [
                'product_id' => (int) $item['product_id'],
                'quantity' => (int) $item['quantity'],
                'price' => (float) $item['price'],
            ],
            $cartState['items']
        );

        try {
            $orderId = $this->orderService->createOrder(
                $userId,
                $roomNo,
                $notes !== '' ? $notes : null,
                $orderItems,
                (float) $cartState['total']
            );
            $this->cartService->clearCart();
            $this->respondJson(['success' => true, 'order_id' => $orderId]);
        } catch (Exception $exception) {
            $this->respondJson(['success' => false, 'message' => $exception->getMessage()], 500);
        }
    }

    private function ensureAuthenticated(bool $asJson): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            if ($asJson) {
                $this->respondJson(['success' => false, 'message' => 'Unauthorized.'], 401);
                return false;
            }

            header('Location: /login');
            exit;
        }

        if (!$asJson && isset($_SESSION['role_id']) && (int) $_SESSION['role_id'] === 1) {
            header('Location: /admin/orders');
            exit;
        }

        return true;
    }

    private function respondJson(array $payload, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($payload);
        exit;
    }
}
