<div
    id="toast-container"
    class="toast-container"
    aria-live="polite"
    aria-atomic="true"
    role="status"
>
    <?php if (isset($_SESSION['success'])): ?>
        <div class="toast toast-success" role="alert">
            <span class="material-symbols-outlined text-xl" style="color: #2e7d32;">check_circle</span>
            <span><?= htmlspecialchars($_SESSION['success'], ENT_QUOTES, 'UTF-8') ?></span>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="toast toast-error" role="alert">
            <span class="material-symbols-outlined text-xl" style="color: #d32f2f;">error</span>
            <span><?= htmlspecialchars($_SESSION['error'], ENT_QUOTES, 'UTF-8') ?></span>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
</div>
