<?php
/**
 * Manual order view - FR-ADM-MAN-001, 002, 003. Place order on behalf of a user.
 * Expects data from controller; uses safe defaults when not provided.
 */
$users = isset($users) && is_array($users) ? $users : [];
$products = isset($products) && is_array($products) ? $products : [];
$rooms = isset($rooms) && is_array($rooms) ? $rooms : [];
$cart = isset($cart) && is_array($cart) ? $cart : [];
$grandTotal = $grandTotal ?? 0;
$currentUser = $currentUser ?? ['name' => 'Admin'];
$e = function ($s) {
    return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');
};

ob_start();
?>
<div class="manual-order-header dashboard-header">
    <h1 class="admin-page-title">Manual order</h1>
</div>
<div class="manual-order-user-section form-group">
    <label class="form-label" for="manual-order-user">Add to User</label>
    <input type="text" id="manual-order-user" class="form-control" list="manual-order-user-list" placeholder="Search by name or email..." aria-label="Select user">
    <datalist id="manual-order-user-list">
        <?php foreach ($users as $u): ?>
        <option value="<?= $e($u['name']) ?> (<?= $e($u['email']) ?>)">
        <?php endforeach; ?>
    </datalist>
    <input type="hidden" name="user_id" id="manual-order-user-id" value="">
</div>
<div class="dashboard-grid">
    <div class="dashboard-main">
        <div class="search-bar">
            <label class="form-label" for="manual-product-search">Search products</label>
            <input type="search" id="manual-product-search" class="form-control" placeholder="Filter by product name..." aria-label="Search products">
        </div>
        <div class="products-grid" id="products-grid">
            <?php foreach ($products as $p): ?>
            <?php if (!empty($p['is_available'])): ?>
            <article class="product-card" data-product-name="<?= $e($p['name']) ?>">
                <img src="<?= $e($p['image'] ?? '') ?>" alt="" class="product-card-image" width="200" height="200">
                <div class="product-card-body">
                    <h3 class="product-card-name"><?= $e($p['name']) ?></h3>
                    <p class="product-card-price"><?= $e(number_format((float)($p['price'] ?? 0), 2)) ?> EGP</p>
                    <button type="button" class="btn btn-primary add-to-cart-btn" data-product-id="<?= (int)$p['id'] ?>" data-product-name="<?= $e($p['name']) ?>" data-price="<?= $e($p['price']) ?>">Add to Cart</button>
                </div>
            </article>
            <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <section class="order-form-section" aria-label="Order details">
            <h2 class="card-title">Delivery details</h2>
            <form id="confirm-order-form" class="order-form">
                <div class="form-group">
                    <label class="form-label" for="room_no">Room</label>
                    <select id="room_no" name="room_no" class="form-control" required>
                        <option value="">Select room</option>
                        <?php foreach ($rooms as $r): ?>
                        <option value="<?= $e($r['room_number']) ?>"><?= $e($r['room_number']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label" for="notes">Notes (max 500 characters)</label>
                    <textarea id="notes" name="notes" class="form-control" maxlength="500" rows="3" placeholder="Special instructions..."></textarea>
                </div>
                <button type="submit" class="btn btn-primary confirm-order-btn">Confirm order</button>
            </form>
        </section>
    </div>
    <aside class="dashboard-sidebar">
        <?php
        require __DIR__ . '/../partials/cart_widget.php';
        ?>
    </aside>
</div>
<script src="<?= defined('BASE_URL') ? rtrim(BASE_URL, '/') . '/' : '' ?>assets/js/search.js"></script>
<?php
$content = ob_get_clean();
$showSidebar = true;
$pageCss = 'dashboard.css';
$pageTitle = 'Manual order';
require __DIR__ . '/../layouts/app.php';
