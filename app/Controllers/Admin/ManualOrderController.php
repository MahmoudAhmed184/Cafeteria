<?php

namespace App\Controllers\Admin;

use App\Models\Room;
use App\Services\Contracts\CartServiceInterface;
use App\Services\Contracts\ManualOrderServiceInterface;
use App\Services\Contracts\ProductServiceInterface;
use Exception;

class ManualOrderController
{
    private ManualOrderServiceInterface $manualOrderService;
    private ProductServiceInterface $productService;
    private CartServiceInterface $cartService;
    private Room $roomModel;

    public function __construct(
        ManualOrderServiceInterface $manualOrderService,
        ProductServiceInterface $productService,
        CartServiceInterface $cartService,
        Room $roomModel
    ) {
        $this->manualOrderService = $manualOrderService;
        $this->productService = $productService;
        $this->cartService = $cartService;
        $this->roomModel = $roomModel;
    }

    public function index(): void
    {
        if (!$this->ensureAdmin(false)) {
            return;
        }

        $users = $this->manualOrderService->searchUsers('');
        $products = $this->productService->getAllProducts(true);
        $rooms = $this->roomModel->fetchAll();
        $cartState = $this->cartService->getCartState();
        $cart = $cartState['items'];
        $grandTotal = $cartState['total'];
        $currentUser = ['name' => $_SESSION['user_name'] ?? 'Admin'];

        require_once __DIR__ . '/../../Views/admin/manual_order.php';
    }

    public function searchUsers(): void
    {
        if (!$this->ensureAdmin(true)) {
            return;
        }

        $searchTerm = trim((string) ($_GET['q'] ?? ''));
        $users = $this->manualOrderService->searchUsers($searchTerm);
        $this->respondJson($users);
    }

    public function store(): void
    {
        if (!$this->ensureAdmin(true)) {
            return;
        }

        $userId = (int) filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);
        $roomNo = trim((string) ($_POST['room_no'] ?? ''));
        $notes = isset($_POST['notes']) ? trim((string) $_POST['notes']) : null;

        if ($userId <= 0 || $roomNo === '') {
            $this->respondJson(['success' => false, 'message' => 'User and room are required.'], 422);
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
            $orderId = $this->manualOrderService->placeOrderForUser(
                $userId,
                $roomNo,
                $notes !== '' ? $notes : null,
                $orderItems,
                (float) $cartState['total']
            );
            $this->cartService->clearCart();
            $this->respondJson(['success' => true, 'order_id' => $orderId]);
        } catch (Exception $exception) {
            $this->respondJson(['success' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    private function ensureAdmin(bool $asJson): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $isAuthenticated = isset($_SESSION['user_id']);
        $isAdmin = isset($_SESSION['role_id']) && (int) $_SESSION['role_id'] === 1;

        if ($isAuthenticated && $isAdmin) {
            return true;
        }

        if ($asJson) {
            $this->respondJson(['success' => false, 'message' => 'Unauthorized.'], 403);
            return false;
        }

        if (!$isAuthenticated) {
            header('Location: /login');
            exit;
        }

        header('Location: /dashboard');
        exit;
    }

    private function respondJson(array $payload, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($payload);
        exit;
    }
}
