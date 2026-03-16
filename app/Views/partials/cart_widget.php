<?php
/**
 * Cart widget partial. Placeholder for M2 cart.js integration (task 2.5).
 * Expects optional $cart (array of items) or empty; $cart is provided by controller later.
 */
$cart = $cart ?? [];
?>
<div class="cart-widget" id="cart-widget" aria-label="Shopping cart">
    <h3 class="cart-widget-title">Cart</h3>
    <div class="cart-widget-body">
        <?php if (empty($cart)): ?>
        <p class="cart-empty">Your cart is empty.</p>
        <?php else: ?>
        <ul class="cart-items">
            <?php foreach ($cart as $item): ?>
            <li class="cart-item">
                <span class="cart-item-name"><?= htmlspecialchars($item['name'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>
                <span class="cart-item-qty">× <?= (int)($item['quantity'] ?? 0) ?></span>
                <span class="cart-item-total"><?= htmlspecialchars(number_format($item['line_total'] ?? 0, 2), ENT_QUOTES, 'UTF-8') ?> EGP</span>
            </li>
            <?php endforeach; ?>
        </ul>
        <p class="cart-grand-total">Total: <strong><?= htmlspecialchars(number_format($grandTotal ?? 0, 2), ENT_QUOTES, 'UTF-8') ?> EGP</strong></p>
        <?php endif; ?>
    </div>
</div>
