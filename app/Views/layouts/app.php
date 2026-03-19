<?php
/**
 * Main application layout.
 * Expects: $content (view path or buffered output), optional $showSidebar (bool), optional $pageCss (string, e.g. 'dashboard.css')
 * CSRF token placeholder: add <meta name="csrf-token" content="<?= $csrfToken ?? '' ?>"> when helpers available.
 */
$pageCss = $pageCss ?? '';
$showSidebar = $showSidebar ?? false;
$assetBase = defined('BASE_URL') ? rtrim(BASE_URL, '/') : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= htmlspecialchars($csrfToken ?? '', ENT_QUOTES, 'UTF-8') ?>">
    <title><?= htmlspecialchars($pageTitle ?? 'Cafeteria Management System', ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="<?= $assetBase ? $assetBase . '/' : '' ?>assets/css/style.css">
    <link rel="stylesheet" href="<?= $assetBase ? $assetBase . '/' : '' ?>assets/css/components.css">
    <?php if ($pageCss): ?>
    <link rel="stylesheet" href="<?= $assetBase ? $assetBase . '/' : '' ?>assets/css/<?= htmlspecialchars($pageCss, ENT_QUOTES, 'UTF-8') ?>">
    <?php endif; ?>
</head>
<body class="app-layout <?= $showSidebar ? 'with-sidebar' : '' ?>">
    <?php require __DIR__ . '/../partials/header.php'; ?>
    <div class="app-body">
        <?php if ($showSidebar): ?>
        <?php require __DIR__ . '/../partials/sidebar.php'; ?>
        <?php endif; ?>
        <main class="main-content" role="main">
            <?= $content ?? '' ?>
        </main>
    </div>
    <?php require __DIR__ . '/../partials/footer.php'; ?>
    <?php require __DIR__ . '/../partials/toast.php'; ?>
    <script src="<?= $assetBase ? $assetBase . '/' : '' ?>assets/js/app.js"></script>
</body>
</html>
