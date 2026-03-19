<?php

$products = isset($products) && is_array($products) ? $products : [];
$e = static fn($value) => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
ob_start();
?>
<div class="dashboard-header">
    <h1 class="admin-page-title">Products</h1>
    <a class="btn btn-primary" href="<?= defined('BASE_URL') ? BASE_URL . '/admin/products/create' : '/admin/products/create' ?>">Add Product</a>
</div>
<div class="card">
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>Available</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($products === []): ?>
            <tr><td colspan="6">No products found.</td></tr>
            <?php else: ?>
            <?php foreach ($products as $product): ?>
            <tr>
                <td><?= (int) ($product['id'] ?? 0) ?></td>
                <td><?= $e($product['name'] ?? '') ?></td>
                <td><?= $e($product['category_name'] ?? '') ?></td>
                <td><?= $e(number_format((float) ($product['price'] ?? 0), 2)) ?> EGP</td>
                <td><?= !empty($product['is_available']) ? 'Yes' : 'No' ?></td>
                <td class="actions">
                    <a class="btn btn-outline" href="<?= defined('BASE_URL') ? BASE_URL . '/admin/products/edit?id=' . (int) ($product['id'] ?? 0) : '/admin/products/edit?id=' . (int) ($product['id'] ?? 0) ?>">Edit</a>
                    <form method="post" action="<?= defined('BASE_URL') ? BASE_URL . '/admin/products/toggle' : '/admin/products/toggle' ?>">
                        <input type="hidden" name="id" value="<?= (int) ($product['id'] ?? 0) ?>">
                        <button type="submit" class="btn btn-outline">Toggle</button>
                    </form>
                    <form method="post" action="<?= defined('BASE_URL') ? BASE_URL . '/admin/products/delete' : '/admin/products/delete' ?>">
                        <input type="hidden" name="id" value="<?= (int) ($product['id'] ?? 0) ?>">
                        <button type="submit" class="btn btn-outline">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php
$content = ob_get_clean();
$showSidebar = true;
$pageCss = 'admin.css';
$pageTitle = 'Products';
require __DIR__ . '/../../layouts/app.php';
