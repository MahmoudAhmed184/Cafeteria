<?php

$products = isset($products) && is_array($products) ? $products : [];
$e = static fn($value) => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');

$active_page = 'products';
ob_start();
?>
<main class="max-w-[1400px] mx-auto px-8 py-10">
    <header class="flex items-end justify-between mb-12">
        <div>
            <h1 class="font-headline text-5xl font-extrabold text-primary tracking-tight mb-2">All Products</h1>
            <p class="text-secondary font-medium mt-2">Manage your cafeteria's premium catalog</p>
        </div>
        <a href="<?= defined('BASE_URL') ? BASE_URL . '/admin/products/create' : '/admin/products/create' ?>"
            class="flex items-center gap-2 px-6 py-3 bg-tertiary-fixed text-on-tertiary-fixed font-bold rounded-lg hover:translate-y-[-2px] transition-all duration-200 shadow-sm active:opacity-80">
            <span class="material-symbols-outlined text-sm">add</span>
            Add product
        </a>
    </header>

    <!-- Product Table -->
    <div class="bg-surface-container-lowest rounded-xl overflow-hidden shadow-[0px_12px_32px_rgba(41,24,6,0.04)] border border-outline-variant/10">
        <table class="w-full text-left border-collapse table-fixed">
            <thead class="bg-surface-container-low text-on-surface-variant text-xs font-semibold tracking-wider uppercase border-b border-outline-variant/20">
                <tr>
                    <th class="px-6 py-4 w-[35%]">Product</th>
                    <th class="px-6 py-4 w-[15%]">Price</th>
                    <th class="px-6 py-4 w-[20%] text-center">Image</th>
                    <th class="px-6 py-4 w-[30%] text-right">Action</th>
                </tr>
            </thead>
            <tbody class="text-on-surface font-body divide-y divide-outline-variant/10">
                <?php if ($products === []): ?>
                <tr>
                    <td colspan="4" class="py-12 px-6 text-center text-on-surface-variant">No products found.</td>
                </tr>
                <?php else: ?>
                <?php foreach ($products as $product): ?>
                <?php $productId = (int)($product['id'] ?? 0); ?>
                <tr class="hover:bg-primary/5 transition-colors h-[72px]">
                    <td class="px-6 py-0">
                        <div class="flex flex-col">
                            <span class="font-semibold text-[15px] text-primary truncate"><?= $e($product['name'] ?? '') ?></span>
                            <span class="text-[12px] text-secondary/80"><?= $e($product['category_name'] ?? '') ?></span>
                        </div>
                    </td>
                    <td class="px-6 py-0">
                        <div class="flex items-baseline gap-1">
                            <span class="text-[10px] font-semibold text-secondary">EGP</span>
                            <span class="text-lg font-bold text-primary"><?= $e(number_format((float)($product['price'] ?? 0), 2)) ?></span>
                        </div>
                    </td>
                    <td class="px-6 py-0">
                        <div class="flex justify-center">
                            <div class="w-12 h-12 rounded-lg overflow-hidden bg-surface-container flex-shrink-0">
                                <?php if (!empty($product['image'])): ?>
                                <img src="<?= (strpos($product['image'] ?? '', 'http') === 0) ? $product['image'] : '/uploads/' . $product['image'] ?>" alt="<?= $e($product['name'] ?? '') ?>"
                                    class="w-full h-full object-cover">
                                <?php else: ?>
                                <div class="w-full h-full flex items-center justify-center">
                                    <span class="material-symbols-outlined text-outline text-sm">image</span>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-0 text-right">
                        <div class="flex justify-end gap-3 items-center">
                            <?php if (!empty($product['is_available'])): ?>
                            <form method="post" action="<?= defined('BASE_URL') ? BASE_URL . '/admin/products/toggle' : '/admin/products/toggle' ?>">
                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars((string)csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
                                <input type="hidden" name="id" value="<?= $productId ?>">
                                <button type="submit" class="flex items-center gap-1.5 px-3 py-1.5 bg-green-50 border border-green-100 rounded-full text-[12px] font-bold text-green-700 uppercase tracking-tight hover:bg-green-100 transition-colors" style="min-width:110px; justify-content:center;">
                                    <span class="material-symbols-outlined text-[14px] text-green-700" style="font-variation-settings:'FILL' 1;">check_circle</span>
                                    available
                                </button>
                            </form>
                            <?php else: ?>
                            <form method="post" action="<?= defined('BASE_URL') ? BASE_URL . '/admin/products/toggle' : '/admin/products/toggle' ?>">
                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars((string)csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
                                <input type="hidden" name="id" value="<?= $productId ?>">
                                <button type="submit" class="flex items-center gap-1.5 px-3 py-1.5 bg-red-50 border border-red-100 rounded-full text-[12px] font-bold text-red-700 uppercase tracking-tight hover:bg-red-100 transition-colors" style="min-width:110px; justify-content:center;">
                                    <span class="material-symbols-outlined text-[14px] text-red-700" style="font-variation-settings:'FILL' 1;">cancel</span>
                                    unavailable
                                </button>
                            </form>
                            <?php endif; ?>
                            <a class="text-sm font-bold text-primary hover:underline"
                                href="<?= defined('BASE_URL') ? BASE_URL . '/admin/products/edit?id=' . $productId : '/admin/products/edit?id=' . $productId ?>">edit</a>
                            <form method="post" action="<?= defined('BASE_URL') ? BASE_URL . '/admin/products/delete' : '/admin/products/delete' ?>">
                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars((string)csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
                                <input type="hidden" name="id" value="<?= $productId ?>">
                                <button type="submit" class="text-sm font-bold text-error hover:underline"
                                    onclick="return confirm('Delete this product?')">delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        <!-- Pagination -->
        <footer class="px-6 py-5 bg-surface-container-low flex items-center justify-between">
            <p class="text-sm text-on-surface-variant">
                Showing <span class="font-bold text-primary"><?= count($products) ?></span> products
            </p>
            <?php
            $currentPage = (int) ($_GET['page'] ?? 1);
            $basePath = defined('BASE_URL') ? BASE_URL . '/admin/products' : '/admin/products';
            require __DIR__ . '/../../partials/pagination.php';
            ?>
        </footer>
    </div>
</main>
<?php
$content = ob_get_clean();
$pageTitle = 'Manage products';
require __DIR__ . '/../../layouts/app.php';
