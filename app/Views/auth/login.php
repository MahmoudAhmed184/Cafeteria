<?php
/**
 * Login view - FR-AUTH-001, FR-AUTH-005
 * Expects $errors and $old from controller; falls back to empty arrays.
 */
$errors = $errors ?? [];
$old = $old ?? [];
$e = function ($s) {
    return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');
};

ob_start();
?>
<div class="auth-card">
    <h1 class="auth-title">Login</h1>
    <?php if (!empty($errors)): ?>
    <div class="auth-errors" role="alert">
        <?php if (isset($errors['_general'])): ?>
        <p><?= $e($errors['_general']) ?></p>
        <?php else: ?>
        <ul>
            <?php foreach ($errors as $field => $msg): ?>
            <li><?= $e($msg) ?></li>
            <?php endforeach; ?>
        </ul>
        <?php endif; ?>
    </div>
    <?php endif; ?>
    <form class="auth-form" method="post" action="<?= defined('BASE_URL') ? BASE_URL . '/login' : '/login' ?>" novalidate>
        <input type="hidden" name="csrf_token" value="<?= $e($csrfToken ?? '') ?>">
        <div class="form-group">
            <label class="form-label" for="email">Email</label>
            <input type="email" id="email" name="email" class="form-control <?= !empty($errors['email']) ? 'is-invalid' : '' ?>" value="<?= $e($old['email'] ?? '') ?>" required autocomplete="email">
            <?php if (!empty($errors['email'])): ?>
            <span class="form-error"><?= $e($errors['email']) ?></span>
            <?php endif; ?>
        </div>
        <div class="form-group">
            <label class="form-label" for="password">Password</label>
            <input type="password" id="password" name="password" class="form-control <?= !empty($errors['password']) ? 'is-invalid' : '' ?>" required autocomplete="current-password">
            <?php if (!empty($errors['password'])): ?>
            <span class="form-error"><?= $e($errors['password']) ?></span>
            <?php endif; ?>
        </div>
        <button type="submit" class="btn btn-primary auth-submit">Login</button>
    </form>
    <p class="auth-footer">
        <a href="<?= defined('BASE_URL') ? BASE_URL . '/forget-password' : '/forget-password' ?>">Forget Password?</a>
    </p>
</div>
<?php
$content = ob_get_clean();
$pageTitle = 'Login';
require __DIR__ . '/../layouts/auth.php';
