<?php

$orders = isset($orders) && is_array($orders) ? $orders : [];
$e = static fn($value) => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');

$active_page = 'orders';
ob_start();
?>
<main class="max-w-[1400px] mx-auto px-8 py-10">
    <!-- Dashboard Header -->
    <div class="flex items-end justify-between mb-12">
        <div>
            <h1 class="font-headline text-5xl font-extrabold text-primary tracking-tight mb-2">Orders</h1>
            <p class="text-secondary font-medium italic">Monitoring active incoming requests in real-time</p>
        </div>
        <div class="flex gap-4">
            <div class="bg-surface-container-low px-4 py-2 rounded shadow-sm flex items-center gap-2 border border-outline-variant/15">
                <span class="w-2 h-2 rounded-full bg-tertiary animate-pulse"></span>
                <span class="text-sm font-bold text-primary"><?= count($orders) ?> ACTIVE ORDERS</span>
            </div>
        </div>
    </div>

    <!-- Order Cards -->
    <div class="space-y-6">
        <?php if ($orders === []): ?>
        <div class="bg-surface-container-lowest rounded shadow-sm p-12 text-center text-on-surface-variant">
            No processing orders at this time.
        </div>
        <?php else: ?>
        <?php foreach ($orders as $order): ?>
        <?php $orderId = (int)($order['id'] ?? 0); ?>
        <div class="bg-surface-container-lowest rounded shadow-[0px_12px_32px_rgba(41,24,6,0.04)] overflow-hidden transition-all hover:shadow-[0px_12px_32px_rgba(41,24,6,0.08)]">
            <!-- Header Row -->
            <div class="bg-surface-container-high/40 px-8 py-4 flex items-center justify-between">
                <div class="grid grid-cols-4 flex-1 items-center">
                    <div class="flex flex-col">
                        <span class="text-[10px] uppercase tracking-widest text-secondary font-bold mb-0.5">Order Date</span>
                        <span class="font-headline text-primary font-bold"><?= $e($order['created_at'] ?? '') ?></span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[10px] uppercase tracking-widest text-secondary font-bold mb-0.5">Name</span>
                        <span class="font-headline text-primary font-bold"><?= $e($order['user_name'] ?? '') ?></span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[10px] uppercase tracking-widest text-secondary font-bold mb-0.5">Room</span>
                        <span class="font-headline text-primary font-bold"><?= $e($order['room_no'] ?? '') ?></span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[10px] uppercase tracking-widest text-secondary font-bold mb-0.5">Ext.</span>
                        <span class="font-headline text-primary font-bold">#<?= $e($order['ext'] ?? '') ?></span>
                    </div>
                </div>
                <div class="flex items-center gap-3 ml-6 shrink-0">
                    <form method="post" action="<?= defined('BASE_URL') ? BASE_URL . '/admin/orders/deliver' : '/admin/orders/deliver' ?>">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars((string)csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
                        <input type="hidden" name="order_id" value="<?= $orderId ?>">
                        <button type="submit" class="bg-gradient-to-br from-tertiary-fixed to-tertiary-fixed-dim text-on-tertiary-fixed font-headline font-extrabold px-8 py-3 rounded uppercase tracking-wider text-sm shadow-sm active:scale-95 transition-transform">
                            deliver
                        </button>
                    </form>
                    <form method="post" action="<?= defined('BASE_URL') ? BASE_URL . '/admin/orders/done' : '/admin/orders/done' ?>">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars((string)csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
                        <input type="hidden" name="order_id" value="<?= $orderId ?>">
                        <button type="submit" class="bg-gradient-to-br from-primary to-primary-container text-on-primary font-headline font-extrabold px-8 py-3 rounded uppercase tracking-wider text-sm shadow-sm active:scale-95 transition-transform">
                            done
                        </button>
                    </form>
                </div>
            </div>
            <!-- Product Breakdown -->
            <div class="p-8">
                <?php if (!empty($order['items']) && is_array($order['items'])): ?>
                <div class="flex gap-6 overflow-x-auto no-scrollbar pb-2">
                    <?php foreach ($order['items'] as $item): ?>
                    <div class="min-w-[140px] flex flex-col gap-3 group">
                        <div class="relative w-full aspect-square bg-surface-container rounded-xl overflow-hidden">
                            <?php if (!empty($item['image'])): ?>
                            <img src="<?= $e($item['image']) ?>" alt="<?= $e($item['name'] ?? '') ?>"
                                class="w-full h-full object-cover transition-transform group-hover:scale-110">
                            <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center">
                                <span class="material-symbols-outlined text-outline text-2xl">coffee</span>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div>
                            <h4 class="font-headline text-primary font-bold text-sm"><?= $e($item['name'] ?? '') ?></h4>
                            <div class="flex justify-between items-center mt-1">
                                <span class="text-secondary font-bold text-xs uppercase tracking-tighter">
                                    <?= $e(number_format((float)($item['price_at_time_of_order'] ?? 0), 2)) ?> EGP
                                </span>
                                <span class="text-primary font-black text-sm">x<?= (int)($item['quantity'] ?? 0) ?></span>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
                <!-- Order Total -->
                <div class="mt-4 pt-6 flex justify-end items-baseline gap-2">
                    <span class="text-sm font-label text-secondary font-medium">Total:</span>
                    <span class="font-label text-sm text-secondary">EGP</span>
                    <span class="font-headline text-3xl font-extrabold text-primary"><?= $e(number_format((float)($order['total_amount'] ?? 0), 2)) ?></span>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>
</main>
<?php
$content = ob_get_clean();
$pageTitle = 'Manage orders';
require __DIR__ . '/../layouts/app.php';
