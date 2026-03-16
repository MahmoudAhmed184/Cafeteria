<?php


// Cart Routes
$router->get("/cart", "Cart/index", "cart.index"); //-> only("admin")
$router->post("/cart", "Cart/store", "cart.store");
$router->get("/cart/user", "Cart/user", "cart.user");
