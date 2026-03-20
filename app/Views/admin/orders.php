<?php

$orders = isset($orders) && is_array($orders) ? $orders : [];
$e = static fn($value) => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
ob_start();
?>
<h1 class="admin-page-title">Incoming Orders</h1>
<div class="card">
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Ext</th>
                <th>Room</th>
                <th>Total</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($orders === []): ?>
            <tr><td colspan="7">No processing orders.</td></tr>
            <?php else: ?>
            <?php foreach ($orders as $order): ?>
            <tr>
                <td>#<?= (int) ($order['id'] ?? 0) ?></td>
                <td><?= $e($order['user_name'] ?? '') ?></td>
                <td><?= $e($order['ext'] ?? '') ?></td>
                <td><?= $e($order['room_no'] ?? '') ?></td>
                <td><?= $e(number_format((float) ($order['total_amount'] ?? 0), 2)) ?> EGP</td>
                <td><?= $e($order['status'] ?? '') ?></td>
                <td class="actions">
                    <form method="post" action="<?= defined('BASE_URL') ? BASE_URL . '/admin/orders/deliver' : '/admin/orders/deliver' ?>">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars((string)csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
                        <input type="hidden" name="order_id" value="<?= (int) ($order['id'] ?? 0) ?>">
                        <button type="submit" class="btn btn-outline">Deliver</button>
                    </form>
                    <form method="post" action="<?= defined('BASE_URL') ? BASE_URL . '/admin/orders/done' : '/admin/orders/done' ?>">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars((string)csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
                        <input type="hidden" name="order_id" value="<?= (int) ($order['id'] ?? 0) ?>">
                        <button type="submit" class="btn btn-primary">Done</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php
$content = ob_get_clean();
$showSidebar = true;
$pageCss = 'admin.css';
$pageTitle = 'Incoming Orders';
require __DIR__ . '/../layouts/app.php';
