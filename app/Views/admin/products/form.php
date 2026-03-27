<?php

$categories = isset($categories) && is_array($categories) ? $categories : [];
$product = $product ?? null;
$product = isset($product) && is_array($product) ? $product : [];
$errors = isset($errors) && is_array($errors) ? $errors : [];
$isEdit = !empty($product['id']);
$e = static fn($value) => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
$baseUrl = defined('BASE_URL') ? BASE_URL : '';
$formAction = $isEdit
    ? (defined('BASE_URL') ? BASE_URL . '/admin/products/update' : '/admin/products/update')
    : (defined('BASE_URL') ? BASE_URL . '/admin/products/store' : '/admin/products/store');

$active_page = 'products';
ob_start();
?>
<div class="max-w-4xl mx-auto px-6 lg:px-8 py-8">
    <header class="mb-8 flex justify-between items-start">
        <div>
            <div class="flex items-center gap-2 text-xs text-on-surface-variant mb-2">
                <a href="<?= defined('BASE_URL') ? BASE_URL . '/admin/products' : '/admin/products' ?>"
                    class="hover:text-primary transition-colors">Products</a>
                <span>/</span>
                <span><?= $isEdit ? 'Edit' : 'Add' ?></span>
            </div>
            <h1 class="text-2xl font-headline font-semibold text-primary">
                <?= $isEdit ? 'Edit Product' : 'Add Product' ?>
            </h1>
        </div>
        <a href="<?= defined('BASE_URL') ? BASE_URL . '/admin/products' : '/admin/products' ?>"
            class="text-sm font-medium text-on-surface-variant hover:text-primary transition-colors px-3 py-1.5 border border-outline-variant/30 rounded-lg hidden sm:block">Back
            to products</a>
    </header>

    <form id="product-form" method="post" action="<?= $e($formAction) ?>" enctype="multipart/form-data"
        class="bg-surface-container-lowest rounded-lg border border-outline-variant/20 p-6 space-y-6">
        <input type="hidden" name="csrf_token" value="<?= $e(function_exists('csrf_token') ? csrf_token() : '') ?>">
        <?php if ($isEdit): ?>
            <input type="hidden" name="id" value="<?= (int) ($product['id'] ?? 0) ?>">
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div class="p-3 bg-error-container/30 rounded-lg space-y-1">
                <?php foreach ($errors as $err): ?>
                    <p class="text-sm text-error font-medium flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">error</span>
                        <?= $e(is_array($err) ? implode(', ', $err) : $err) ?>
                    </p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Name -->
        <div class="space-y-1.5">
            <label class="block text-sm font-medium text-on-surface" for="name">Product Name</label>
            <input type="text" id="name" name="name" required value="<?= $e($product['name'] ?? '') ?>"
                class="w-full bg-surface-container-low border border-outline-variant/30 rounded-lg py-2.5 px-3.5 text-sm text-on-surface focus:ring-2 focus:ring-primary/20 focus:border-primary focus:outline-none transition"
                placeholder="e.g. Arabica Roast">
        </div>

        <!-- Price -->
        <div class="space-y-1.5">
            <label class="block text-sm font-medium text-on-surface" for="price">Price (EGP)</label>
            <input type="number" id="price" name="price" step="0.01" min="0.01" required
                value="<?= $e($product['price'] ?? '') ?>"
                class="w-full bg-surface-container-low border border-outline-variant/30 rounded-lg py-2.5 px-3.5 text-sm text-on-surface focus:ring-2 focus:ring-primary/20 focus:border-primary focus:outline-none transition"
                placeholder="0.00">
        </div>

        <!-- Category -->
        <div class="space-y-1.5">
            <div class="flex justify-between items-center">
                <label class="block text-sm font-medium text-on-surface" for="category_id">Category</label>
                <button type="button" id="add-category-btn" aria-controls="add-category-modal"
                    class="text-xs font-medium text-secondary hover:text-primary transition-colors flex items-center gap-1">
                    <span class="material-symbols-outlined text-[14px]">add</span> Add category
                </button>
            </div>
            <select id="category_id" name="category_id" required
                class="w-full bg-surface-container-low border border-outline-variant/30 rounded-lg py-2.5 px-3.5 text-sm text-on-surface focus:ring-2 focus:ring-primary/20 focus:border-primary focus:outline-none transition custom-select">
                <option value="">Select category</option>
                <?php foreach ($categories as $c): ?>
                    <option value="<?= (int) $c['id'] ?>" <?= (isset($product['category_id']) && (int) $product['category_id'] === (int) $c['id']) ? 'selected' : '' ?>>
                        <?= $e($c['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Image Upload -->
        <div class="space-y-1.5">
            <span class="block text-sm font-medium text-on-surface">
                Product Image
                <?= $isEdit ? '<span class="text-xs text-on-surface-variant font-normal">(leave empty to keep current)</span>' : '' ?>
            </span>
            <div
                class="flex items-center gap-4 p-4 bg-surface-container-low rounded-lg border border-outline-variant/30 border-dashed file-upload-zone">
                <div id="image-preview"
                    class="w-14 h-14 rounded-lg overflow-hidden bg-surface-container flex items-center justify-center shrink-0">
                    <?php if ($isEdit && !empty($product['image'])): ?>
                        <img src="<?= (strpos($product['image'] ?? '', 'http') === 0) ? $product['image'] : '/uploads/' . $product['image'] ?>"
                            class="w-full h-full object-cover" alt="Current product image" loading="lazy">
                    <?php else: ?>
                        <span class="material-symbols-outlined text-on-surface-variant text-xl">photo_camera</span>
                    <?php endif; ?>
                </div>
                <div class="flex flex-col flex-1">
                    <p class="text-xs text-on-surface-variant mb-2">JPG, PNG, GIF, or WebP. Max 2MB.</p>
                    <label
                        class="cursor-pointer inline-flex items-center justify-center px-4 py-2 bg-surface-container-highest text-sm font-medium text-on-surface rounded-md hover:bg-surface-container-high transition-all w-fit focus-within:ring-2 focus-within:ring-primary/20">
                        Browse
                        <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/gif,image/webp"
                            <?= $isEdit ? '' : 'required' ?> class="sr-only">
                    </label>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center gap-3 pt-4 border-t border-outline-variant/15">
            <button type="submit"
                class="px-6 py-2.5 bg-primary text-on-primary font-body font-semibold text-sm rounded-lg hover:bg-primary-container active:scale-[0.99] transition-all flex items-center gap-2 focus:outline-none focus:ring-2 focus:ring-primary/30">
                <span class="material-symbols-outlined text-[18px]">save</span>
                <?= $isEdit ? 'Save Changes' : 'Save Product' ?>
            </button>
            <button type="reset"
                class="px-6 py-2.5 bg-transparent border border-outline-variant/40 text-on-surface font-body font-medium text-sm rounded-lg hover:bg-surface-container-high transition-all flex items-center gap-2 focus:outline-none focus:ring-2 focus:ring-outline/30">
                <span class="material-symbols-outlined text-[18px]">restart_alt</span>
                Reset
            </button>
        </div>
    </form>
</div>

<!-- Add Category Modal -->
<div id="add-category-modal" class="modal-backdrop fixed inset-0 bg-black/30 flex items-center justify-center z-[100]" role="dialog" aria-modal="true" aria-labelledby="add-category-title" hidden>
    <div class="bg-white rounded-lg p-6 max-w-sm w-full mx-4 border border-outline-variant/20">
        <h2 id="add-category-title" class="text-lg font-semibold text-primary mb-4">Add Category</h2>
        <form id="add-category-form">
            <div class="space-y-1.5 mb-4">
                <label class="block text-sm font-medium text-on-surface" for="new_category_name">Category Name</label>
                <input type="text" id="new_category_name"
                    class="w-full bg-surface-container-low border border-outline-variant/30 rounded-lg py-2.5 px-3.5 text-sm text-on-surface focus:ring-2 focus:ring-primary/20 focus:border-primary focus:outline-none transition"
                    placeholder="e.g. Hot Drinks" required>
            </div>
            <div class="flex items-center gap-3">
                <button type="submit"
                    class="flex-1 px-4 py-2.5 bg-primary text-on-primary font-body font-semibold text-sm rounded-lg hover:bg-primary-container active:scale-[0.99] transition-all">
                    Add
                </button>
                <button type="button" id="add-category-cancel"
                    class="flex-1 px-4 py-2.5 border border-outline-variant/40 text-on-surface font-body font-medium text-sm rounded-lg hover:bg-surface-container-high transition-all">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<script src="/assets/js/admin/products.js?v=<?= time() ?>"></script>
<?php
$content = ob_get_clean();
$pageTitle = $isEdit ? 'Edit product' : 'Add product';
require __DIR__ . '/../../layouts/app.php';
