<?php

namespace App\Controllers\Admin;

use App\Models\Room;
use App\Services\Contracts\CartServiceInterface;
use App\Services\Contracts\ManualOrderServiceInterface;
use App\Services\Contracts\ProductServiceInterface;
use Exception;

use App\Controllers\BaseController;

class ManualOrderController extends BaseController
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
        $this->ensureAdmin();

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
        $this->ensureAdmin(true);

        $searchTerm = trim((string) ($_GET['q'] ?? ''));
        $users = $this->manualOrderService->searchUsers($searchTerm);
        $this->respondJson($users);
    }

    public function store(): void
    {
        $this->ensureAdmin(true);

        $userId = (int) filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);
        $roomNo = trim((string) ($_POST['room_no'] ?? ''));
        $notes = isset($_POST['notes']) ? trim((string) $_POST['notes']) : null;

        if ($userId <= 0 || $roomNo === '') {
            $this->respondJson(['success' => false, 'message' => 'User and room are required.'], 422);
        }

        try {
            $orderId = $this->cartService->placeOrder($userId, $roomNo, $notes !== '' ? $notes : null);
            $this->respondJson(['success' => true, 'order_id' => $orderId]);
        } catch (Exception $exception) {
            $this->respondJson(['success' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}
