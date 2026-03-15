<?php
/**
 * User dashboard view - FR-CART-001, FR-CART-002, FR-CART-007, FR-CART-008, FR-CART-009
 * Mock $data for view-first development; replace with controller data at integration.
 */
$data = [
    'products' => [
        ['id' => 1, 'name' => 'Coffee', 'price' => 15.00, 'image' => 'assets/images/placeholder.png', 'is_available' => 1],
        ['id' => 2, 'name' => 'Sandwich', 'price' => 25.00, 'image' => 'assets/images/placeholder.png', 'is_available' => 1],
        ['id' => 3, 'name' => 'Juice', 'price' => 10.00, 'image' => 'assets/images/placeholder.png', 'is_available' => 1],
    ],
    'rooms' => [
        ['id' => 1, 'room_number' => '101'],
        ['id' => 2, 'room_number' => '102'],
    ],
    'cart' => [],
    'grandTotal' => 0,
];
if (isset($products) && is_array($products)) {
    $data['products'] = $products;
}
if (isset($rooms) && is_array($rooms)) {
    $data['rooms'] = $rooms;
}
if (isset($cart)) {
    $data['cart'] = $cart;
}
if (isset($grandTotal)) {
    $data['grandTotal'] = $grandTotal;
}
$products = $data['products'];
$rooms = $data['rooms'];
$cart = $data['cart'];
$grandTotal = $data['grandTotal'];
$currentUser = $currentUser ?? ['name' => 'User'];
$e = function ($s) {
    return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');
};

ob_start();
?>
<div class="dashboard-header">
    <h1 class="admin-page-title">Dashboard</h1>
</div>
<div class="dashboard-grid">
    <div class="dashboard-main">
        <div class="search-bar">
            <label class="form-label" for="product-search">Search products</label>
            <input type="search" id="product-search" class="form-control" placeholder="Filter by product name..." aria-label="Search products">
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
        $cart = $data['cart'];
        $grandTotal = $data['grandTotal'];
        require __DIR__ . '/../partials/cart_widget.php';
        ?>
    </aside>
</div>
<?php
$content = ob_get_clean();
$showSidebar = false;
$pageCss = 'dashboard.css';
$pageTitle = 'Dashboard';
require __DIR__ . '/../layouts/app.php';
