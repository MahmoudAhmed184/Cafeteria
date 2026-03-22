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
<main class="flex-1 max-w-5xl w-full mx-auto px-8 py-12">
    <!-- Breadcrumb / Header -->
    <header class="mb-12">
        <div class="flex items-center gap-2 text-on-surface-variant mb-2">
            <span class="text-sm font-medium">Products</span>
        </div>
        <h1 class="text-5xl font-extrabold font-headline tracking-tight text-primary">
            <?= $isEdit ? 'Edit Product' : 'Add Product' ?>
        </h1>
    </header>

    <!-- Form Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Main Form Canvas -->
        <section class="lg:col-span-8 space-y-8">
            <form id="product-form" method="post" action="<?= $e($formAction) ?>"
                enctype="multipart/form-data"
                class="bg-surface-container-lowest p-8 rounded-xl shadow-[0px_12px_32px_rgba(41,24,6,0.04)]">
                <input type="hidden" name="csrf_token" value="<?= $e(function_exists('csrf_token') ? csrf_token() : '') ?>">
                <?php if ($isEdit): ?>
                <input type="hidden" name="id" value="<?= (int)($product['id'] ?? 0) ?>">
                <?php endif; ?>

                <div class="space-y-6">
                    <!-- Product Name -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-bold text-primary font-headline flex items-center gap-2" for="name">
                            <span class="material-symbols-outlined text-sm">inventory_2</span>
                            Product
                        </label>
                        <input type="text" id="name" name="name" required
                            value="<?= $e($product['name'] ?? '') ?>"
                            class="bg-surface-container-lowest border-none ring-1 ring-outline-variant/30 focus:ring-2 focus:ring-secondary/50 rounded-lg p-3 text-on-surface placeholder:text-outline transition-all font-body"
                            placeholder="Enter product name (e.g. Arabica Roast)">
                    </div>

                    <!-- Price -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-bold text-primary font-headline flex items-center gap-2" for="price">
                            <span class="material-symbols-outlined text-sm">payments</span>
                            Price
                        </label>
                        <div class="relative flex items-center bg-surface-container-lowest ring-1 ring-outline-variant/30 rounded-lg focus-within:ring-2 focus-within:ring-secondary/50 transition-all">
                            <span class="pl-4 pr-2 text-secondary font-label font-semibold">EGP</span>
                            <input type="number" id="price" name="price" step="0.01" min="0.01" required
                                value="<?= $e($product['price'] ?? '') ?>"
                                class="flex-1 bg-transparent border-none p-3 text-on-surface font-body focus:ring-0"
                                placeholder="0.00">
                        </div>
                    </div>

                    <!-- Category -->
                    <div class="flex flex-col gap-2">
                        <div class="flex justify-between items-end">
                            <label class="text-sm font-bold text-primary font-headline flex items-center gap-2" for="category_id">
                                <span class="material-symbols-outlined text-sm">category</span>
                                Category
                            </label>
                            <button type="button" id="add-category-btn" aria-controls="add-category-modal"
                                class="text-on-tertiary-container font-headline font-bold text-xs flex items-center gap-1 hover:underline mb-1">
                                <span class="material-symbols-outlined text-xs">add</span> Add Category
                            </button>
                        </div>
                        <div class="relative">
                            <select id="category_id" name="category_id" required
                                class="w-full bg-surface-container-lowest border-none ring-1 ring-outline-variant/30 focus:ring-2 focus:ring-secondary/50 rounded-lg p-3 text-on-surface transition-all font-body appearance-none !bg-none pr-10">
                                <option value="">Select category</option>
                                <?php foreach ($categories as $c): ?>
                                <option value="<?= (int)$c['id'] ?>"
                                    <?= (isset($product['category_id']) && (int)$product['category_id'] === (int)$c['id']) ? 'selected' : '' ?>>
                                    <?= $e($c['name']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-outline">unfold_more</span>
                        </div>
                    </div>

                    <!-- Product Image -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-bold text-primary font-headline flex items-center gap-2" for="image">
                            <span class="material-symbols-outlined text-sm">image</span>
                            Product image <?= $isEdit ? '(leave empty)' : '<span class="text-error">*</span>' ?>
                        </label>
                        <div class="flex items-center gap-4 p-4 bg-surface-container-lowest rounded-lg border-2 border-dashed border-outline-variant/50">
                            <div id="image-preview" class="image-preview shrink-0" aria-live="polite">
                                <?php if ($isEdit && !empty($product['image'])): ?>
                                    <div class="flex flex-col gap-2">
                                        <p class="text-xs text-on-surface-variant font-body opacity-70">Current: 
                                            <span class="font-mono"><?= $e($product['image']) ?></span>
                                        </p>
                                        <div class="w-16 h-16 rounded-lg overflow-hidden border border-outline-variant/20 shadow-sm">
                                            <img src="<?= (strpos($product['image'] ?? '', 'http') === 0) ? $product['image'] : '/uploads/' . $product['image'] ?>" 
                                                 class="w-full h-full object-cover" alt="Current product image">
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="w-16 h-16 rounded-full bg-surface-container overflow-hidden flex items-center justify-center">
                                        <span class="material-symbols-outlined text-outline-variant text-3xl">photo_camera</span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="flex flex-col flex-1">
                                <p class="text-xs text-on-surface-variant font-medium mb-2">Upload a high-resolution image. Max 2MB.</p>
                                <label class="cursor-pointer inline-flex items-center justify-center px-4 py-2 bg-surface-container-highest text-primary font-headline font-bold text-xs rounded-lg hover:bg-surface-container-high transition-all w-fit">
                                    Browse
                                    <input type="file" id="image" name="image"
                                        accept="image/jpeg,image/png,image/gif,image/webp"
                                        <?= $isEdit ? '' : 'required' ?> class="hidden">
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center gap-4 pt-6 mt-6 border-t border-outline-variant/20">
                    <button type="submit"
                        class="flex-1 md:flex-none px-10 py-3 bg-gradient-to-br from-primary to-primary-container text-white font-headline font-bold rounded-lg shadow-md hover:opacity-90 active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-sm">save</span>
                        <?= $isEdit ? 'Save Changes' : 'Save Product' ?>
                    </button>
                    <button type="reset"
                        class="flex-1 md:flex-none px-10 py-3 bg-transparent border border-outline-variant text-primary font-headline font-bold rounded-lg hover:bg-surface-container-high transition-all flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-sm">restart_alt</span>
                        Reset
                    </button>
                    <a href="<?= defined('BASE_URL') ? BASE_URL . '/admin/products' : '/admin/products' ?>"
                        class="text-primary font-bold hover:underline ml-2">← Back</a>
                </div>
            </form>
        </section>

        <!-- Media Sidebar -->
        <aside class="lg:col-span-4 space-y-6">
            <div class="bg-surface-container-high rounded-xl p-6 border-2 border-dashed border-outline-variant/30 flex flex-col items-center justify-center text-center gap-4 group cursor-pointer hover:border-tertiary-fixed transition-colors">
                <div class="w-full aspect-square rounded-xl bg-surface-container-highest overflow-hidden relative mb-2">
                    <div class="absolute inset-0 flex flex-col items-center justify-center p-6">
                        <span class="material-symbols-outlined text-4xl text-secondary mb-2">photo_camera</span>
                        <span class="font-headline font-bold text-primary">Product picture</span>
                        <p class="text-xs text-on-surface-variant font-body px-4 mt-2">Upload a high-resolution editorial shot of the product</p>
                    </div>
                </div>
            </div>
            <!-- Hint Card -->
            <div class="bg-surface-container-low p-6 rounded-xl space-y-4">
                <h3 class="font-headline font-bold text-sm text-secondary uppercase tracking-widest">Editor's Note</h3>
                <p class="text-sm font-body text-on-surface-variant leading-relaxed">
                    Ensure all products have accurate pricing and are assigned to the correct operational category for morning shift reporting.
                </p>
            </div>
        </aside>
    </div>
</main>

<!-- Add Category Modal -->
<div id="add-category-modal" class="modal-backdrop" role="dialog" aria-modal="true"
    aria-labelledby="add-category-title" hidden
    style="position:fixed;inset:0;background:rgba(0,0,0,0.4);display:flex;align-items:center;justify-content:center;z-index:100;">
    <div class="bg-surface-container-lowest rounded-xl p-8 max-w-sm w-full mx-4 shadow-2xl">
        <h2 id="add-category-title" class="font-headline text-2xl font-black text-primary mb-6 italic tracking-tight">Add category</h2>
        <form id="add-category-form">
            <div class="flex flex-col gap-2 mb-6">
                <label class="text-sm font-bold text-primary font-headline flex items-center gap-2" for="new_category_name">
                    <span class="material-symbols-outlined text-sm">label_important</span>
                    Category name
                </label>
                <input type="text" id="new_category_name"
                    class="bg-surface-container-lowest border-none ring-1 ring-outline-variant/30 focus:ring-2 focus:ring-secondary/50 rounded-lg p-3 text-on-surface placeholder:text-outline transition-all font-body uppercase text-sm tracking-wider font-bold"
                    placeholder="E.G. HOT DRINKS"
                    required>
            </div>
            <div class="flex items-center gap-3 pt-4 border-t border-outline-variant/10">
                <button type="submit" class="flex-1 px-6 py-2.5 bg-gradient-to-br from-primary to-primary-container text-white font-headline font-bold rounded-lg shadow-sm hover:opacity-90 active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-sm">add</span>
                    Add
                </button>
                <button type="button" id="add-category-cancel" class="flex-1 px-6 py-2.5 bg-transparent border border-outline-variant text-primary font-headline font-bold rounded-lg hover:bg-surface-container-high transition-all">
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
