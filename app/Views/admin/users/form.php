<?php

$user = $user ?? null;
$rooms = $rooms ?? [];
$isEdit = is_array($user) && !empty($user['id']);
$e = static fn($value) => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
$action = $isEdit ? '/admin/users/update' : '/admin/users/store';
$csrfToken = function_exists('csrf_token') ? csrf_token() : '';

$active_page = 'users';
ob_start();
?>
<div class="max-w-3xl mx-auto px-6 lg:px-8 py-8">
    <header class="mb-8">
        <div class="flex justify-between items-start">
            <div>
                <div class="flex items-center gap-2 text-xs text-on-surface-variant mb-2">
                    <a href="<?= defined('BASE_URL') ? BASE_URL . '/admin/users' : '/admin/users' ?>"
                        class="hover:text-primary transition-colors">Users</a>
                    <span>/</span>
                    <span><?= $isEdit ? 'Edit' : 'Add' ?></span>
                </div>
                <h1 class="text-2xl font-headline font-semibold text-primary">
                    <?= $isEdit ? 'Edit User' : 'Add User' ?>
                </h1>
            </div>
            <a href="<?= defined('BASE_URL') ? BASE_URL . '/admin/users' : '/admin/users' ?>"
                class="text-sm font-medium text-on-surface-variant hover:text-primary transition-colors px-3 py-1.5 border border-outline-variant/30 rounded-lg hidden sm:block">Back
                to users</a>
        </div>
    </header>

    <form method="post" action="<?= defined('BASE_URL') ? BASE_URL . $action : $action ?>" enctype="multipart/form-data"
        class="bg-surface-container-lowest rounded-lg border border-outline-variant/20 p-6 space-y-6">
        <input type="hidden" name="csrf_token" value="<?= $e($csrfToken) ?>">
        <?php if ($isEdit): ?>
            <input type="hidden" name="id" value="<?= (int) $user['id'] ?>">
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

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <!-- Name -->
            <div class="space-y-1.5">
                <label class="block text-sm font-medium text-on-surface" for="name">Name</label>
                <input type="text" id="name" name="name" required value="<?= $e($user['name'] ?? '') ?>"
                    class="w-full bg-surface-container-low border border-outline-variant/30 rounded-lg py-2.5 px-3.5 text-sm text-on-surface focus:ring-2 focus:ring-primary/20 focus:border-primary focus:outline-none transition"
                    placeholder="e.g. Julian Artisan">
            </div>

            <!-- Email -->
            <div class="space-y-1.5">
                <label class="block text-sm font-medium text-on-surface" for="email">Email</label>
                <input type="email" id="email" name="email" required value="<?= $e($user['email'] ?? '') ?>"
                    class="w-full bg-surface-container-low border border-outline-variant/30 rounded-lg py-2.5 px-3.5 text-sm text-on-surface focus:ring-2 focus:ring-primary/20 focus:border-primary focus:outline-none transition"
                    placeholder="julian@company.com">
            </div>

            <!-- Password -->
            <div class="space-y-1.5">
                <label class="block text-sm font-medium text-on-surface" for="password">
                    Password
                    <?= $isEdit ? '<span class="text-xs text-on-surface-variant font-normal">(leave blank to keep current)</span>' : '' ?>
                </label>
                <input type="password" id="password" name="password" <?= $isEdit ? '' : 'required' ?> minlength="8"
                    class="w-full bg-surface-container-low border border-outline-variant/30 rounded-lg py-2.5 px-3.5 text-sm text-on-surface focus:ring-2 focus:ring-primary/20 focus:border-primary focus:outline-none transition"
                    placeholder="••••••••">
            </div>

            <!-- Confirm Password -->
            <div class="space-y-1.5">
                <label class="block text-sm font-medium text-on-surface" for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" <?= $isEdit ? '' : 'required' ?>
                    minlength="8"
                    class="w-full bg-surface-container-low border border-outline-variant/30 rounded-lg py-2.5 px-3.5 text-sm text-on-surface focus:ring-2 focus:ring-primary/20 focus:border-primary focus:outline-none transition"
                    placeholder="••••••••">
            </div>

            <!-- Room -->
            <div class="space-y-1.5">
                <label class="block text-sm font-medium text-on-surface" for="room_no">Room</label>
                <select id="room_no" name="room_no" required
                    class="w-full bg-surface-container-low border border-outline-variant/30 rounded-lg py-2.5 px-3.5 text-sm text-on-surface focus:ring-2 focus:ring-primary/20 focus:border-primary focus:outline-none transition custom-select">
                    <option value="">Select room</option>
                    <?php foreach ($rooms as $r): ?>
                        <option value="<?= $e($r['room_number']) ?>" <?= ($user['room_no'] ?? '') === $r['room_number'] ? 'selected' : '' ?>>
                            <?= $e($r['room_number']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Extension -->
            <div class="space-y-1.5">
                <label class="block text-sm font-medium text-on-surface" for="ext">Extension</label>
                <input type="text" id="ext" name="ext" required value="<?= $e($user['ext'] ?? '') ?>"
                    class="w-full bg-surface-container-low border border-outline-variant/30 rounded-lg py-2.5 px-3.5 text-sm text-on-surface focus:ring-2 focus:ring-primary/20 focus:border-primary focus:outline-none transition"
                    placeholder="e.g. 4592">
            </div>
        </div>

        <!-- Profile Picture -->
        <div class="space-y-1.5">
            <span class="block text-sm font-medium text-on-surface">Profile Picture
                <?= $isEdit ? '<span class="text-xs text-on-surface-variant font-normal">(leave empty to keep current)</span>' : '' ?></span>
            <div
                class="flex items-center gap-4 p-4 bg-surface-container-low rounded-lg border border-outline-variant/30 border-dashed file-upload-zone">
                <div id="image-preview"
                    class="w-14 h-14 rounded-lg overflow-hidden bg-surface-container flex items-center justify-center shrink-0">
                    <?php if ($isEdit && !empty($user['profile_pic'])): ?>
                        <img src="<?= (strpos($user['profile_pic'] ?? '', 'http') === 0) ? $user['profile_pic'] : '/uploads/' . $user['profile_pic'] ?>"
                            alt="Current profile" class="w-full h-full object-cover" loading="lazy">
                    <?php else: ?>
                        <span class="material-symbols-outlined text-on-surface-variant text-xl">account_circle</span>
                    <?php endif; ?>
                </div>
                <div class="flex flex-col flex-1">
                    <p class="text-xs text-on-surface-variant mb-2">JPG, PNG, GIF, or WebP. Max 2MB.</p>
                    <label
                        class="cursor-pointer inline-flex items-center justify-center px-4 py-2 bg-surface-container-highest text-sm font-medium text-on-surface rounded-md hover:bg-surface-container-high transition-all w-fit focus-within:ring-2 focus-within:ring-primary/20">
                        Browse
                        <input type="file" id="profile_pic" name="profile_pic"
                            accept="image/jpeg,image/png,image/gif,image/webp" class="sr-only">
                    </label>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center gap-3 pt-4 border-t border-outline-variant/15">
            <button type="submit"
                class="px-6 py-2.5 bg-primary text-on-primary font-body font-semibold text-sm rounded-lg hover:bg-primary-container active:scale-[0.99] transition-all flex items-center gap-2 focus:outline-none focus:ring-2 focus:ring-primary/30">
                <span class="material-symbols-outlined text-[18px]">save</span>
                Save
            </button>
            <button type="reset"
                class="px-6 py-2.5 bg-transparent border border-outline-variant/40 text-on-surface font-body font-medium text-sm rounded-lg hover:bg-surface-container-high transition-all flex items-center gap-2 focus:outline-none focus:ring-2 focus:ring-outline/30">
                <span class="material-symbols-outlined text-[18px]">restart_alt</span>
                Reset
            </button>
        </div>
    </form>
</div>
<?php
$content = ob_get_clean();
$pageTitle = $isEdit ? 'Edit user' : 'Add user';
require __DIR__ . '/../../layouts/app.php';
