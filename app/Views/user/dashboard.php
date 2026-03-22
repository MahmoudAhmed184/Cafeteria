<?php

$products = isset($products) && is_array($products) ? $products : [];
$rooms = isset($rooms) && is_array($rooms) ? $rooms : [];
$cart = isset($cart) && is_array($cart) ? $cart : [];
$grandTotal = $grandTotal ?? 0;
$latestOrder = $latestOrder ?? null;
$currentUser = $currentUser ?? ['name' => 'User'];
$e = function ($s) {
    return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');
};

$active_page = 'home';
ob_start();
?>
<div class="pt-24 pb-12 px-8 max-w-[1440px] mx-auto grid grid-cols-12 gap-8">
    <!-- Left Column: Your Order (Sticky Cart) -->
    <aside class="col-span-12 lg:col-span-4">
        <div class="sticky top-28 bg-surface-container-lowest p-6 rounded-lg shadow-sm border border-outline-variant/15">
            <?php require __DIR__ . '/../partials/cart_widget.php'; ?>
            <!-- Order Form — must be a real form so cart.js form.querySelector() works -->
            <form id="confirm-order-form"
                action="<?= defined('BASE_URL') ? rtrim(BASE_URL, '/') . '/orders/confirm' : '/orders/confirm' ?>"
                class="space-y-4 pt-2">
                <div>
                    <label class="block text-[11px] font-medium uppercase tracking-wider text-on-surface-variant mb-2" for="notes">Notes</label>
                    <textarea id="notes" name="notes" maxlength="500"
                        class="w-full bg-surface-container-low border-none rounded-lg p-3 text-sm focus:ring-1 focus:ring-primary h-20 resize-none"
                        placeholder="Any special instructions?"></textarea>
                </div>
                <div>
                    <label class="block text-[11px] font-medium uppercase tracking-wider text-on-surface-variant mb-2" for="room_no">Delivery Room</label>
                    <select id="room_no" name="room_no" required
                        class="w-full bg-surface-container-low border-none rounded-lg p-3 text-sm focus:ring-1 focus:ring-primary appearance-none">
                        <option value="">Select room</option>
                        <?php foreach ($rooms as $r): ?>
                        <option value="<?= $e($r['room_number']) ?>"><?= $e($r['room_number']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit"
                    class="w-full bg-gradient-to-br from-primary to-primary-container text-on-primary py-4 mt-3 rounded-lg font-bold tracking-tight text-lg shadow-lg active:scale-95 transition-all">
                    Confirm Order
                </button>
            </form>
        </div>
    </aside>

    <!-- Right Column: Products -->
    <section class="col-span-12 lg:col-span-8 space-y-6">
        <!-- Search & Filter -->
        <div class="flex items-center gap-3 mt-6">
            <div class="relative flex-grow">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
                <input type="search" id="product-search"
                    class="w-full pl-12 pr-4 py-4 bg-surface-container-highest border-none rounded-xl focus:ring-2 focus:ring-primary/20 transition-all font-medium text-primary"
                    placeholder="Search for your favorite brew or snack..."
                    aria-label="Search products">
            </div>
        </div>

        <!-- Latest Order Widget -->
        <?php require __DIR__ . '/../partials/latest_order.php'; ?>

        <!-- All Products Grid -->
        <div class="mt-5">
            <div class="flex items-center gap-4 mb-6 mt-6">
                <h2 class="text-xl font-extrabold text-primary tracking-tight">All Products</h2>
                <div class="h-px flex-grow bg-outline-variant/20"></div>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-5" id="products-grid">
                <?php foreach ($products as $p): ?>
                <?php if (!empty($p['is_available'])): ?>
                <div class="bg-surface-container-lowest rounded-lg overflow-hidden border border-outline-variant/10 hover:translate-y-[-4px] transition-transform duration-300 cursor-pointer group flex flex-col h-[260px] w-full relative product-card"
                    data-product-name="<?= $e($p['name']) ?>">
                    <div class="p-2 h-44 flex items-center justify-center">
                        <img src="<?= (strpos($p['image'] ?? '', 'http') === 0) ? $p['image'] : '/uploads/' . $p['image'] ?>" alt="<?= $e($p['name']) ?>" class="w-full h-full object-cover rounded-xl" width="200" height="200">
                    </div>
                    <div class="px-4 pb-4 flex-grow flex flex-col justify-between">
                        <div class="mt-1">
                            <h4 class="text-sm font-semibold text-[#1b1c1a] leading-tight text-left"><?= $e($p['name']) ?></h4>
                            <p class="text-[13px] text-coffee-brown mt-0.5 text-left">EGP <?= $e(number_format((float)($p['price'] ?? 0), 2)) ?></p>
                        </div>
                        <button type="button"
                            class="absolute bottom-4 right-4 w-8 h-8 rounded-lg bg-surface-container-high text-primary-container flex items-center justify-center group-hover:bg-primary group-hover:text-white transition-colors add-to-cart-btn"
                            data-product-id="<?= (int)$p['id'] ?>"
                            data-product-name="<?= $e($p['name']) ?>"
                            data-price="<?= $e($p['price']) ?>"
                            aria-label="Add <?= $e($p['name']) ?> to cart">
                            <span class="material-symbols-outlined text-sm">add</span>
                        </button>
                    </div>
                </div>
                <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
</div>



<script src="<?= defined('BASE_URL') ? rtrim(BASE_URL, '/') . '/' : '' ?>assets/js/search.js"></script>
<script src="<?= defined('BASE_URL') ? rtrim(BASE_URL, '/') . '/' : '' ?>assets/js/cart.js"></script>
<?php
$content = ob_get_clean();
$pageTitle = 'Dashboard';
require __DIR__ . '/../layouts/app.php';
