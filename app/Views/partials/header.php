<header class="site-header" role="banner">
    <div class="header-inner">
        <a href="<?= defined('BASE_URL') ? BASE_URL . '/' : '/' ?>" class="logo-link">Cafeteria</a>
        <nav class="header-nav" aria-label="Main navigation">
            <?php if (!empty($currentUser)): ?>
            <span class="user-name"><?= htmlspecialchars($currentUser['name'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>
            <a href="<?= defined('BASE_URL') ? BASE_URL . '/logout' : '/logout' ?>" class="btn btn-outline">Logout</a>
            <?php endif; ?>
        </nav>
    </div>
</header>
