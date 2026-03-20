<?php

namespace App\Controllers;

use App\Models\Room;
use App\Services\Contracts\CartServiceInterface;
use App\Services\Contracts\OrderServiceInterface;
use App\Services\Contracts\ProductServiceInterface;
use Exception;

class CartController extends BaseController
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


    public function state(): void
    {
        $this->ensureAuthenticated(true);

        $this->respondJson($this->cartService->getCartState());
    }

    public function add(): void
    {
        $this->ensureAuthenticated(true);

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
        $this->ensureAuthenticated(true);

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
        $this->ensureAuthenticated(true);

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
        $this->ensureAuthenticated(true);

        $this->cartService->clearCart();
        $this->respondJson(['success' => true] + $this->cartService->getCartState());
    }

    public function confirm(): void
    {
        $this->ensureAuthenticated(true);

        $userId = (int) $_SESSION['user_id'];
        $roomNo = trim((string) ($_POST['room_no'] ?? ''));
        $notes = isset($_POST['notes']) ? trim((string) $_POST['notes']) : null;

        if ($roomNo === '') {
            $this->respondJson(['success' => false, 'message' => 'Room is required.'], 422);
        }

        try {
            $orderId = $this->cartService->placeOrder($userId, $roomNo, $notes !== '' ? $notes : null);
            $this->respondJson(['success' => true, 'order_id' => $orderId]);
        } catch (Exception $exception) {
            $this->respondJson(['success' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}
