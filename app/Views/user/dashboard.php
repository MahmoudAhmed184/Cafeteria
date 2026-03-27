<?php /* Frontend Polish Pass: unified cart widget, lazy loading on product grid images */

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
<div class="py-8 px-6 lg:px-8 max-w-[1400px] mx-auto grid grid-cols-12 gap-8">
    <!-- Left Column: Cart -->
    <aside class="col-span-12 lg:col-span-4 order-last lg:order-first">
        <div class="sticky top-20 bg-surface-container-lowest p-6 rounded-lg border border-outline-variant/15">
            <?php require __DIR__ . '/../partials/cart_widget.php'; ?>
            <form id="confirm-order-form"
                action="<?= defined('BASE_URL') ? rtrim(BASE_URL, '/') . '/orders/confirm' : '/orders/confirm' ?>"
                class="space-y-4 pt-2">
                <div class="space-y-1.5">
                    <label class="block text-xs font-medium text-on-surface-variant uppercase tracking-wide" for="notes">Notes</label>
                    <textarea id="notes" name="notes" maxlength="500"
                        class="w-full bg-surface-container-low border border-outline-variant/30 rounded-lg p-3 text-sm text-on-surface focus:ring-2 focus:ring-primary/20 focus:border-primary/40 h-20 resize-none placeholder:text-outline/40"
                        placeholder="Any special instructions?"></textarea>
                </div>
                <div class="space-y-1.5">
                    <label class="block text-xs font-medium text-on-surface-variant uppercase tracking-wide" for="room_no">Delivery Room</label>
                    <select id="room_no" name="room_no" required
                        class="w-full bg-surface-container-low border border-outline-variant/30 rounded-lg py-2.5 px-3.5 text-sm text-on-surface focus:ring-2 focus:ring-primary/20 focus:border-primary/40 focus:outline-none custom-select">
                        <option value="">Select room</option>
                        <?php foreach ($rooms as $r): ?>
                        <option value="<?= $e($r['room_number']) ?>"><?= $e($r['room_number']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit"
                    class="w-full bg-primary text-on-primary py-2.5 rounded-lg font-body font-semibold text-sm hover:bg-primary-container active:scale-[0.99] transition-all duration-150">
                    Confirm Order
                </button>
            </form>
        </div>
    </aside>

    <!-- Right Column: Products -->
    <section class="col-span-12 lg:col-span-8 space-y-6">
        <!-- Search -->
        <div class="relative">
            <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">search</span>
            <input type="search" id="product-search"
                class="w-full pl-10 pr-4 py-2.5 bg-surface-container-highest border border-outline-variant/20 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary/40 text-sm text-on-surface transition-all placeholder:text-outline/40"
                placeholder="Search products..."
                aria-label="Search products">
        </div>

        <!-- Latest Order Widget -->
        <?php require __DIR__ . '/../partials/latest_order.php'; ?>

        <!-- All Products Grid -->
        <div>
            <div class="flex items-center gap-4 mb-4">
                <h2 class="text-lg font-semibold text-primary">All Products</h2>
                <div class="h-px flex-grow bg-outline-variant/20"></div>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4" id="products-grid">
                <?php foreach ($products as $p): ?>
                <?php if (!empty($p['is_available'])): ?>
                <div class="bg-surface-container-lowest rounded-lg overflow-hidden border border-outline-variant/10 hover:border-outline-variant/40 transition-colors duration-150 cursor-pointer group flex flex-col relative product-card"
                    data-product-name="<?= $e($p['name']) ?>">
                    <div class="aspect-[3/2] overflow-hidden rounded-t-lg">
                        <img src="<?= (strpos($p['image'] ?? '', 'http') === 0) ? $p['image'] : '/uploads/' . $p['image'] ?>" alt="<?= $e($p['name']) ?>" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" loading="lazy">
                    </div>
                    <div class="px-3 pt-3 pb-3 flex-grow flex flex-col justify-between">
                        <div>
                            <h3 class="text-sm font-medium text-on-surface leading-snug line-clamp-2 mb-1"><?= $e($p['name']) ?></h3>
                            <p class="text-xs text-secondary font-semibold">EGP <?= $e(number_format((float)($p['price'] ?? 0), 2)) ?></p>
                        </div>
                        <button type="button"
                            class="absolute bottom-3 right-3 w-8 h-8 rounded-lg bg-surface-container-high text-on-surface-variant flex items-center justify-center group-hover:bg-primary group-hover:text-on-primary transition-all duration-150 add-to-cart-btn shadow-sm group-hover:shadow-md"
                            data-product-id="<?= (int)$p['id'] ?>"
                            data-product-name="<?= $e($p['name']) ?>"
                            data-price="<?= $e($p['price']) ?>"
                            aria-label="Add <?= $e($p['name']) ?> to cart"
                            title="Add to cart">
                            <span class="material-symbols-outlined text-[20px]">add</span>
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
