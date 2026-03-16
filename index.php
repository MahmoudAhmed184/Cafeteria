<?php

define("ROOT", "./");
require_once ROOT . "app/Core/Helpers.php";
require_once ROOT . "app/Core/Router.php";


$URI = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$METHOD = $_POST["_method"] ?? $_SERVER["REQUEST_METHOD"];



$router = Router::create();
require_once ROOT . "routes.php";


try {
    $router->route($METHOD, $URI);
} catch(\Exception) {
    dd("Fail");
}