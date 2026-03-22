<?php
$summary = isset($summary) && is_array($summary) ? $summary : [];
$usersList = isset($usersList) && is_array($usersList) ? $usersList : [];
$e = static fn($value) => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');

$active_page = 'checks';
ob_start();
?>
<main class="max-w-[1440px] mx-auto px-8 py-10">
    <!-- Page Header -->
    <div class="mb-10">
        <h1 class="font-headline text-5xl font-extrabold text-primary tracking-tight mb-2">Financial Report</h1>
        <p class="text-secondary font-medium">Expenditure analysis across all registered staff members.</p>
    </div>

    <!-- Filter Section -->
    <form method="get" action="<?= defined('BASE_URL') ? BASE_URL . '/admin/checks' : '/admin/checks' ?>"
        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <div class="flex flex-col gap-2">
            <label class="text-xs font-bold uppercase tracking-widest text-secondary opacity-70 ml-1" for="date_from">From</label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline">calendar_today</span>
                <input type="date" id="date_from" name="date_from" value="<?= $e($_GET['date_from'] ?? '') ?>"
                    class="w-full bg-surface-container-low border-none rounded-lg py-4 pl-12 pr-4 text-on-surface focus:ring-2 focus:ring-tertiary-fixed transition-all outline-none">
            </div>
        </div>
        <div class="flex flex-col gap-2">
            <label class="text-xs font-bold uppercase tracking-widest text-secondary opacity-70 ml-1" for="date_to">To</label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline">calendar_month</span>
                <input type="date" id="date_to" name="date_to" value="<?= $e($_GET['date_to'] ?? '') ?>"
                    class="w-full bg-surface-container-low border-none rounded-lg py-4 pl-12 pr-4 text-on-surface focus:ring-2 focus:ring-tertiary-fixed transition-all outline-none">
            </div>
        </div>
        <div class="flex flex-col gap-2">
            <label class="text-xs font-bold uppercase tracking-widest text-secondary opacity-70 ml-1" for="user_id">User</label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline">person</span>
                <select id="user_id" name="user_id"
                    class="w-full bg-surface-container-low border-none rounded-lg py-4 pl-12 pr-10 text-on-surface focus:ring-2 focus:ring-tertiary-fixed transition-all outline-none appearance-none !bg-none">
                    <option value="">All users</option>
                    <?php foreach ($usersList as $user): ?>
                    <option value="<?= (int)($user['id'] ?? 0) ?>"
                        <?= ((string)($user['id'] ?? '') === (string)($_GET['user_id'] ?? '')) ? 'selected' : '' ?>>
                        <?= $e($user['name'] ?? '') ?>
                    </option>
                    <?php endforeach; ?>
                </select>
                <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-outline">unfold_more</span>
            </div>
        </div>
        <div class="flex flex-col justify-end">
            <button type="submit"
                class="w-full h-14 bg-gradient-to-br from-primary to-primary-container text-white font-headline font-bold tracking-tight rounded-lg shadow-md hover:opacity-90 active:scale-[0.98] transition-all">
                Filter
            </button>
        </div>
    </form>

    <!-- Results Table -->
    <div class="bg-surface-container-lowest rounded-xl overflow-hidden shadow-[0px_12px_32px_rgba(41,24,6,0.04)] border border-outline-variant/10">
        <table class="w-full text-left border-collapse">
            <thead class="bg-surface-container text-on-surface-variant border-b border-outline-variant/20">
                <tr>
                    <th class="py-5 px-8 font-bold text-sm tracking-wide">User</th>
                    <th class="py-5 px-8 font-bold text-sm tracking-wide text-right">Total Spent</th>
                    <th class="py-5 px-8 font-bold text-sm tracking-wide text-center">Orders</th>
                    <th class="py-5 px-8 font-bold text-sm tracking-wide">Details</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant/10">
                <?php if ($summary === []): ?>
                <tr>
                    <td colspan="4" class="py-12 px-8 text-center text-on-surface-variant">No data found for the selected period.</td>
                </tr>
                <?php else: ?>
                <?php foreach ($summary as $i => $row): ?>
                <?php $rowId = 'checks-row-' . $i; ?>
                <!-- Summary Row (Clickable) -->
                <tr class="bg-surface-container-lowest hover:bg-surface-container cursor-pointer transition-colors check-toggle-row group"
                    data-target="<?= $rowId ?>">
                    <td class="px-8 py-6">
                        <div class="flex items-center gap-4">
                            <span class="material-symbols-outlined text-outline group-hover:text-primary transition-colors check-toggle-icon">add</span>
                            <div class="w-9 h-9 rounded-full bg-surface-container-high flex items-center justify-center">
                                <span class="material-symbols-outlined text-secondary text-sm">account_circle</span>
                            </div>
                            <div>
                                <p class="font-bold text-primary text-sm"><?= $e($row['name'] ?? '') ?></p>
                            </div>
                        </div>
                    </td>
                    <td class="px-8 py-6 text-right">
                        <div class="flex items-baseline justify-end gap-1">
                            <span class="text-[11px] font-semibold text-secondary">EGP</span>
                            <span class="font-headline text-xl font-extrabold text-primary"><?= $e(number_format((float)($row['total_spent'] ?? 0), 2)) ?></span>
                        </div>
                    </td>
                    <td class="px-8 py-6 text-center">
                        <span class="bg-primary/10 text-primary text-xs font-bold px-3 py-1 rounded-full">
                            <?= (int)($row['order_count'] ?? 0) ?> orders
                        </span>
                    </td>
                    <td class="px-8 py-6">
                        <span class="text-secondary/60 text-xs font-bold uppercase tracking-widest">View breakdown</span>
                    </td>
                </tr>
                <!-- Expandable Detail Row -->
                <tr id="<?= $rowId ?>" class="hidden">
                    <td colspan="4" class="p-0">
                        <div class="px-12 py-8 bg-surface-container-low/50 border-t border-outline-variant/10">
                            <?php if (!empty($row['orders']) && is_array($row['orders'])): ?>
                            <div class="space-y-4">
                                <?php foreach ($row['orders'] as $j => $orderRow): ?>
                                <?php $orderRowId = $rowId . '-order-' . $j; ?>
                                <div class="bg-surface-container-lowest rounded-lg p-4 shadow-sm border border-outline-variant/10">
                                    <!-- Order Header (Clickable for Level 2) -->
                                    <div class="flex justify-between items-center cursor-pointer order-toggle-row group" data-target="<?= $orderRowId ?>">
                                        <div class="flex items-center gap-3">
                                            <!-- [+/−] Toggle Icon -->
                                            <span class="material-symbols-outlined text-outline group-hover:text-primary transition-colors order-toggle-icon text-sm">add</span>
                                            <span class="text-xs text-on-surface-variant font-bold tracking-wide uppercase">Order Date</span>
                                            <span class="text-xs text-on-surface font-medium"><?= $e($orderRow['created_at'] ?? '') ?></span>
                                        </div>
                                        <div class="flex items-center gap-4">
                                            <span class="text-xs text-on-surface-variant font-bold tracking-wide uppercase">Amount</span>
                                            <div class="flex items-baseline gap-1">
                                                <span class="text-xs text-secondary font-bold">EGP</span>
                                                <span class="font-bold text-primary text-sm"><?= $e(number_format((float)($orderRow['order_total'] ?? 0), 2)) ?></span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Product Card Grid (Level 2 Expanded) -->
                                    <div id="<?= $orderRowId ?>" class="hidden mt-4 pt-4 border-t border-outline-variant/10">
                                        <?php if (!empty($orderRow['items']) && is_array($orderRow['items'])): ?>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 shadow-inner p-2 rounded-xl bg-surface-container-low/30">
                                            <?php foreach ($orderRow['items'] as $item): ?>
                                            <div class="flex gap-4 p-3 rounded-xl bg-surface-container-lowest shadow-[0_2px_8px_rgba(0,0,0,0.04)] border border-outline-variant/10 hover:shadow-md transition-shadow">
                                                <div class="w-16 h-16 shrink-0 bg-surface-container-low rounded-lg overflow-hidden flex items-center justify-center">
                                                    <?php if(!empty($item['image'])): ?>
                                                    <img src="<?= (strpos($item['image'] ?? '', 'http') === 0) ? $item['image'] : '/uploads/' . $item['image'] ?>" alt="<?= $e($item['product_name'] ?? $item['name'] ?? '') ?>" class="w-full h-full object-cover" />
                                                    <?php else: ?>
                                                    <span class="material-symbols-outlined text-outline">local_cafe</span>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="flex flex-col justify-center flex-grow">
                                                    <p class="text-sm font-bold text-primary leading-tight mb-1 truncate max-w-[140px]"><?= $e($item['product_name'] ?? $item['name'] ?? 'Product') ?></p>
                                                    <div class="flex justify-between items-center w-full">
                                                        <span class="text-xs font-semibold text-secondary whitespace-nowrap"><?= $e(number_format((float)($item['price_at_time_of_order'] ?? 0), 2)) ?> LE × <?= (int)($item['quantity'] ?? 0) ?></span>
                                                        <span class="text-xs font-bold text-primary whitespace-nowrap"><?= $e(number_format(((float)($item['price_at_time_of_order'] ?? 0)) * ((int)($item['quantity'] ?? 0)), 2)) ?> LE</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php endforeach; ?>
                                        </div>
                                        <?php else: ?>
                                        <p class="text-xs text-on-surface-variant italic text-center p-4">No items found for this order.</p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <?php else: ?>
                            <p class="text-on-surface-variant text-sm text-center">No detailed breakdown available.</p>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<script>
(function () {
    'use strict';
    // Level 1: User row toggle
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

    // Level 2: Order row toggle
    document.querySelectorAll('.order-toggle-row').forEach(function (row) {
        row.addEventListener('click', function (e) {
            e.stopPropagation(); // prevent triggering parent clicks if any
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
