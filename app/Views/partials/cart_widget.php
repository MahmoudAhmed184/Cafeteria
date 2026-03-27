<?php /* Frontend Polish Pass: pixel matched width, loaded accessible alt tags */

$cart = $cart ?? [];
$grandTotal = $grandTotal ?? 0;
$e = function ($s) {
    return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); };
?>
<div class="flex items-center justify-between mb-6">
    <h2 class="text-lg font-semibold text-primary flex items-center gap-2">
        <span class="material-symbols-outlined text-primary">shopping_bag</span>
        Your Order
    </h2>
    <span
        class="bg-tertiary-container/10 text-on-tertiary-container px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">Draft</span>
</div>
<!-- Cart Items -->
<div class="space-y-4 mb-6" id="cart-items-list">
    <?php if (empty($cart)): ?>
    <p class="text-on-surface-variant text-sm text-center py-4" id="cart-empty-msg">Your cart is empty.</p>
    <?php
else: ?>
    <?php foreach ($cart as $item): ?>
    <div class="flex items-center justify-between group pb-4 border-b border-outline-variant/10"
        data-product-id="<?=(int)($item['product_id'] ?? 0)?>">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-surface-container rounded-lg overflow-hidden flex-shrink-0">
                <?php if (!empty($item['image'])): ?>
                <img src="<?=(strpos($item['image'] ?? '', 'http') === 0) ? $item['image'] : '/uploads/' . $item['image']?>"
                    alt="<?= $e($item['name'] ?? '') ?>" class="w-full h-full object-cover" loading="lazy">
                <?php
        else: ?>
                <span
                    class="material-symbols-outlined text-secondary-fixed-dim w-full h-full flex items-center justify-center">coffee</span>
                <?php
        endif; ?>
            </div>
            <div>
                <p class="text-sm font-medium text-on-surface">
                    <?= $e($item['name'] ?? '')?>
                </p>
                <p class="text-xs text-on-surface-variant">EGP
                    <?= $e(number_format((float)($item['price'] ?? 0), 2))?>
                </p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <div class="flex items-center bg-surface-container-low rounded-lg p-1">
                <button type="button"
                    class="w-6 h-6 flex items-center justify-center hover:bg-surface-container-high rounded transition-all text-primary cart-qty-btn cart-qty-minus"
                    data-product-id="<?=(int)($item['product_id'] ?? 0)?>"
                    data-qty="<?=(int)($item['quantity'] ?? 0)?>" aria-label="Decrease quantity">
                    <span class="material-symbols-outlined text-sm">remove</span>
                </button>
                <span class="px-2 font-bold text-sm min-w-[20px] text-center cart-item-qty">
                    <?=(int)($item['quantity'] ?? 0)?>
                </span>
                <button type="button"
                    class="w-6 h-6 flex items-center justify-center hover:bg-surface-container-high rounded transition-all text-primary cart-qty-btn cart-qty-plus"
                    data-product-id="<?=(int)($item['product_id'] ?? 0)?>"
                    data-qty="<?=(int)($item['quantity'] ?? 0)?>" aria-label="Increase quantity">
                    <span class="material-symbols-outlined text-sm">add</span>
                </button>
            </div>
            <button type="button" class="text-error/40 hover:text-error transition-colors cart-remove-btn"
                data-product-id="<?=(int)($item['product_id'] ?? 0)?>" aria-label="Remove item">
                <span class="material-symbols-outlined text-lg">close</span>
            </button>
        </div>
    </div>
    <?php
    endforeach; ?>
    <?php
endif; ?>
</div>
<!-- Grand Total -->
<div class="flex justify-between items-end pt-4 mt-4 border-t border-outline-variant/20">
    <span class="text-sm font-medium text-on-surface-variant">Grand Total</span>
    <div class="text-right">
        <span class="text-sm font-semibold text-secondary mr-1">EGP</span>
        <span class="text-3xl font-black text-primary" id="cart-grand-total">
            <?= $e(number_format((float)$grandTotal, 2))?>
        </span>
    </div>
</div>