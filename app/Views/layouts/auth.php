<?php
$assetBase = defined('BASE_URL') ? rtrim(BASE_URL, '/') : '';
?>
<?php require_once __DIR__ . '/../partials/head.php'; ?>

<body class="bg-surface text-on-surface font-body min-h-screen flex items-center justify-center p-6 antialiased">
    <?= $content ?? '' ?>
    <?php require __DIR__ . '/../partials/toast.php'; ?>
    <script src="<?= $assetBase ? $assetBase . '/' : '' ?>assets/js/app.js"></script>
</body>
</html>