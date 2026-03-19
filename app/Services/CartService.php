<?php

namespace App\Services;

use App\Models\Product;
use App\Services\Contracts\CartServiceInterface;
use Exception;

class CartService implements CartServiceInterface
{
    private Product $productModel;
    private string $sessionKey;

    public function __construct(Product $productModel, string $sessionKey = 'cart')
    {
        $this->productModel = $productModel;
        $this->sessionKey = $sessionKey;
    }

    public function getCartState(): array
    {
        return [
            'items' => array_values($this->getCartItems()),
            'total' => $this->calculateTotal(),
        ];
    }

    public function addItem(int $productId, int $quantity = 1): void
    {
        $this->ensureSession();
        $quantity = max(1, $quantity);

        $product = $this->productModel->findById($productId);
        if (!$product || empty($product['is_available'])) {
            throw new Exception('Product is unavailable.');
        }

        $cart = $this->getCartItems();
        $itemKey = (string) $productId;

        if (isset($cart[$itemKey])) {
            $cart[$itemKey]['quantity'] = (int) $cart[$itemKey]['quantity'] + $quantity;
        } else {
            $cart[$itemKey] = [
                'product_id' => (int) $product['id'],
                'name' => (string) $product['name'],
                'price' => (float) $product['price'],
                'image' => (string) ($product['image'] ?? ''),
                'quantity' => $quantity,
                'line_total' => 0.0,
            ];
        }

        $cart[$itemKey]['line_total'] = (float) $cart[$itemKey]['price'] * (int) $cart[$itemKey]['quantity'];
        $_SESSION[$this->sessionKey] = $cart;
    }

    public function updateItemQuantity(int $productId, int $quantity): void
    {
        $this->ensureSession();

        if ($quantity <= 0) {
            $this->removeItem($productId);
            return;
        }

        $cart = $this->getCartItems();
        $itemKey = (string) $productId;

        if (!isset($cart[$itemKey])) {
            throw new Exception('Item was not found in cart.');
        }

        $cart[$itemKey]['quantity'] = $quantity;
        $cart[$itemKey]['line_total'] = (float) $cart[$itemKey]['price'] * $quantity;
        $_SESSION[$this->sessionKey] = $cart;
    }

    public function removeItem(int $productId): void
    {
        $this->ensureSession();
        $cart = $this->getCartItems();
        unset($cart[(string) $productId]);
        $_SESSION[$this->sessionKey] = $cart;
    }

    public function clearCart(): void
    {
        $this->ensureSession();
        $_SESSION[$this->sessionKey] = [];
    }

    public function validateCart(): bool
    {
        $cart = $this->getCartItems();
        if ($cart === []) {
            return false;
        }

        foreach ($cart as $item) {
            $product = $this->productModel->findById((int) $item['product_id']);
            if (!$product || empty($product['is_available'])) {
                return false;
            }
        }

        return true;
    }

    public function calculateTotal(): float
    {
        $total = 0.0;
        foreach ($this->getCartItems() as $item) {
            $total += (float) ($item['line_total'] ?? 0.0);
        }
        return $total;
    }

    private function ensureSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    private function getCartItems(): array
    {
        $this->ensureSession();

        $items = $_SESSION[$this->sessionKey] ?? [];
        if (!is_array($items)) {
            return [];
        }

        return $items;
    }
}
