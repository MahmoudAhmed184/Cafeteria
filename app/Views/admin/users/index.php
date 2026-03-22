<?php

$users = isset($users) && is_array($users) ? $users : [];
$e = static fn($value) => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');

$active_page = 'users';
ob_start();
?>
<main class="max-w-[1440px] mx-auto px-8 py-10">
    <!-- Header -->
    <div class="flex justify-between items-baseline mb-12">
        <div>
            <h1 class="font-headline text-5xl font-extrabold text-[#33210d] tracking-tight">All Users</h1>
            <p class="text-secondary mt-2 font-medium">Manage employee access and department directories.</p>
        </div>
        <a href="<?= defined('BASE_URL') ? BASE_URL . '/admin/users/create' : '/admin/users/create' ?>"
            class="flex items-center gap-2 px-6 py-3 bg-tertiary-fixed text-on-tertiary-fixed font-bold rounded-lg hover:translate-y-[-2px] transition-all duration-200 shadow-sm active:opacity-80">
            <span class="material-symbols-outlined text-sm">person_add</span>
            Add user
        </a>
    </div>

    <!-- Data Table -->
    <div class="bg-surface-container-low rounded-xl overflow-hidden shadow-[0px_12px_32px_rgba(41,24,6,0.04)] border border-outline-variant/10">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-surface-container-high/50 border-b border-outline-variant/20">
                    <th class="px-8 py-5 font-headline font-bold text-[#4B3621] text-sm uppercase tracking-widest">Image</th>
                    <th class="px-8 py-5 font-headline font-bold text-[#4B3621] text-sm uppercase tracking-widest">Name</th>
                    <th class="px-8 py-5 font-headline font-bold text-[#4B3621] text-sm uppercase tracking-widest">Room</th>
                    <th class="px-8 py-5 font-headline font-bold text-[#4B3621] text-sm uppercase tracking-widest">Ext.</th>
                    <th class="px-8 py-5 font-headline font-bold text-[#4B3621] text-sm uppercase tracking-widest text-right">Action</th>
                </tr>
            </thead>
            <tbody class="text-on-surface">
                <?php if ($users === []): ?>
                <tr>
                    <td colspan="5" class="py-12 px-8 text-center text-on-surface-variant">No users found.</td>
                </tr>
                <?php else: ?>
                <?php foreach ($users as $i => $user): ?>
                <?php $userId = (int)($user['id'] ?? 0); ?>
                <tr class="<?= $i % 2 === 1 ? 'bg-surface-container/30' : '' ?> hover:bg-surface-container-highest/40 transition-colors">
                    <td class="px-8 py-4">
                        <?php if (!empty($user['profile_pic'])): ?>
                        <img class="w-10 h-10 rounded-full object-cover" src="<?= (strpos($user['profile_pic'] ?? '', 'http') === 0) ? $user['profile_pic'] : '/uploads/' . $user['profile_pic'] ?>" alt="<?= $e($user['name'] ?? '') ?>">
                        <?php else: ?>
                        <div class="w-10 h-10 rounded-full bg-surface-container-highest flex items-center justify-center">
                            <span class="material-symbols-outlined text-outline text-sm">person</span>
                        </div>
                        <?php endif; ?>
                    </td>
                    <td class="px-8 py-4 font-semibold text-primary"><?= $e($user['name'] ?? '') ?></td>
                    <td class="px-8 py-4 text-secondary"><?= $e($user['room_no'] ?? '') ?></td>
                    <td class="px-8 py-4 font-mono text-sm"><?= $e($user['ext'] ?? '') ?></td>
                    <td class="px-8 py-4 text-right space-x-4">
                        <a href="<?= defined('BASE_URL') ? BASE_URL . '/admin/users/edit?id=' . $userId : '/admin/users/edit?id=' . $userId ?>"
                            class="text-primary hover:underline font-bold text-sm">edit</a>
                        <form method="post" action="<?= defined('BASE_URL') ? BASE_URL . '/admin/users/delete' : '/admin/users/delete' ?>" class="inline">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars((string)csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
                            <input type="hidden" name="id" value="<?= $userId ?>">
                            <button type="submit" class="text-error hover:underline font-bold text-sm"
                                onclick="return confirm('Delete this user?')">delete</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        <!-- Pagination Controls -->
        <div class="px-8 py-6 flex items-center justify-between bg-surface-container-high/20">
            <div class="text-sm text-on-surface-variant font-medium">
                Showing <span class="text-primary font-bold"><?= count($users) ?></span> users
            </div>
            <?php
            $currentPage = (int) ($_GET['page'] ?? 1);
            $basePath = defined('BASE_URL') ? BASE_URL . '/admin/users' : '/admin/users';
            require __DIR__ . '/../../partials/pagination.php';
            ?>
        </div>
    </div>
</main>
<?php
$content = ob_get_clean();
$pageTitle = 'Manage users';
require __DIR__ . '/../../layouts/app.php';
