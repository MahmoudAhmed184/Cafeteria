<?php

$e = static fn($value) => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
$csrfValue = function_exists('csrf_token') ? csrf_token() : '';
ob_start();
?>
<div class="auth-card">
    <h1 class="auth-title">Forget Password</h1>
    <form class="auth-form" method="post" action="<?= defined('BASE_URL') ? BASE_URL . '/forget-password' : '/forget-password' ?>" novalidate>
        <input type="hidden" name="csrf_token" value="<?= $e($csrfValue) ?>">
        <div class="form-group">
            <label class="form-label" for="email">Email</label>
            <input type="email" id="email" name="email" class="form-control" required autocomplete="email">
        </div>
        <button type="submit" class="btn btn-primary auth-submit">Send Reset Instructions</button>
    </form>
</div>
<?php
$content = ob_get_clean();
$pageTitle = 'Forget Password';
require __DIR__ . '/../layouts/auth.php';
