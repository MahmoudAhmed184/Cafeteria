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
<main class="max-w-5xl mx-auto px-8 py-12">
    <div class="mb-10">
        <h1 class="text-4xl font-extrabold text-primary tracking-tighter font-headline mb-2">
            <?= $isEdit ? 'Edit User' : 'Add User' ?>
        </h1>
    </div>

        <div class="bg-surface-container-low rounded-xl p-8 max-w-3xl">
            <form method="post"
                action="<?= defined('BASE_URL') ? BASE_URL . $action : $action ?>"
                enctype="multipart/form-data"
                class="space-y-8">
                <input type="hidden" name="csrf_token" value="<?= $e($csrfToken) ?>">
                <?php if ($isEdit): ?>
                <input type="hidden" name="id" value="<?= (int)$user['id'] ?>">
                <?php endif; ?>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                    <!-- Name -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-bold text-primary font-headline flex items-center gap-2" for="name">
                            <span class="material-symbols-outlined text-sm">person</span>
                            Name
                        </label>
                        <input type="text" id="name" name="name" required
                            value="<?= $e($user['name'] ?? '') ?>"
                            class="bg-surface-container-lowest border-none ring-1 ring-outline-variant/30 focus:ring-2 focus:ring-secondary/50 rounded-lg p-3 text-on-surface placeholder:text-outline transition-all font-body"
                            placeholder="e.g. Julian Artisan">
                    </div>

                    <!-- Email -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-bold text-primary font-headline flex items-center gap-2" for="email">
                            <span class="material-symbols-outlined text-sm">mail</span>
                            Email
                        </label>
                        <input type="email" id="email" name="email" required
                            value="<?= $e($user['email'] ?? '') ?>"
                            class="bg-surface-container-lowest border-none ring-1 ring-outline-variant/30 focus:ring-2 focus:ring-secondary/50 rounded-lg p-3 text-on-surface placeholder:text-outline transition-all font-body"
                            placeholder="julian@artisan.co">
                    </div>

                    <!-- Password -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-bold text-primary font-headline flex items-center gap-2" for="password">
                            <span class="material-symbols-outlined text-sm">lock</span>
                            Password<?= $isEdit ? ' (leave blank to keep current)' : '' ?>
                        </label>
                        <input type="password" id="password" name="password"
                            <?= $isEdit ? '' : 'required' ?> minlength="8"
                            class="bg-surface-container-lowest border-none ring-1 ring-outline-variant/30 focus:ring-2 focus:ring-secondary/50 rounded-lg p-3 text-on-surface placeholder:text-outline transition-all font-body"
                            placeholder="••••••••">
                    </div>

                    <!-- Confirm Password -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-bold text-primary font-headline flex items-center gap-2" for="confirm_password">
                            <span class="material-symbols-outlined text-sm">verified_user</span>
                            Confirm Password
                        </label>
                        <input type="password" id="confirm_password" name="confirm_password"
                            <?= $isEdit ? '' : 'required' ?> minlength="8"
                            class="bg-surface-container-lowest border-none ring-1 ring-outline-variant/30 focus:ring-2 focus:ring-secondary/50 rounded-lg p-3 text-on-surface placeholder:text-outline transition-all font-body"
                            placeholder="••••••••">
                    </div>

                    <!-- Room No -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-bold text-primary font-headline flex items-center gap-2" for="room_no">
                            <span class="material-symbols-outlined text-sm">door_front</span>
                            Room No.
                        </label>
                        <div class="relative">
                            <select id="room_no" name="room_no" required
                                class="w-full bg-surface-container-lowest border-none ring-1 ring-outline-variant/30 focus:ring-2 focus:ring-secondary/50 rounded-lg p-3 text-on-surface transition-all font-body appearance-none !bg-none pr-10">
                            <option value="">Select Room</option>
                            <?php foreach ($rooms as $r): ?>
                            <option value="<?= $e($r['room_number']) ?>"
                                <?= ($user['room_no'] ?? '') === $r['room_number'] ? 'selected' : '' ?>>
                                <?= $e($r['room_number']) ?>
                            </option>
                            <?php endforeach; ?>
                            </select>
                            <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-outline">unfold_more</span>
                        </div>
                    </div>

                    <!-- Ext -->
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-bold text-primary font-headline flex items-center gap-2" for="ext">
                            <span class="material-symbols-outlined text-sm">call</span>
                            Ext.
                        </label>
                        <input type="text" id="ext" name="ext" required
                            value="<?= $e($user['ext'] ?? '') ?>"
                            class="bg-surface-container-lowest border-none ring-1 ring-outline-variant/30 focus:ring-2 focus:ring-secondary/50 rounded-lg p-3 text-on-surface placeholder:text-outline transition-all font-body"
                            placeholder="4592">
                    </div>

                    <!-- Profile Picture -->
                    <div class="md:col-span-2 flex flex-col gap-2">
                        <label class="text-sm font-bold text-primary font-headline flex items-center gap-2" for="profile_pic">
                            <span class="material-symbols-outlined text-sm">image</span>
                            Profile picture
                        </label>
                        <div class="flex items-center gap-4 p-4 bg-surface-container-lowest rounded-lg border-2 border-dashed border-outline-variant/50">
                            <div class="w-16 h-16 rounded-full bg-surface-container overflow-hidden flex items-center justify-center shrink-0">
                                <?php if ($isEdit && !empty($user['profile_pic'])): ?>
                                <img src="<?= (strpos($user['profile_pic'] ?? '', 'http') === 0) ? $user['profile_pic'] : '/uploads/' . $user['profile_pic'] ?>" alt="Current profile" class="w-full h-full object-cover">
                                <?php else: ?>
                                <span class="material-symbols-outlined text-outline-variant text-3xl">account_circle</span>
                                <?php endif; ?>
                            </div>
                            <div class="flex flex-col flex-1">
                                <p class="text-xs text-on-surface-variant font-medium mb-2">Upload a high-quality JPG or PNG. Max 2MB.</p>
                                <label class="cursor-pointer inline-flex items-center justify-center px-4 py-2 bg-surface-container-highest text-primary font-headline font-bold text-xs rounded-lg hover:bg-surface-container-high transition-all w-fit">
                                    Browse
                                    <input type="file" id="profile_pic" name="profile_pic"
                                        accept="image/jpeg,image/png,image/gif,image/webp"
                                        class="hidden">
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center gap-4 pt-6 border-t border-outline-variant/20">
                    <button type="submit"
                        class="flex-1 md:flex-none px-10 py-3 bg-gradient-to-br from-primary to-primary-container text-white font-headline font-bold rounded-lg shadow-md hover:opacity-90 active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-sm">save</span>
                        Save
                    </button>
                    <button type="reset"
                        class="flex-1 md:flex-none px-10 py-3 bg-transparent border border-outline-variant text-primary font-headline font-bold rounded-lg hover:bg-surface-container-high transition-all flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-sm">restart_alt</span>
                        Reset
                    </button>
                    <a href="<?= defined('BASE_URL') ? BASE_URL . '/admin/users' : '/admin/users' ?>"
                        class="text-primary font-bold hover:underline ml-2">← Back</a>
                </div>
            </form>
        </div>
</main>
<?php
$content = ob_get_clean();
$pageTitle = $isEdit ? 'Edit user' : 'Add user';
require __DIR__ . '/../../layouts/app.php';
