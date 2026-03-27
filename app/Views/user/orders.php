<?php /* Frontend Polish Pass: unified status pills, destructive button styling, expand animation */

$orders = isset($orders) && is_array($orders) ? $orders : [];
$e = static fn($value) => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');

$active_page = 'orders';
ob_start();
?>
<div class="py-8 px-6 lg:px-8 max-w-[1400px] mx-auto">
    <header class="mb-8">
        <h1 class="text-2xl font-headline font-semibold text-primary">My Orders</h1>
        <p class="text-sm text-on-surface-variant mt-1">Track your cafeteria requests and manage active orders.</p>
    </header>

    <!-- Filters -->
    <form method="GET" class="mb-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="space-y-1.5">
            <label class="block text-xs font-medium text-on-surface-variant uppercase tracking-wide" for="date_from">Date from</label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-[18px]">calendar_today</span>
                <input type="date" id="date_from" name="date_from" value="<?= $e($_GET['date_from'] ?? '') ?>"
                    class="w-full bg-surface-container-low border border-outline-variant/30 rounded-lg py-2.5 pl-10 pr-3.5 text-sm text-on-surface focus:ring-2 focus:ring-primary/20 focus:border-primary/40 transition-all">
            </div>
        </div>
        <div class="space-y-1.5">
            <label class="block text-xs font-medium text-on-surface-variant uppercase tracking-wide" for="date_to">Date to</label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-[18px]">calendar_month</span>
                <input type="date" id="date_to" name="date_to" value="<?= $e($_GET['date_to'] ?? '') ?>"
                    class="w-full bg-surface-container-low border border-outline-variant/30 rounded-lg py-2.5 pl-10 pr-3.5 text-sm text-on-surface focus:ring-2 focus:ring-primary/20 focus:border-primary/40 transition-all">
            </div>
        </div>
    </form>

    <!-- Orders Table -->
    <div class="bg-surface-container-lowest rounded-lg border border-outline-variant/20 overflow-hidden">
        <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-surface-container">
                <tr>
                    <th class="py-3 px-6 text-xs font-semibold text-on-surface-variant uppercase tracking-wide">Order Date</th>
                    <th class="py-3 px-6 text-xs font-semibold text-on-surface-variant uppercase tracking-wide">Status</th>
                    <th class="py-3 px-6 text-xs font-semibold text-on-surface-variant uppercase tracking-wide">Amount</th>
                    <th class="py-3 px-6 text-xs font-semibold text-on-surface-variant uppercase tracking-wide text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant/20">
                <?php if ($orders === []): ?>
                <tr>
                    <td colspan="4" class="py-12 px-6">
                        <div class="empty-state">
                            <span class="material-symbols-outlined empty-state-icon">receipt_long</span>
                            <p class="empty-state-text">No orders found.</p>
                        </div>
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($orders as $order): ?>
                <?php
                    $orderId = (int)($order['id'] ?? 0);
                    $status = $order['status'] ?? '';
                    $statusBadge = match($status) {
                        'Processing'       => 'status-pill status-pill-processing',
                        'Out for delivery' => 'status-pill status-pill-delivery',
                        'Done'             => 'status-pill status-pill-done',
                        'Cancelled'        => 'status-pill status-pill-cancelled',
                        default            => 'status-pill bg-gray-100 text-gray-700 before:bg-gray-400',
                    };
                ?>
                <tr class="group hover:bg-surface-container cursor-pointer order-toggle-row"
                    data-target="order-detail-<?= $orderId ?>">
                    <td class="py-4 px-6">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-on-surface-variant text-[18px] group-hover:text-primary transition-transform duration-200 order-toggle-icon inline-block">add</span>
                            <span class="text-sm text-on-surface"><?= $e($order['created_at'] ?? '') ?></span>
                        </div>
                    </td>
                    <td class="py-4 px-6">
                        <span class="<?= $statusBadge ?>">
                            <?= $e($status) ?>
                        </span>
                    </td>
                    <td class="py-4 px-6">
                        <div class="flex items-baseline gap-1">
                            <span class="text-xs text-secondary font-medium">EGP</span>
                            <span class="text-sm font-semibold text-on-surface"><?= $e(number_format((float)($order['total_amount'] ?? 0), 2)) ?></span>
                        </div>
                    </td>
                    <td class="py-4 px-6 text-right">
                        <?php if ($status === 'Processing'): ?>
                        <form method="post" action="<?= defined('BASE_URL') ? BASE_URL . '/orders/cancel' : '/orders/cancel' ?>">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars((string)csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
                            <input type="hidden" name="order_id" value="<?= $orderId ?>">
                            <button type="submit" class="btn-destructive inline-block"
                                onclick="event.stopPropagation()">Cancel</button>
                        </form>
                        <?php else: ?>
                        <span class="text-xs text-outline italic">—</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <!-- Expandable Details -->
                <tr id="order-detail-<?= $orderId ?>" class="hidden">
                    <td class="p-0" colspan="4">
                        <div class="bg-surface-container-low px-6 py-6 border-t border-outline-variant/10">
                            <?php if (!empty($order['items']) && is_array($order['items'])): ?>
                            <div class="flex flex-wrap gap-4">
                                <?php foreach ($order['items'] as $item): ?>
                                <div class="flex items-center gap-3 bg-surface-container-lowest p-3 rounded-lg border border-outline-variant/15 min-w-[160px]">
                                    <div class="relative w-12 h-12 rounded-md overflow-hidden flex-shrink-0 bg-surface-container-highest">
                                        <?php if (!empty($item['image'])): ?>
                                        <img src="<?= (strpos($item['image'] ?? '', 'http') === 0) ? $item['image'] : '/uploads/' . $item['image'] ?>" alt="<?= $e($item['product_name'] ?? '') ?>" class="w-full h-full object-cover" loading="lazy">
                                        <?php else: ?>
                                        <div class="w-full h-full flex items-center justify-center">
                                            <span class="material-symbols-outlined text-on-surface-variant text-[18px]">coffee</span>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-medium text-on-surface"><?= $e($item['product_name'] ?? '') ?></h4>
                                        <p class="text-xs text-on-surface-variant">Qty: <?= (int)($item['quantity'] ?? 0) ?></p>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <?php else: ?>
                            <p class="text-sm text-on-surface-variant">No item details available.</p>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        </div>
        <footer class="bg-surface-container px-6 py-4 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div></div>
            <?php
            $currentPage = (int) ($_GET['page'] ?? 1);
            $basePath = defined('BASE_URL') ? BASE_URL . '/orders' : '/orders';
            if (!empty($_GET['date_from'])) $basePath .= '?date_from=' . urlencode($_GET['date_from']) . '&date_to=' . urlencode($_GET['date_to'] ?? '');
            require __DIR__ . '/../partials/pagination.php';
            ?>
        </footer>
    </div>
</div>

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
            if (icon) icon.classList.toggle('rotate-45', !hidden);
        });
    });
})();
</script>
<?php
$content = ob_get_clean();
$pageTitle = 'My Orders';
require __DIR__ . '/../layouts/app.php';
