<?php

require_once ROOT . "app/Domains/Cart/Models/Cart.php";

class CartRepository {
    public function getUserCart($userId) {
        $cart = new Cart();
        return $cart->where("user_id", $userId);
    }

    public function addToCart($userId, $productId, $quantity) {
        $cart = new Cart();
        return $cart->create([
            "user_id" => $userId,
            "product_id" => $productId,
            "quantity" => $quantity
        ]);
    }
}