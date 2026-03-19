<?php

require_once ROOT . "app/Models/Model.php";

class Cart extends Model {
    protected $table = "carts";
    protected $columns = ["user_id", "product_id", "quantity"];
}