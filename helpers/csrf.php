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
    $match = is_string($token) && $token !== '' && is_string($sessionToken) && hash_equals($sessionToken, $token);
    
    if (!$match) {
        error_log("verify_csrf_token failed: token=" . ($token ?: 'NULL') . ", session=" . ($sessionToken ?: 'NULL'));
    }
    
    return $match;
}

function csrf_token(): string
{
    return generate_csrf_token();
}
