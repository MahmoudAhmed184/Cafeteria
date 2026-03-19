<?php

require_once ROOT . "app/Domains/Cart/Repository/CartRepository.php";

class CartService {
    private $cartRepository;

    public function __construct() {
        $this->cartRepository = new CartRepository();
    }

    public function index() {
        view("Cart/index");
    }

    public function store() {
        // Logic to add a product to the cart
    }

    public function user() {
        // Logic to get the current user's cart
    }
}