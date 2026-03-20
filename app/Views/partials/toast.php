<div
    id="toast-container"
    class="toast-container"
    aria-live="polite"
    aria-atomic="true"
    role="status"
>
    <?php if (isset($_SESSION['success'])): ?>
        <div class="toast toast-success" role="alert"><?= htmlspecialchars($_SESSION['success'], ENT_QUOTES, 'UTF-8') ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="toast toast-error" role="alert"><?= htmlspecialchars($_SESSION['error'], ENT_QUOTES, 'UTF-8') ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
</div>
