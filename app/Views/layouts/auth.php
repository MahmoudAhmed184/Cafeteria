<?php
/**
 * Minimal layout for login / password-reset pages.
 * Expects: $content (view output), optional $pageTitle.
 */
$assetBase = defined('BASE_URL') ? rtrim(BASE_URL, '/') : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= htmlspecialchars($csrfToken ?? '', ENT_QUOTES, 'UTF-8') ?>">
    <title><?= htmlspecialchars($pageTitle ?? 'Login', ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="<?= $assetBase ? $assetBase . '/' : '' ?>assets/css/style.css">
    <link rel="stylesheet" href="<?= $assetBase ? $assetBase . '/' : '' ?>assets/css/components.css">
    <link rel="stylesheet" href="<?= $assetBase ? $assetBase . '/' : '' ?>assets/css/auth.css">
</head>
<body class="auth-layout">
    <div class="auth-container">
        <?= $content ?? '' ?>
    </div>
    <?php require __DIR__ . '/../partials/toast.php'; ?>
    <script src="<?= $assetBase ? $assetBase . '/' : '' ?>assets/js/app.js"></script>
</body>
</html>
