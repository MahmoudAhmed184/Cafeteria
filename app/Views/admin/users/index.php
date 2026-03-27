<?php

$users = isset($users) && is_array($users) ? $users : [];
$e = static fn($value) => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');

$active_page = 'users';
ob_start();
?>
<div class="max-w-[1400px] mx-auto px-6 lg:px-8 py-8">
    <header class="flex flex-col sm:flex-row sm:items-end justify-between mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-headline font-semibold text-primary">Users</h1>
            <p class="text-sm text-on-surface-variant mt-1">Manage employee access and departments</p>
        </div>
        <a href="<?= defined('BASE_URL') ? BASE_URL . '/admin/users/create' : '/admin/users/create' ?>"
            class="inline-flex items-center gap-2 px-5 py-2.5 bg-secondary text-on-secondary font-body font-semibold text-sm rounded-lg hover:bg-secondary-container active:scale-[0.98] transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-secondary/30">
            <span class="material-symbols-outlined text-[18px]">person_add</span>
            Add user
        </a>
    </header>

    <!-- Users Table -->
    <div class="bg-surface-container-lowest rounded-lg border border-outline-variant/20 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-surface-container">
                    <tr>
                        <th class="px-6 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide">
                            Image</th>
                        <th class="px-6 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide">Name
                        </th>
                        <th class="px-6 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide">Room
                        </th>
                        <th class="px-6 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide">Ext.
                        </th>
                        <th
                            class="px-6 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wide text-right">
                            Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/15">
                    <?php if ($users === []): ?>
                        <tr>
                            <td colspan="5" class="py-12 px-6">
                                <div class="empty-state">
                                    <span class="material-symbols-outlined empty-state-icon">group_off</span>
                                    <p class="empty-state-text">No users found. Start by adding one.</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($users as $user): ?>
                            <?php $userId = (int) ($user['id'] ?? 0); ?>
                            <tr class="hover:bg-surface-container-low transition-colors duration-150">
                                <td class="px-6 py-4">
                                    <?php if (!empty($user['profile_pic'])): ?>
                                        <img class="w-9 h-9 rounded-full object-cover" loading="lazy"
                                            src="<?= (strpos($user['profile_pic'] ?? '', 'http') === 0) ? $user['profile_pic'] : '/uploads/' . $user['profile_pic'] ?>"
                                            alt="<?= $e($user['name'] ?? '') ?>">
                                    <?php else: ?>
                                        <img class="w-9 h-9 rounded-full object-cover" loading="lazy"
                                            src="https://ui-avatars.com/api/?name=<?= urlencode($user['name'] ?? 'U') ?>&background=e3e2df&color=33210d&size=72"
                                            alt="<?= $e($user['name'] ?? '') ?>">
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-sm font-medium text-on-surface"><?= $e($user['name'] ?? '') ?></td>
                                <td class="px-6 py-4 text-sm text-on-surface-variant"><?= $e($user['room_no'] ?? '') ?></td>
                                <td class="px-6 py-4 text-sm text-on-surface-variant"><?= $e($user['ext'] ?? '') ?></td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-1">
                                        <a href="<?= defined('BASE_URL') ? BASE_URL . '/admin/users/edit?id=' . $userId : '/admin/users/edit?id=' . $userId ?>"
                                            class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-on-surface-variant hover:bg-surface-container-high hover:text-primary transition-all duration-150"
                                            aria-label="Edit <?= $e($user['name'] ?? '') ?>" title="Edit">
                                            <span class="material-symbols-outlined text-[20px]">edit</span>
                                        </a>
                                        <form method="post"
                                            action="<?= defined('BASE_URL') ? BASE_URL . '/admin/users/delete' : '/admin/users/delete' ?>"
                                            class="inline">
                                            <input type="hidden" name="csrf_token"
                                                value="<?= htmlspecialchars((string) csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
                                            <input type="hidden" name="id" value="<?= $userId ?>">
                                            <button type="button"
                                                class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-error/70 hover:bg-error-container/30 hover:text-error transition-all duration-150"
                                                aria-label="Delete <?= $e($user['name'] ?? '') ?>" title="Delete"
                                                onclick="if(confirm('Delete this user?')) this.closest('form').submit();">
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
                Showing <span class="font-semibold text-on-surface"><?= count($users) ?></span> users
            </p>
            <?php
            $currentPage = (int) ($_GET['page'] ?? 1);
            $basePath = defined('BASE_URL') ? BASE_URL . '/admin/users' : '/admin/users';
            require __DIR__ . '/../../partials/pagination.php';
            ?>
        </footer>
    </div>
</div>
<?php
$content = ob_get_clean();
$pageTitle = 'Manage users';
require __DIR__ . '/../../layouts/app.php';
