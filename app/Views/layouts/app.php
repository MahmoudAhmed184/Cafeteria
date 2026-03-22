<?php

$assetBase = defined('BASE_URL') ? rtrim(BASE_URL, '/') : '';
?>

<?php require_once __DIR__ . '/../partials/head.php'; ?>
<body class="bg-background text-on-surface font-body antialiased">
<?php
    if (isset($_SESSION['role_id']) && (int)$_SESSION['role_id'] === 1) {
        require_once __DIR__ . '/../partials/header_admin.php';
    } else {
        require_once __DIR__ . '/../partials/header_user.php';
    }
?>
    <main class="pt-0" role="main">
        <?= $content ?? ''?>
    </main>
    <?php require __DIR__ . '/../partials/toast.php'; ?>
    <script src="<?= $assetBase ? $assetBase . '/' : '/'?>assets/js/app.js"></script>
</body>
</html>
