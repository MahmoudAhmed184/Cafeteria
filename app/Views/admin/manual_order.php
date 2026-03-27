<?php
$users = isset($users) && is_array($users) ? $users : [];
$products = isset($products) && is_array($products) ? $products : [];
$rooms = isset($rooms) && is_array($rooms) ? $rooms : [];
$cart = isset($cart) && is_array($cart) ? $cart : [];
$grandTotal = $grandTotal ?? 0;
$currentUser = $currentUser ?? ['name' => 'Admin'];
$e = function ($s) {
    return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');
};

$active_page = 'manual_order';
ob_start();
?>
<div class="max-w-[1400px] mx-auto px-6 lg:px-8 py-8 grid grid-cols-12 gap-8 items-start">
    <!-- Left Column: Cart -->
    <aside class="col-span-12 lg:col-span-4 order-last lg:order-first">
        <div class="sticky top-20 bg-surface-container-lowest rounded-lg border border-outline-variant/20 p-6">
            <?php require __DIR__ . '/../partials/cart_widget.php'; ?>
            <form id="confirm-order-form" method="post"
                action="<?= defined('BASE_URL') ? BASE_URL . '/admin/manual-order/store' : '/admin/manual-order/store' ?>">
                <input type="hidden" name="csrf_token"
                    value="<?= $e(function_exists('csrf_token') ? csrf_token() : '') ?>">
                <input type="hidden" name="user_id" id="manual-order-user-id" value="">
                <div class="space-y-4 pt-2">
                    <div class="space-y-1.5">
                        <label class="block text-xs font-medium text-on-surface-variant uppercase tracking-wide"
                            for="notes">Order Notes</label>
                        <textarea id="notes" name="notes" rows="2" maxlength="500"
                            class="w-full bg-surface-container-low border border-outline-variant/30 rounded-lg p-3 text-sm text-on-surface focus:ring-2 focus:ring-primary/20 focus:border-primary/40 h-20 resize-none placeholder:text-outline/40"
                            placeholder="Special requests..."></textarea>
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-xs font-medium text-on-surface-variant uppercase tracking-wide"
                            for="room_no">Delivery Location</label>
                        <select id="room_no" name="room_no" required
                            class="w-full bg-surface-container-low border border-outline-variant/30 rounded-lg py-2.5 px-3.5 text-sm text-on-surface focus:ring-2 focus:ring-primary/20 focus:border-primary/40 focus:outline-none custom-select">
                            <option value="">Select room</option>
                            <?php foreach ($rooms as $r): ?>
                                <option value="<?= $e($r['room_number']) ?>"><?= $e($r['room_number']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit"
                        class="w-full py-2.5 rounded-lg bg-primary text-on-primary font-body font-semibold text-sm hover:bg-primary-container active:scale-[0.99] transition-all duration-150 flex items-center justify-center gap-2">
                        <span>Confirm Order</span>
                        <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                    </button>
                </div>
            </form>
        </div>
    </aside>

    <!-- Right Column: Search and Products -->
    <section class="col-span-12 lg:col-span-8 space-y-6">
        <!-- Top Bar -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Search -->
            <div class="relative">
                <span
                    class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">search</span>
                <input type="search" id="manual-product-search"
                    class="w-full pl-10 pr-4 py-2.5 bg-surface-container-highest border border-outline-variant/20 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary/40 text-sm text-on-surface transition-all placeholder:text-outline/40"
                    placeholder="Search products..." aria-label="Search products">
            </div>
            <!-- User Search -->
            <div class="relative">
                <span
                    class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">person_search</span>
                <input type="text" id="manual-order-user" list="manual-order-user-list"
                    class="w-full pl-10 pr-4 py-2.5 bg-surface-container-highest border border-outline-variant/20 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary/40 text-sm text-on-surface transition-all placeholder:text-outline/40"
                    placeholder="Search user by name or email..." aria-label="Select user">
                <datalist id="manual-order-user-list">
                    <?php foreach ($users as $u): ?>
                        <option value="<?= $e($u['name']) ?> (<?= $e($u['email']) ?>)">
                        <?php endforeach; ?>
                </datalist>
            </div>
        </div>

        <!-- Product Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-2 xl:grid-cols-4 gap-4" id="products-grid">
            <?php foreach ($products as $p): ?>
                <?php if (!empty($p['is_available'])): ?>
                    <div class="group bg-surface-container-lowest rounded-lg border border-outline-variant/10 hover:border-outline-variant/40 transition-colors duration-150 cursor-pointer product-card"
                        data-product-name="<?= $e($p['name']) ?>">
                        <div class="relative aspect-[4/3] rounded-t-lg overflow-hidden">
                            <img src="<?= (strpos($p['image'] ?? '', 'http') === 0) ? $p['image'] : '/uploads/' . $p['image'] ?>"
                                alt="<?= $e($p['name']) ?>" class="w-full h-full object-cover" loading="lazy">
                        </div>
                        <div class="p-3 flex flex-col justify-between h-20">
                            <h3 class="text-sm font-medium text-on-surface leading-tight line-clamp-2"><?= $e($p['name']) ?>
                            </h3>
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-on-surface-variant"><?= $e($p['category_name'] ?? 'Product') ?></span>
                                <span class="text-xs font-semibold text-secondary">EGP
                                    <?= $e(number_format((float) ($p['price'] ?? 0), 2)) ?></span>
                            </div>
                        </div>
                        <button type="button" class="hidden add-to-cart-btn" data-product-id="<?= (int) $p['id'] ?>"
                            data-product-name="<?= $e($p['name']) ?>" data-price="<?= $e($p['price']) ?>">Add</button>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </section>
</div>

<!-- Success toast overlay -->
<div id="add-success-overlay"
    class="fixed bottom-8 left-1/2 -translate-x-1/2 glass-panel border border-outline-variant/20 rounded-full px-5 py-2.5 flex items-center gap-2 z-[100] hidden">
    <span class="w-5 h-5 rounded-full bg-green-500 flex items-center justify-center text-white">
        <span class="material-symbols-outlined text-[14px]">check</span>
    </span>
    <span class="text-sm font-medium text-on-surface" id="add-success-msg">Item added to cart</span>
</div>

<?php $v = time(); ?>
<script src="/assets/js/search.js?v=<?= $v ?>"></script>
<script src="/assets/js/cart.js?v=<?= $v ?>"></script>
<script>
    (function () {
        'use strict';
        var userInput = document.getElementById('manual-order-user');
        var userIdInput = document.getElementById('manual-order-user-id');
        if (!userInput || !userIdInput) return;

        var usersMap = <?= json_encode(
            array_map(function ($u) {
                        return ['label' => $u['name'] . ' (' . $u['email'] . ')', 'id' => $u['id']];
                    }, $users),
            JSON_HEX_TAG | JSON_HEX_AMP
        ) ?>;

        var lookup = {};
        usersMap.forEach(function (u) { lookup[u.label] = u.id; });

        function syncUserId() {
            var val = userInput.value.trim();
            userIdInput.value = lookup[val] !== undefined ? lookup[val] : '';
        }

        userInput.addEventListener('input', syncUserId);
        userInput.addEventListener('change', syncUserId);

        document.querySelectorAll('.product-card').forEach(function (card) {
            card.addEventListener('click', function () {
                var btn = card.querySelector('.add-to-cart-btn');
                if (btn) btn.click();
            });
        });
    })();
</script>
<?php
$content = ob_get_clean();
$pageTitle = 'Manual order';
require __DIR__ . '/../layouts/app.php';
