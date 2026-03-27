<?php
$summary = isset($summary) && is_array($summary) ? $summary : [];
$usersList = isset($usersList) && is_array($usersList) ? $usersList : [];
$e = static fn($value) => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');

$active_page = 'checks';
ob_start();
?>
<div class="max-w-[1400px] mx-auto px-6 lg:px-8 py-8">
    <header class="mb-8">
        <h1 class="text-2xl font-headline font-semibold text-primary">Financial Report</h1>
        <p class="text-sm text-on-surface-variant mt-1">Expenditure analysis across all staff members</p>
    </header>

    <!-- Filters -->
    <form method="get" action="<?= defined('BASE_URL') ? BASE_URL . '/admin/checks' : '/admin/checks' ?>"
        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="space-y-1.5">
            <label class="block text-xs font-medium text-on-surface-variant uppercase tracking-wide"
                for="date_from">From</label>
            <div class="relative">
                <span
                    class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-[18px]">calendar_today</span>
                <input type="date" id="date_from" name="date_from" value="<?= $e($_GET['date_from'] ?? '') ?>"
                    class="w-full bg-surface-container-low border border-outline-variant/30 rounded-lg py-2.5 pl-10 pr-3.5 text-sm text-on-surface focus:ring-2 focus:ring-primary/20 focus:border-primary/40 transition-all">
            </div>
        </div>
        <div class="space-y-1.5">
            <label class="block text-xs font-medium text-on-surface-variant uppercase tracking-wide"
                for="date_to">To</label>
            <div class="relative">
                <span
                    class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-[18px]">calendar_month</span>
                <input type="date" id="date_to" name="date_to" value="<?= $e($_GET['date_to'] ?? '') ?>"
                    class="w-full bg-surface-container-low border border-outline-variant/30 rounded-lg py-2.5 pl-10 pr-3.5 text-sm text-on-surface focus:ring-2 focus:ring-primary/20 focus:border-primary/40 transition-all">
            </div>
        </div>
        <div class="space-y-1.5">
            <label class="block text-xs font-medium text-on-surface-variant uppercase tracking-wide"
                for="user_id">User</label>
            <select id="user_id" name="user_id"
                class="w-full bg-surface-container-low border border-outline-variant/30 rounded-lg py-2.5 px-3.5 text-sm text-on-surface focus:ring-2 focus:ring-primary/20 focus:border-primary/40 focus:outline-none transition-all custom-select">
                <option value="">All users</option>
                <?php foreach ($usersList as $user): ?>
                    <option value="<?= (int) ($user['id'] ?? 0) ?>" <?= ((string) ($user['id'] ?? '') === (string) ($_GET['user_id'] ?? '')) ? 'selected' : '' ?>>
                        <?= $e($user['name'] ?? '') ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="flex flex-col justify-end items-start">
            <button type="submit"
                class="w-auto px-6 py-2.5 bg-primary text-on-primary font-body font-semibold text-sm rounded-lg hover:bg-primary-container active:scale-[0.99] transition-all">
                Filter
            </button>
        </div>
    </form>

    <!-- Results Table -->
    <div class="bg-surface-container-lowest rounded-lg border border-outline-variant/20 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-surface-container">
                    <tr>
                        <th class="py-3 px-6 text-xs font-semibold text-on-surface-variant uppercase tracking-wide">User
                        </th>
                        <th
                            class="py-3 px-6 text-xs font-semibold text-on-surface-variant uppercase tracking-wide text-right">
                            Total Spent</th>
                        <th
                            class="py-3 px-6 text-xs font-semibold text-on-surface-variant uppercase tracking-wide text-center">
                            Orders</th>
                        <th class="py-3 px-6 text-xs font-semibold text-on-surface-variant uppercase tracking-wide">
                            Details</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/15">
                    <?php if ($summary === []): ?>
                        <tr>
                            <td colspan="4" class="py-12 px-6">
                                <div class="empty-state">
                                    <span class="material-symbols-outlined empty-state-icon">query_stats</span>
                                    <p class="empty-state-text">No data found for the selected period.</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($summary as $i => $row): ?>
                            <?php $rowId = 'checks-row-' . $i; ?>
                            <tr class="hover:bg-surface-container-low duration-150 cursor-pointer transition-colors check-toggle-row group"
                                data-target="<?= $rowId ?>">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <span
                                            class="material-symbols-outlined text-on-surface-variant text-[18px] group-hover:text-primary transition-colors check-toggle-icon">add</span>
                                        <div
                                            class="w-8 h-8 rounded-full bg-surface-container-highest flex items-center justify-center overflow-hidden shrink-0">
                                            <?php if (!empty($row['profile_pic'])): ?>
                                                <img src="<?= (strpos($row['profile_pic'] ?? '', 'http') === 0) ? $row['profile_pic'] : '/uploads/' . $row['profile_pic'] ?>"
                                                    alt="<?= $e($row['name'] ?? '') ?>" class="w-full h-full object-cover">
                                            <?php else: ?>
                                                <span
                                                    class="material-symbols-outlined text-on-surface-variant text-[18px]">person</span>
                                            <?php endif; ?>
                                        </div>
                                        <span class="text-sm font-medium text-on-surface"><?= $e($row['name'] ?? '') ?></span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-baseline justify-end gap-1">
                                        <span class="text-xs text-secondary font-medium">EGP</span>
                                        <span
                                            class="text-sm font-semibold text-on-surface"><?= $e(number_format((float) ($row['total_spent'] ?? 0), 2)) ?></span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="inline-flex bg-surface-container-highest text-on-surface-variant text-xs font-medium px-2.5 py-1 rounded-full">
                                        <?= (int) ($row['order_count'] ?? 0) ?> orders
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-xs text-on-surface-variant font-medium">View breakdown</span>
                                </td>
                            </tr>
                            <!-- Expandable Detail Row -->
                            <tr id="<?= $rowId ?>" class="hidden">
                                <td colspan="4" class="p-0">
                                    <div class="px-6 py-6 bg-surface-container-low border-t border-outline-variant/10">
                                        <?php if (!empty($row['orders']) && is_array($row['orders'])): ?>
                                            <div class="space-y-3">
                                                <?php foreach ($row['orders'] as $j => $orderRow): ?>
                                                    <?php $orderRowId = $rowId . '-order-' . $j; ?>
                                                    <div
                                                        class="bg-surface-container-lowest rounded-lg border border-outline-variant/15 p-4">
                                                        <!-- Order Header -->
                                                        <div class="flex justify-between items-center cursor-pointer order-toggle-row group"
                                                            data-target="<?= $orderRowId ?>">
                                                            <div class="flex items-center gap-2">
                                                                <span
                                                                    class="material-symbols-outlined text-on-surface-variant text-[18px] group-hover:text-primary transition-colors order-toggle-icon">add</span>
                                                                <span
                                                                    class="text-xs text-on-surface-variant font-medium uppercase tracking-wide">Order
                                                                    Date</span>
                                                                <span
                                                                    class="text-xs text-on-surface"><?= $e($orderRow['created_at'] ?? '') ?></span>
                                                            </div>
                                                            <div class="flex items-baseline gap-1">
                                                                <span class="text-xs text-secondary font-medium">EGP</span>
                                                                <span
                                                                    class="text-sm font-semibold text-on-surface"><?= $e(number_format((float) ($orderRow['order_total'] ?? 0), 2)) ?></span>
                                                            </div>
                                                        </div>

                                                        <!-- Product Items -->
                                                        <div id="<?= $orderRowId ?>"
                                                            class="hidden mt-3 pt-3 border-t border-outline-variant/10">
                                                            <?php if (!empty($orderRow['items']) && is_array($orderRow['items'])): ?>
                                                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                                                                    <?php foreach ($orderRow['items'] as $item): ?>
                                                                        <div
                                                                            class="flex gap-3 p-3 rounded-lg bg-surface-container-low border border-outline-variant/10">
                                                                            <div
                                                                                class="w-12 h-12 shrink-0 bg-surface-container rounded-md overflow-hidden flex items-center justify-center">
                                                                                <?php if (!empty($item['image'])): ?>
                                                                                    <img src="<?= (strpos($item['image'] ?? '', 'http') === 0) ? $item['image'] : '/uploads/' . $item['image'] ?>"
                                                                                        alt="<?= $e($item['product_name'] ?? $item['name'] ?? '') ?>"
                                                                                        class="w-full h-full object-cover" loading="lazy" />
                                                                                <?php else: ?>
                                                                                    <span
                                                                                        class="material-symbols-outlined text-on-surface-variant text-[16px]">local_cafe</span>
                                                                                <?php endif; ?>
                                                                            </div>
                                                                            <div class="flex flex-col justify-center min-w-0">
                                                                                <p
                                                                                    class="text-sm font-medium text-on-surface leading-tight truncate">
                                                                                    <?= $e($item['product_name'] ?? $item['name'] ?? 'Product') ?>
                                                                                </p>
                                                                                <div class="flex items-center gap-2 mt-0.5">
                                                                                    <span
                                                                                        class="text-xs text-on-surface-variant"><?= $e(number_format((float) ($item['price_at_time_of_order'] ?? 0), 2)) ?>
                                                                                        × <?= (int) ($item['quantity'] ?? 0) ?></span>
                                                                                    <span
                                                                                        class="text-xs font-medium text-on-surface"><?= $e(number_format(((float) ($item['price_at_time_of_order'] ?? 0)) * ((int) ($item['quantity'] ?? 0)), 2)) ?></span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    <?php endforeach; ?>
                                                                </div>
                                                            <?php else: ?>
                                                                <p class="text-xs text-on-surface-variant text-center py-2">No items found
                                                                    for this order.</p>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php else: ?>
                                            <p class="text-sm text-on-surface-variant text-center">No detailed breakdown available.
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    (function () {
        'use strict';
        document.querySelectorAll('.check-toggle-row').forEach(function (row) {
            row.addEventListener('click', function () {
                var targetId = row.getAttribute('data-target');
                var detail = document.getElementById(targetId);
                var icon = row.querySelector('.check-toggle-icon');
                if (!detail) return;
                var hidden = detail.classList.toggle('hidden');
                if (icon) icon.textContent = hidden ? 'add' : 'remove';
            });
        });

        document.querySelectorAll('.order-toggle-row').forEach(function (row) {
            row.addEventListener('click', function (e) {
                e.stopPropagation();
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
$pageTitle = 'Checks';
require __DIR__ . '/../layouts/app.php';
