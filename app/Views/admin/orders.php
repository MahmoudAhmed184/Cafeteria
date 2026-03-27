<?php

$orders = isset($orders) && is_array($orders) ? $orders : [];
$e = static fn($value) => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');

$active_page = 'orders';
ob_start();
?>
<div class="max-w-[1400px] mx-auto px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-headline font-semibold text-primary">Orders</h1>
            <p class="text-sm text-on-surface-variant mt-1">Monitoring active incoming requests</p>
        </div>
        <div class="flex items-center gap-2 bg-surface-container-low px-4 py-2 rounded-lg border border-outline-variant/15">
            <span class="w-2 h-2 rounded-full bg-tertiary animate-pulse"></span>
            <span class="text-sm font-medium text-primary"><?= count($orders) ?> active orders</span>
        </div>
    </div>

    <!-- Order Cards -->
    <div class="space-y-4">
        <?php if ($orders === []): ?>
        <div class="bg-surface-container-lowest rounded-lg border border-outline-variant/20 p-12">
            <div class="empty-state">
                <span class="material-symbols-outlined empty-state-icon">inbox</span>
                <p class="empty-state-text">No processing orders at this time.</p>
            </div>
        </div>
        <?php else: ?>
        <?php foreach ($orders as $order): ?>
        <?php $orderId = (int)($order['id'] ?? 0); ?>
        <div class="bg-surface-container-lowest rounded-lg border border-outline-variant/20 overflow-hidden">
            <!-- Header Row -->
            <div class="bg-surface-container-high/40 px-6 py-3 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 flex-1">
                    <div class="flex flex-col">
                        <span class="text-xs uppercase tracking-wider text-on-surface-variant font-medium mb-0.5">Order Date</span>
                        <span class="text-sm font-medium text-on-surface"><?= $e($order['created_at'] ?? '') ?></span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xs uppercase tracking-wider text-on-surface-variant font-medium mb-0.5">Name</span>
                        <span class="text-sm font-medium text-on-surface"><?= $e($order['user_name'] ?? '') ?></span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xs uppercase tracking-wider text-on-surface-variant font-medium mb-0.5">Room</span>
                        <span class="text-sm font-medium text-on-surface"><?= $e($order['room_no'] ?? '') ?></span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xs uppercase tracking-wider text-on-surface-variant font-medium mb-0.5">Ext.</span>
                        <span class="text-sm font-medium text-on-surface">#<?= $e($order['ext'] ?? '') ?></span>
                    </div>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    <form method="post" action="<?= defined('BASE_URL') ? BASE_URL . '/admin/orders/deliver' : '/admin/orders/deliver' ?>">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars((string)csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
                        <input type="hidden" name="order_id" value="<?= $orderId ?>">
                        <button type="submit" class="px-5 py-2.5 bg-secondary text-on-secondary font-body font-semibold text-sm rounded-lg hover:bg-secondary-container active:scale-[0.98] transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-secondary/30">
                            Deliver
                        </button>
                    </form>
                    <form method="post" action="<?= defined('BASE_URL') ? BASE_URL . '/admin/orders/done' : '/admin/orders/done' ?>">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars((string)csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
                        <input type="hidden" name="order_id" value="<?= $orderId ?>">
                        <button type="submit" class="px-5 py-2.5 bg-primary text-on-primary font-body font-semibold text-sm rounded-lg hover:bg-primary-container active:scale-[0.98] transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-primary/30">
                            Done
                        </button>
                    </form>
                </div>
            </div>
            <!-- Expand Toggle -->
            <button type="button"
                class="w-full flex items-center justify-between px-6 py-2.5 bg-surface-container/60 border-t border-outline-variant/10 hover:bg-surface-container transition-colors duration-150 order-expand-btn"
                data-target="order-items-<?= $orderId ?>"
                aria-expanded="false">
                <span class="text-xs font-medium text-on-surface-variant">
                    <?= count($order['items'] ?? []) ?> item<?= count($order['items'] ?? []) !== 1 ? 's' : '' ?>
                </span>
                <span class="material-symbols-outlined text-[18px] text-on-surface-variant transition-transform duration-200 order-expand-icon">expand_more</span>
            </button>
            <!-- Product Breakdown (collapsed by default) -->
            <div id="order-items-<?= $orderId ?>" class="hidden">
            <div class="p-6">
                <?php if (!empty($order['items']) && is_array($order['items'])): ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-2">
                    <?php foreach ($order['items'] as $item): ?>
                    <div class="flex items-start gap-2 bg-surface-container-low p-2 rounded-lg border border-outline-variant/20">
                        <div class="w-16 h-16 bg-surface-container rounded-lg overflow-hidden flex-shrink-0">
                            <?php if (!empty($item['image'])): ?>
                            <img src="<?= (strpos($item['image'] ?? '', 'http') === 0) ? $item['image'] : '/uploads/' . $item['image'] ?>" alt="<?= $e($item['name'] ?? '') ?>"
                                class="w-full h-full object-cover" loading="lazy">
                            <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center">
                                <span class="material-symbols-outlined text-on-surface-variant">coffee</span>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="flex flex-col min-w-0">
                            <h4 class="text-xs font-medium text-on-surface leading-tight line-clamp-2"><?= $e($item['name'] ?? '') ?></h4>
                            <span class="text-xs text-secondary font-medium mt-0.5">EGP <?= $e(number_format((float)($item['price_at_time_of_order'] ?? 0), 2)) ?></span>
                            <span class="text-xs text-on-surface-variant font-medium">Qty: <?= (int)($item['quantity'] ?? 0) ?></span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
                <!-- Total -->
                <div class="mt-4 pt-4 border-t border-outline-variant/15 flex justify-end items-baseline gap-1.5">
                    <span class="text-xs text-on-surface-variant font-medium">Total:</span>
                    <span class="text-xs text-secondary font-medium">EGP</span>
                    <span class="text-lg font-semibold text-on-surface"><?= $e(number_format((float)($order['total_amount'] ?? 0), 2)) ?></span>
                </div>
            </div>
            </div>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<script>
(function () {
    'use strict';
    document.querySelectorAll('.order-expand-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var targetId = btn.getAttribute('data-target');
            var panel = document.getElementById(targetId);
            var icon = btn.querySelector('.order-expand-icon');
            if (!panel) return;
            var isHidden = panel.classList.toggle('hidden');
            btn.setAttribute('aria-expanded', isHidden ? 'false' : 'true');
            if (icon) icon.classList.toggle('rotate-180', !isHidden);
        });
    });
})();
</script>

<?php
$content = ob_get_clean();
$pageTitle = 'Manage orders';
require __DIR__ . '/../layouts/app.php';
