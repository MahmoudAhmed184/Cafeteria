<header class="site-header" role="banner">
    <div class="header-inner">
        <a href="<?= defined('BASE_URL') ? BASE_URL . '/' : '/' ?>" class="logo-link">Cafeteria</a>
        <nav class="header-nav" aria-label="Main navigation">
            <?php
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $isLoggedIn = !empty($_SESSION['user_id']);
            $isAdmin = isset($_SESSION['role_id']) && (int) $_SESSION['role_id'] === 1;
            ?>
            <?php if ($isLoggedIn && !$isAdmin): ?>
                <a href="<?= defined('BASE_URL') ? BASE_URL . '/dashboard' : '/dashboard' ?>"
                    class="header-nav-link">Dashboard</a>
                <a href="<?= defined('BASE_URL') ? BASE_URL . '/orders' : '/orders' ?>" class="header-nav-link">My
                    Orders</a>
            <?php endif; ?>
            <?php if (!empty($currentUser)): ?>
                <span class="user-name"><?= htmlspecialchars($currentUser['name'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>
                <a href="<?= defined('BASE_URL') ? BASE_URL . '/logout' : '/logout' ?>" class="btn btn-outline">Logout</a>
            <?php endif; ?>
        </nav>
    </div>
</header>