<?php

$orders = isset($orders) && is_array($orders) ? $orders : [];
$e = static fn($value) => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
ob_start();
?>
<div class="dashboard-header">
    <h1 class="admin-page-title">My Orders</h1>
</div>
<div class="card">
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Date</th>
                <th>Status</th>
                <th>Total</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($orders === []): ?>
            <tr><td colspan="5">No orders found.</td></tr>
            <?php else: ?>
            <?php foreach ($orders as $order): ?>
            <tr>
                <td>#<?= (int) ($order['id'] ?? 0) ?></td>
                <td><?= $e($order['created_at'] ?? '') ?></td>
                <td><?= $e($order['status'] ?? '') ?></td>
                <td><?= $e(number_format((float) ($order['total_amount'] ?? 0), 2)) ?> EGP</td>
                <td>
                    <?php if (($order['status'] ?? '') === 'Processing'): ?>
                    <form method="post" action="<?= defined('BASE_URL') ? BASE_URL . '/orders/cancel' : '/orders/cancel' ?>">
                        <input type="hidden" name="order_id" value="<?= (int) ($order['id'] ?? 0) ?>">
                        <button type="submit" class="btn btn-outline">Cancel</button>
                    </form>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php
$content = ob_get_clean();
$showSidebar = false;
$pageCss = 'dashboard.css';
$pageTitle = 'My Orders';
require __DIR__ . '/../layouts/app.php';
