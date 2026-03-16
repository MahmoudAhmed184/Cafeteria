<?php

declare(strict_types=1);

function dd($data)
{
    echo "<pre>";
    print_r($data);
    echo "</pre>";
    die();
}

function redirect(string $url)
{
    header("Location: $url");
    exit();
}

function base_path(string $path)
{
    return ROOT . $path;
}

function asset(string $path)
{
    return "/project/cafeteria/public/assets/" . $path;
}

function view(string $path, ?array $data = [])
{
    $domain = explode("/", $path)[0];
    $view = explode("/", $path)[1];
    extract($data);
    require_once base_path("app/Domains/$domain/Views/$view.php");
    return;
}

function old(string $key, string $default = '')
{
    $value = $_SESSION['_old'][$key] ?? $default;
    unset($_SESSION['_old'][$key]);
    return $value;
}

function flash_old(array $data)
{
    unset($data['password']);
    unset($data['confirm_password']);
    $_SESSION['_old'] = $data;
}

function e(?string $string): string
{
    if ($string === null)
        return '';
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}