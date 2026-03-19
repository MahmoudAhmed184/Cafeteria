<?php

declare(strict_types=1);

function generate_csrf_token(): string
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (empty($_SESSION['_csrf_token'])) {
        $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
    }

    return (string) $_SESSION['_csrf_token'];
}

function verify_csrf_token(?string $token): bool
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $sessionToken = $_SESSION['_csrf_token'] ?? '';
    return is_string($token) && $token !== '' && is_string($sessionToken) && hash_equals($sessionToken, $token);
}

function csrf_token(): string
{
    return generate_csrf_token();
}
