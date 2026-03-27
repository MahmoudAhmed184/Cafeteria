<?php /* Frontend Polish Pass: standardized destructive button, table row hover, empty state */

$products = isset($products) && is_array($products) ? $products : [];
$e = static fn($value) => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');

$active_page = 'products';
ob_start();
?>
<div class="max-w-[1400px] mx-auto px-6 lg:px-8 py-8">
    <header class="flex flex-col sm:flex-row sm:items-end justify-between mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-headline font-semibold text-primary">Products</h1>
            <p class="text-sm text-on-surface-variant mt-1">Manage your cafeteria catalog</p>
        </div>
        <a href="<?= defined('BASE_URL') ? BASE_URL . '/admin/products/create' : '/admin/products/create' ?>"
            class="inline-flex items-center gap-2 px-5 py-2.5 bg-secondary text-on-secondary font-body font-semibold text-sm rounded-lg hover:bg-secondary-container active:scale-[0.98] transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-secondary/30">
            <span class="material-symbols-outlined text-[18px]">add</span>
            Add product
        </a>
    </header>

    <!-- Product Table -->
    <div class="bg-surface-container-lowest rounded-lg border border-outline-variant/20 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-surface-container">
                    <tr>
                        <th class="px-6 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide">Product</th>
                        <th class="px-6 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide">Price</th>
                        <th class="px-6 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide text-center">Image</th>
                        <th class="px-6 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/15">
                    <?php if ($products === []): ?>
                    <tr>
                        <td colspan="4" class="py-12 px-6">
                            <div class="empty-state">
                                <span class="material-symbols-outlined empty-state-icon">inventory_2</span>
                                <p class="empty-state-text">No products found. Start by adding one.</p>
                            </div>
                        </td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($products as $product): ?>
                    <?php $productId = (int)($product['id'] ?? 0); ?>
                    <tr class="hover:bg-surface-container-low transition-colors duration-150">
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="text-sm font-medium text-on-surface truncate"><?= $e($product['name'] ?? '') ?></span>
                                <span class="text-xs text-on-surface-variant"><?= $e($product['category_name'] ?? '') ?></span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-baseline gap-1">
                                <span class="text-xs text-secondary font-medium">EGP</span>
                                <span class="text-sm font-semibold text-on-surface"><?= $e(number_format((float)($product['price'] ?? 0), 2)) ?></span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex justify-center">
                                <div class="w-12 h-12 rounded-md overflow-hidden bg-surface-container">
                                    <?php if (!empty($product['image'])): ?>
                                    <img src="<?= (strpos($product['image'] ?? '', 'http') === 0) ? $product['image'] : '/uploads/' . $product['image'] ?>" alt="<?= $e($product['name'] ?? '') ?>"
                                        class="w-full h-full object-cover" loading="lazy">
                                    <?php else: ?>
                                    <div class="w-full h-full flex items-center justify-center">
                                        <span class="material-symbols-outlined text-on-surface-variant text-[16px]">image</span>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-3 items-center">
                                <?php if (!empty($product['is_available'])): ?>
                                <form method="post" action="<?= defined('BASE_URL') ? BASE_URL . '/admin/products/toggle' : '/admin/products/toggle' ?>">
                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars((string)csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
                                    <input type="hidden" name="id" value="<?= $productId ?>">
                                    <button type="submit" class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-success-container border border-success/20 rounded-full text-xs font-medium text-success hover:bg-success-container/60 transition-colors">
                                        <span class="material-symbols-outlined text-[14px]" style="font-variation-settings:'FILL' 1;">check_circle</span>
                                        Available
                                    </button>
                                </form>
                                <?php else: ?>
                                <form method="post" action="<?= defined('BASE_URL') ? BASE_URL . '/admin/products/toggle' : '/admin/products/toggle' ?>">
                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars((string)csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
                                    <input type="hidden" name="id" value="<?= $productId ?>">
                                    <button type="submit" class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-error-container/30 border border-error/20 rounded-full text-xs font-medium text-error hover:bg-error-container/50 transition-colors">
                                        <span class="material-symbols-outlined text-[14px]" style="font-variation-settings:'FILL' 1;">cancel</span>
                                        Unavailable
                                    </button>
                                </form>
                                <?php endif; ?>
                                <a href="<?= defined('BASE_URL') ? BASE_URL . '/admin/products/edit?id=' . $productId : '/admin/products/edit?id=' . $productId ?>"
                                    class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-on-surface-variant hover:bg-surface-container-high hover:text-primary transition-all duration-150"
                                    aria-label="Edit <?= $e($product['name'] ?? '') ?>"
                                    title="Edit">
                                    <span class="material-symbols-outlined text-[20px]">edit</span>
                                </a>
                                <form method="post" action="<?= defined('BASE_URL') ? BASE_URL . '/admin/products/delete' : '/admin/products/delete' ?>">
                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars((string)csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
                                    <input type="hidden" name="id" value="<?= $productId ?>">
                                    <button type="button"
                                        class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-error/70 hover:bg-error-container/30 hover:text-error transition-all duration-150"
                                        aria-label="Delete <?= $e($product['name'] ?? '') ?>"
                                        title="Delete"
                                        onclick="if(confirm('Delete this product?')) this.closest('form').submit();">
                                        <span class="material-symbols-outlined text-[20px]">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <footer class="px-6 py-4 bg-surface-container flex items-center justify-between">
            <p class="text-sm text-on-surface-variant">
                Showing <span class="font-semibold text-on-surface"><?= count($products) ?></span> products
            </p>
            <?php
            $currentPage = (int) ($_GET['page'] ?? 1);
            $basePath = defined('BASE_URL') ? BASE_URL . '/admin/products' : '/admin/products';
            require __DIR__ . '/../../partials/pagination.php';
            ?>
        </footer>
    </div>
</div>
<?php
$content = ob_get_clean();
$pageTitle = 'Manage products';
require __DIR__ . '/../../layouts/app.php';
