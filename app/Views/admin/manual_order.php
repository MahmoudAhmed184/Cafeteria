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
<main class="max-w-screen-2xl mx-auto px-8 py-10 grid grid-cols-12 gap-8 items-start">
    <!-- Left Column: Order Cart Panel (Sticky) -->
    <aside class="col-span-12 lg:col-span-4 sticky top-28">
        <div class="bg-surface-container-lowest rounded-xl shadow-[0px_12px_32px_rgba(41,24,6,0.08)] p-6 overflow-hidden">
            <?php require __DIR__ . '/../partials/cart_widget.php'; ?>
            <!-- Form Controls (all fields live inside #confirm-order-form below) -->
            <div class="mt-2">

                    <form id="confirm-order-form" method="post"
                        action="<?= defined('BASE_URL') ? BASE_URL . '/admin/manual-order/store' : '/admin/manual-order/store' ?>">
                        <input type="hidden" name="csrf_token" value="<?= $e(function_exists('csrf_token') ? csrf_token() : '') ?>">
                        <input type="hidden" name="user_id" id="manual-order-user-id" value="">
                        <!-- notes and room_no live inside form so cart.js form.querySelector() works -->
                        <div class="mb-4">
                            <label class="block text-xs font-bold text-primary/60 uppercase mb-2 tracking-widest" for="notes">Order Notes</label>
                            <textarea id="notes" name="notes" rows="2" maxlength="500"
                                class="w-full bg-surface-container-low border-none rounded-lg focus:ring-2 focus:ring-tertiary-fixed text-sm p-3 placeholder:text-outline/50 resize-none"
                                placeholder="Special requests..."></textarea>
                        </div>
                        <div class="mb-4">
                            <label class="block text-xs font-bold text-primary/60 uppercase mb-2 tracking-widest" for="room_no">Delivery Location</label>
                            <div class="relative">
                                <select id="room_no" name="room_no" required
                                    class="w-full bg-surface-container-low border-none rounded-lg focus:ring-2 focus:ring-tertiary-fixed text-sm p-3 appearance-none cursor-pointer !bg-none pr-10">
                                    <option value="">Select room</option>
                                    <?php foreach ($rooms as $r): ?>
                                    <option value="<?= $e($r['room_number']) ?>"><?= $e($r['room_number']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-primary/60">unfold_more</span>
                            </div>
                        </div>
                        <button type="submit"
                            class="w-full py-4 rounded-lg bg-gradient-to-br from-primary to-primary-container text-white font-bold tracking-tight shadow-lg shadow-primary/20 hover:scale-[0.98] transition-all active:scale-95 flex items-center justify-center gap-2">
                            <span>Confirm Order</span>
                            <span class="material-symbols-outlined text-sm">arrow_forward</span>
                        </button>
                    </form>
            </div>
        </div>
    </aside>


    <!-- Right Column: Search and Product Grid -->
    <section class="col-span-12 lg:col-span-8 space-y-8">
        <!-- Top Action Bar -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Search Bar -->
            <div class="relative group">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-primary/40 group-focus-within:text-primary transition-colors">search</span>
                <input type="search" id="manual-product-search"
                    class="w-full h-14 pl-12 pr-4 bg-surface-container-low rounded-xl border-none focus:ring-2 focus:ring-tertiary-fixed transition-all placeholder:text-outline/60 font-medium"
                    placeholder="Search products or ingredients..."
                    aria-label="Search products">
            </div>
            <!-- Add to User Section -->
            <div class="relative group">
                <div class="absolute -top-2 left-4 bg-background px-2 text-[10px] font-black uppercase tracking-widest text-primary/40 z-10">Add to user</div>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-primary/40">person_search</span>
                    <input type="text" id="manual-order-user" list="manual-order-user-list"
                        class="w-full h-14 pl-12 pr-4 bg-surface-container-low rounded-xl border-none focus:ring-2 focus:ring-tertiary-fixed font-bold text-primary"
                        placeholder="Search by name or email..."
                        aria-label="Select user">
                    <datalist id="manual-order-user-list">
                        <?php foreach ($users as $u): ?>
                        <option value="<?= $e($u['name']) ?> (<?= $e($u['email']) ?>)">
                        <?php endforeach; ?>
                    </datalist>
                </div>
            </div>
        </div>

        <!-- Product Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-2 xl:grid-cols-4 gap-6" id="products-grid">
            <?php foreach ($products as $p): ?>
            <?php if (!empty($p['is_available'])): ?>
            <div class="group bg-surface-container-lowest p-2 rounded-lg hover:shadow-xl hover:shadow-primary/5 transition-all cursor-pointer product-card"
                data-product-name="<?= $e($p['name']) ?>">
                <div class="relative aspect-square rounded-xl overflow-hidden mb-4">
                    <img src="<?= $e($p['image'] ?? '') ?>" alt="<?= $e($p['name']) ?>"
                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end justify-center pb-4">
                        <span class="bg-white/90 text-primary text-xs font-black uppercase px-4 py-2 rounded-full shadow-lg">Quick Add +</span>
                    </div>
                </div>
                <div class="px-2 pb-2 flex flex-col justify-between h-20">
                    <h3 class="font-bold text-primary leading-tight line-clamp-2"><?= $e($p['name']) ?></h3>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-secondary font-medium"><?= $e($p['category_name'] ?? 'Product') ?></span>
                        <div class="flex items-baseline">
                            <span class="text-[10px] font-bold text-secondary mr-0.5">EGP</span>
                            <span class="text-lg font-black text-primary"><?= $e(number_format((float)($p['price'] ?? 0), 0)) ?></span>
                        </div>
                    </div>
                </div>
                <!-- Hidden add to cart button for JS compatibility -->
                <button type="button"
                    class="hidden add-to-cart-btn"
                    data-product-id="<?= (int)$p['id'] ?>"
                    data-product-name="<?= $e($p['name']) ?>"
                    data-price="<?= $e($p['price']) ?>">Add</button>
            </div>
            <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </section>
</main>

<!-- Success Feedback Overlay -->
<div id="add-success-overlay" class="fixed bottom-8 left-1/2 -translate-x-1/2 glass-panel border border-primary/10 rounded-full px-6 py-3 flex items-center gap-3 shadow-2xl z-[100] hidden">
    <span class="w-6 h-6 rounded-full bg-green-500 flex items-center justify-center text-white">
        <span class="material-symbols-outlined text-sm">check</span>
    </span>
    <span class="text-primary font-bold" id="add-success-msg">Item added to cart</span>
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

    // Allow clicking on product cards to trigger add to cart
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
