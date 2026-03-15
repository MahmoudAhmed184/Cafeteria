<?php
/**
 * Latest order widget - FR-CART-011. Mock data; wired during integration.
 * Expects optional $latestOrder (id, created_at, status, total_amount, items).
 */
$latestOrder = $latestOrder ?? null;
$e = function ($s) {
    return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');
};
?>
<div class="latest-order-widget" id="latest-order-widget" aria-label="Latest order">
    <h3 class="latest-order-title">Latest order</h3>
    <div class="latest-order-body">
        <?php if (empty($latestOrder)): ?>
        <p class="latest-order-empty">No recent order.</p>
        <?php else: ?>
        <p class="latest-order-date"><?= $e($latestOrder['created_at'] ?? '') ?></p>
        <p class="latest-order-status">Status: <strong><?= $e($latestOrder['status'] ?? '') ?></strong></p>
        <p class="latest-order-total">Total: <strong><?= $e(number_format((float)($latestOrder['total_amount'] ?? 0), 2)) ?> EGP</strong></p>
        <?php if (!empty($latestOrder['items']) && is_array($latestOrder['items'])): ?>
        <ul class="latest-order-items">
            <?php foreach (array_slice($latestOrder['items'], 0, 3) as $item): ?>
            <li><?= $e($item['name'] ?? '') ?> × <?= (int)($item['quantity'] ?? 0) ?></li>
            <?php endforeach; ?>
            <?php if (count($latestOrder['items']) > 3): ?>
            <li class="latest-order-more">…</li>
            <?php endif; ?>
        </ul>
        <?php endif; ?>
        <?php endif; ?>
    </div>
</div>
