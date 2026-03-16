<?php

declare(strict_types=1);

// for debug
function dd($data) {
    echo "<pre>";
    print_r($data);
    echo "<pre>";
    return;
}

function base_path($path) {
    return ROOT . $path;
}

function view(string $path, ?array $data = []) {
    $domain = explode("/", $path)[0];
    $view = explode("/", $path)[1];    
    extract($data);

    require_once base_path("app/Domains/$domain/Views/$view.php");
    return;
}