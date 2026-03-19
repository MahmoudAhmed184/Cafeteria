<?php

declare(strict_types=1);

function dd(mixed $data): never
{
    echo '<pre>';
    print_r($data);
    echo '</pre>';
    exit;
}

function redirect(string $url): never
{
    header("Location: {$url}");
    exit;
}

function base_path(string $path = ''): string
{
    $root = defined('APP_ROOT') ? APP_ROOT : dirname(__DIR__);
    return rtrim($root, '/') . ($path !== '' ? '/' . ltrim($path, '/') : '');
}

function asset(string $path = ''): string
{
    $base = defined('BASE_URL') ? rtrim((string) BASE_URL, '/') : '';
    $assetPath = 'assets' . ($path !== '' ? '/' . ltrim($path, '/') : '');
    return ($base !== '' ? $base . '/' : '/') . $assetPath;
}

function view(string $path, ?array $data = []): void
{
    $resolvedPath = trim(str_replace('\\', '/', $path), '/');
    extract($data ?? [], EXTR_SKIP);
    require base_path("app/Views/{$resolvedPath}.php");
}

function old(string $key, string $default = ''): string
{
    $value = $_SESSION['_old'][$key] ?? $default;
    unset($_SESSION['_old'][$key]);
    return (string) $value;
}

function flash_old(array $data): void
{
    unset($data['password'], $data['confirm_password']);
    $_SESSION['_old'] = $data;
}

function e(?string $string): string
{
    return htmlspecialchars((string) ($string ?? ''), ENT_QUOTES, 'UTF-8');
}
