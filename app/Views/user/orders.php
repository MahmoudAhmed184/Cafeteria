<?php

$orders = isset($orders) && is_array($orders) ? $orders : [];
$e = static fn($value) => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');

$active_page = 'orders';
ob_start();
?>
<main class="pt-28 pb-20 px-8 max-w-screen-2xl mx-auto">
    <header class="mb-12">
        <h1 class="text-5xl font-extrabold text-primary tracking-tight mb-2">My Orders</h1>
        <p class="text-on-surface-variant max-w-2xl font-body">Track your cafeteria requests and manage your current active orders.</p>
    </header>

    <!-- Filters Section -->
    <form method="GET" class="mb-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="flex flex-col gap-2">
            <label class="text-xs font-bold uppercase tracking-widest text-secondary opacity-70 ml-1" for="date_from">Date from</label>
            <div class="relative group">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline">calendar_today</span>
                <input type="date" id="date_from" name="date_from" value="<?= $e($_GET['date_from'] ?? '') ?>"
                    class="w-full bg-surface-container-low border-none rounded-lg py-4 pl-12 pr-4 text-on-surface focus:ring-2 focus:ring-tertiary-fixed transition-all outline-none">
            </div>
        </div>
        <div class="flex flex-col gap-2">
            <label class="text-xs font-bold uppercase tracking-widest text-secondary opacity-70 ml-1" for="date_to">Date to</label>
            <div class="relative group">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline">calendar_month</span>
                <input type="date" id="date_to" name="date_to" value="<?= $e($_GET['date_to'] ?? '') ?>"
                    class="w-full bg-surface-container-low border-none rounded-lg py-4 pl-12 pr-4 text-on-surface focus:ring-2 focus:ring-tertiary-fixed transition-all outline-none">
            </div>
        </div>
    </form>

    <!-- Order Table -->
    <div class="bg-surface-container-low rounded-xl overflow-hidden shadow-[0px_12px_32px_rgba(41,24,6,0.04)]">
        <table class="w-full text-left border-collapse">
            <thead class="bg-surface-container text-on-surface-variant">
                <tr>
                    <th class="py-5 px-8 font-bold text-sm tracking-wide">Order Date</th>
                    <th class="py-5 px-8 font-bold text-sm tracking-wide">Status</th>
                    <th class="py-5 px-8 font-bold text-sm tracking-wide">Amount</th>
                    <th class="py-5 px-8 font-bold text-sm tracking-wide text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant/20">
                <?php if ($orders === []): ?>
                <tr>
                    <td colspan="4" class="py-12 px-8 text-center text-on-surface-variant">No orders found.</td>
                </tr>
                <?php else: ?>
                <?php foreach ($orders as $order): ?>
                <?php
                    $orderId = (int)($order['id'] ?? 0);
                    $status = $order['status'] ?? '';
                    $statusBadge = match($status) {
                        'Processing'       => 'bg-tertiary-fixed text-on-tertiary-fixed',
                        'Out for delivery' => 'bg-secondary-container text-on-secondary-container',
                        'Done'             => 'bg-primary text-on-primary',
                        'Cancelled'        => 'bg-error text-on-error',
                        default            => 'bg-surface-container-high text-on-surface-variant',
                    };
                    $statusDot = match($status) {
                        'Processing'       => 'bg-tertiary',
                        'Out for delivery' => 'bg-secondary',
                        'Done'             => 'bg-on-primary',
                        'Cancelled'        => 'bg-on-error',
                        default            => 'bg-outline',
                    };
                ?>
                <!-- Order Row -->
                <tr class="bg-surface-container-lowest group transition-colors hover:bg-surface-container cursor-pointer order-toggle-row"
                    data-target="order-detail-<?= $orderId ?>">
                    <td class="py-6 px-8">
                        <div class="flex items-center gap-4">
                            <span class="material-symbols-outlined text-outline group-hover:text-primary transition-colors order-toggle-icon">add</span>
                            <span class="font-medium text-on-surface"><?= $e($order['created_at'] ?? '') ?></span>
                        </div>
                    </td>
                    <td class="py-6 px-8">
                        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full <?= $statusBadge ?> text-xs font-bold">
                            <span class="w-1.5 h-1.5 rounded-full <?= $statusDot ?>"></span>
                            <?= $e($status) ?>
                        </span>
                    </td>
                    <td class="py-6 px-8">
                        <div class="flex items-baseline gap-1">
                            <span class="text-xs font-semibold text-secondary">EGP</span>
                            <span class="text-lg font-bold text-primary"><?= $e(number_format((float)($order['total_amount'] ?? 0), 2)) ?></span>
                        </div>
                    </td>
                    <td class="py-6 px-8 text-right">
                        <?php if ($status === 'Processing'): ?>
                        <form method="post" action="<?= defined('BASE_URL') ? BASE_URL . '/orders/cancel' : '/orders/cancel' ?>">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars((string)csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
                            <input type="hidden" name="order_id" value="<?= $orderId ?>">
                            <button type="submit" class="bg-error text-on-error px-6 py-2 rounded-lg text-xs font-black tracking-widest hover:opacity-90 active:scale-95 transition-all"
                                onclick="event.stopPropagation()">CANCEL</button>
                        </form>
                        <?php else: ?>
                        <span class="text-outline text-xs italic">No actions available</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <!-- Expandable Row Content -->
                <tr id="order-detail-<?= $orderId ?>" class="bg-surface-container-lowest hidden">
                    <td class="p-0" colspan="4">
                        <div class="bg-surface-container-low/50 px-8 py-8 border-t border-outline-variant/10">
                            <?php if (!empty($order['items']) && is_array($order['items'])): ?>
                            <div class="flex flex-wrap gap-6">
                                <?php foreach ($order['items'] as $item): ?>
                                <div class="flex items-center gap-4 bg-surface-container-lowest p-3 rounded-xl shadow-sm border border-outline-variant/5 min-w-[180px]">
                                    <div class="relative w-16 h-16 rounded-lg overflow-hidden flex-shrink-0 bg-surface-container-highest">
                                        <?php if (!empty($item['image'])): ?>
                                        <img src="<?= $e($item['image']) ?>" alt="<?= $e($item['product_name'] ?? '') ?>" class="w-full h-full object-cover">
                                        <?php else: ?>
                                        <div class="w-full h-full flex items-center justify-center">
                                            <span class="material-symbols-outlined text-outline">coffee</span>
                                        </div>
                                        <?php endif; ?>
                                        <div class="absolute inset-0 bg-primary/10"></div>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-primary text-sm"><?= $e($item['product_name'] ?? '') ?></h4>
                                        <p class="text-xs text-secondary font-medium">Quantity: <?= (int)($item['quantity'] ?? 0) ?></p>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <?php else: ?>
                            <p class="text-on-surface-variant text-sm">No item details available.</p>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        <!-- Table Footer with pagination -->
        <footer class="bg-surface-container px-8 py-6 flex flex-col md:flex-row justify-between items-center gap-6">
            <div></div>
            <?php
            $currentPage = (int) ($_GET['page'] ?? 1);
            $basePath = defined('BASE_URL') ? BASE_URL . '/orders' : '/orders';
            if (!empty($_GET['date_from'])) $basePath .= '?date_from=' . urlencode($_GET['date_from']) . '&date_to=' . urlencode($_GET['date_to'] ?? '');
            require __DIR__ . '/../partials/pagination.php';
            ?>
        </footer>
    </div>
</main>

<script>
(function () {
    'use strict';
    document.querySelectorAll('.order-toggle-row').forEach(function (row) {
        row.addEventListener('click', function () {
            var targetId = row.getAttribute('data-target');
            var detail = document.getElementById(targetId);
            var icon = row.querySelector('.order-toggle-icon');
            if (!detail) return;
            var hidden = detail.classList.toggle('hidden');
            if (icon) icon.textContent = hidden ? 'add' : 'remove';
        });
    });
})();
</script>
<?php
$content = ob_get_clean();
$pageTitle = 'My Orders';
require __DIR__ . '/../layouts/app.php';
