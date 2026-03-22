<?php

$latestOrder = $latestOrder ?? null;
$e = function ($s) {
    return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
};
?>
<?php if (!empty($latestOrder)): ?>
<div class="mt-6">
    <div class="flex items-center gap-4 mb-6 mt-6">
        <h2 class="text-xl font-extrabold text-primary tracking-tight">Latest Order</h2>
        <div class="h-px flex-grow bg-outline-variant/20"></div>
    </div>
    <div class="flex gap-4 overflow-x-auto pb-4 scrollbar-hide" id="latest-order-widget" aria-label="Latest order">
        <?php if (!empty($latestOrder['items']) && is_array($latestOrder['items'])): ?>
            <?php foreach ($latestOrder['items'] as $item): ?>
            <div class="flex-shrink-0 flex items-center gap-4 bg-surface-container-lowest p-4 rounded-lg border border-outline-variant/10 w-64 h-24 hover:shadow-md transition-shadow cursor-pointer">
                <?php if (!empty($item['image'])): ?>
                <img src="<?= $e($item['image']) ?>" alt="<?= $e($item['name'] ?? '') ?>" class="w-16 h-16 rounded-lg object-cover flex-shrink-0">
                <?php else: ?>
                <div class="w-16 h-16 rounded-lg bg-surface-container-high flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined text-outline">coffee</span>
                </div>
                <?php endif; ?>
                <div class="flex flex-col justify-center min-w-0">
                    <h3 class="font-bold text-primary text-sm leading-tight truncate"><?= $e($item['name'] ?? '') ?></h3>
                    <p class="text-xs text-secondary font-medium mt-0.5">EGP <?= $e(number_format((float)($item['price_at_time_of_order'] ?? 0), 2)) ?></p>
                    <p class="text-xs text-on-surface-variant">Qty: <?= (int)($item['quantity'] ?? 0) ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
        <p class="text-on-surface-variant text-sm" id="latest-order-empty">No items in latest order.</p>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>