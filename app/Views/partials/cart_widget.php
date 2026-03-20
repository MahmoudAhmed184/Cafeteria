<?php
/**
 * Cart widget partial. Placeholder for M2 cart.js integration (task 2.5).
 * Expects optional $cart (array of items) or empty; $cart is provided by controller later.
 */
$cart = $cart ?? [];
?>
<div class="cart-widget" id="cart-widget" aria-label="Shopping cart">
    <h3 class="cart-widget-title">Cart</h3>
    <div class="cart-widget-body" id="cart-widget-body">
        <?php if (empty($cart)): ?>
        <p class="cart-empty" id="cart-empty-msg">Your cart is empty.</p>
        <?php else: ?>
        <ul class="cart-items" id="cart-items-list">
            <?php foreach ($cart as $item): ?>
            <li class="cart-item" data-product-id="<?= (int)($item['product_id'] ?? 0) ?>">
                <div class="cart-item-info">
                    <span class="cart-item-name"><?= htmlspecialchars($item['name'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>
                    <span class="cart-item-price"><?= htmlspecialchars(number_format($item['price'] ?? 0, 2), ENT_QUOTES, 'UTF-8') ?> EGP</span>
                </div>
                <div class="cart-item-controls">
                    <button type="button" class="btn btn-sm cart-qty-btn cart-qty-minus" data-product-id="<?= (int)($item['product_id'] ?? 0) ?>" data-qty="<?= (int)($item['quantity'] ?? 0) ?>" title="Decrease quantity">−</button>
                    <span class="cart-item-qty"><?= (int)($item['quantity'] ?? 0) ?></span>
                    <button type="button" class="btn btn-sm cart-qty-btn cart-qty-plus" data-product-id="<?= (int)($item['product_id'] ?? 0) ?>" data-qty="<?= (int)($item['quantity'] ?? 0) ?>" title="Increase quantity">+</button>
                    <span class="cart-item-total"><?= htmlspecialchars(number_format($item['line_total'] ?? 0, 2), ENT_QUOTES, 'UTF-8') ?> EGP</span>
                    <button type="button" class="btn btn-sm cart-remove-btn" data-product-id="<?= (int)($item['product_id'] ?? 0) ?>" title="Remove item">✕</button>
                </div>
            </li>
            <?php endforeach; ?>
        </ul>
        <p class="cart-grand-total">Total: <strong id="cart-grand-total"><?= htmlspecialchars(number_format($grandTotal ?? 0, 2), ENT_QUOTES, 'UTF-8') ?> EGP</strong></p>
        <button type="button" class="btn btn-sm btn-outline" id="cart-clear-btn">Clear Cart</button>
        <?php endif; ?>
    </div>
</div>
