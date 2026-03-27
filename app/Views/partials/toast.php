<div id="toast-container" class="toast-container" aria-live="polite" aria-atomic="true" role="status">
    <?php if (isset($_SESSION['success'])): ?>
        <div class="toast toast-success" role="alert">
            <span class="material-symbols-outlined text-lg text-green-600">check_circle</span>
            <span>
                <?= htmlspecialchars($_SESSION['success'], ENT_QUOTES, 'UTF-8') ?>
            </span>
        </div>
        <?php unset($_SESSION['success']); ?>
        <?php
    endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="toast toast-error" role="alert">
            <span class="material-symbols-outlined text-lg text-red-600">error</span>
            <span>
                <?= htmlspecialchars($_SESSION['error'], ENT_QUOTES, 'UTF-8') ?>
            </span>
        </div>
        <?php unset($_SESSION['error']); ?>
        <?php
    endif; ?>
</div>