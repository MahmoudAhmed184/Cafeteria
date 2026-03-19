<?php


// Cart Routes
$router->get("/cart", "CartController@index", "cart.index"); //-> only("admin")
$router->post("/cart", "CartController@store", "cart.store");
$router->get("/cart/user", "CartController@user", "cart.user");
