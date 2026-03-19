<?php

class CartController {
    public function index() {
        view("cart/index");
    }

    public function store() {
        $cart = new Cart();
        $cart->user_id = $_POST["user_id"];
        $cart->product_id = $_POST["product_id"];
        $cart->quantity = $_POST["quantity"];
        $cart->save();

        return header("Location: /cart");
    }

    public function user() {
        $cart = new Cart();
        $carts = $cart->where("user_id", $_GET["user_id"])->get();

        return dd($carts);
    }
}