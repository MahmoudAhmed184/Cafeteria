<?php
/**
 * Add/Edit product form - FR-ADM-PRD-003, 004, 005, 007. Mock data for view-first development.
 */
$data = [
    'categories' => [
        ['id' => 1, 'name' => 'Beverages'],
        ['id' => 2, 'name' => 'Sandwiches'],
    ],
    'product' => null,
];
if (isset($categories) && is_array($categories)) {
    $data['categories'] = $categories;
}
if (isset($product)) {
    $data['product'] = $product;
}
$categories = $data['categories'];
$product = $data['product'];
$isEdit = !empty($product);
$e = function ($s) {
    return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');
};
$formAction = $isEdit ? (defined('BASE_URL') ? BASE_URL . '/admin/products/update' : '/admin/products/update') : (defined('BASE_URL') ? BASE_URL . '/admin/products/store' : '/admin/products/store');
ob_start();
?>
<div class="product-form-page">
    <h1 class="admin-page-title"><?= $isEdit ? 'Edit product' : 'Add product' ?></h1>
    <form id="product-form" class="card product-form" method="post" action="<?= $e($formAction) ?>" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= $e($csrfToken ?? '') ?>">
        <?php if ($isEdit): ?>
        <input type="hidden" name="id" value="<?= (int)($product['id'] ?? 0) ?>">
        <?php endif; ?>
        <div class="form-group">
            <label class="form-label" for="name">Product name <span class="required">*</span></label>
            <input type="text" id="name" name="name" class="form-control" value="<?= $e($product['name'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label class="form-label" for="price">Price (EGP) <span class="required">*</span></label>
            <input type="number" id="price" name="price" class="form-control" step="0.01" min="0.01" value="<?= $e($product['price'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label class="form-label" for="category_id">Category <span class="required">*</span></label>
            <div class="form-select-with-action">
                <select id="category_id" name="category_id" class="form-control" required>
                    <option value="">Select category</option>
                    <?php foreach ($categories as $c): ?>
                    <option value="<?= (int)$c['id'] ?>" <?= (isset($product['category_id']) && (int)$product['category_id'] === (int)$c['id']) ? 'selected' : '' ?>><?= $e($c['name']) ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="button" class="btn btn-outline" id="add-category-btn" aria-controls="add-category-modal">Add category</button>
            </div>
        </div>
        <div class="form-group">
            <label class="form-label" for="image">Product image <?= $isEdit ? '(leave empty to keep current)' : '<span class="required">*</span>' ?></label>
            <input type="file" id="image" name="image" class="form-control" accept="image/jpeg,image/png,image/gif,image/webp" <?= $isEdit ? '' : 'required' ?>>
            <div id="image-preview" class="image-preview" aria-live="polite"></div>
            <?php if ($isEdit && !empty($product['image'])): ?>
            <p class="form-hint">Current: <?= $e($product['image']) ?></p>
            <?php endif; ?>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Save</button>
            <button type="reset" class="btn btn-outline">Reset</button>
        </div>
    </form>
</div>

<div id="add-category-modal" class="modal-backdrop" role="dialog" aria-modal="true" aria-labelledby="add-category-title" hidden>
    <div class="modal">
        <h2 id="add-category-title" class="card-title">Add category</h2>
        <form id="add-category-form">
            <div class="form-group">
                <label class="form-label" for="new_category_name">Category name</label>
                <input type="text" id="new_category_name" class="form-control" required>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Add</button>
                <button type="button" class="btn btn-outline" id="add-category-cancel">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script src="<?= defined('BASE_URL') ? rtrim(BASE_URL, '/') . '/' : '' ?>assets/js/admin/products.js"></script>
<?php
$content = ob_get_clean();
$showSidebar = true;
$pageCss = 'admin.css';
$pageTitle = $isEdit ? 'Edit product' : 'Add product';
require __DIR__ . '/../../layouts/app.php';
