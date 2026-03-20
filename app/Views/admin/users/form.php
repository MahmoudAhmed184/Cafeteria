<?php

$user = $user ?? null;
$rooms = $rooms ?? [];
$isEdit = is_array($user) && !empty($user['id']);
$e = static fn($value) => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
$action = $isEdit ? '/admin/users/update' : '/admin/users/store';
$csrfToken = function_exists('csrf_token') ? csrf_token() : '';
ob_start();
?>
<h1 class="admin-page-title"><?= $isEdit ? 'Edit User' : 'Add User' ?></h1>
<form class="card" method="post" action="<?= defined('BASE_URL') ? BASE_URL . $action : $action ?>" enctype="multipart/form-data">
    <input type="hidden" name="csrf_token" value="<?= $e($csrfToken) ?>">
    <?php if ($isEdit): ?>
    <input type="hidden" name="id" value="<?= (int) $user['id'] ?>">
    <?php endif; ?>
    <div class="form-group">
        <label class="form-label" for="name">Name</label>
        <input class="form-control" type="text" id="name" name="name" value="<?= $e($user['name'] ?? '') ?>" required>
    </div>
    <div class="form-group">
        <label class="form-label" for="email">Email</label>
        <input class="form-control" type="email" id="email" name="email" value="<?= $e($user['email'] ?? '') ?>" required>
    </div>
    <div class="form-group">
        <label class="form-label" for="room_no">Room</label>
        <select class="form-control" id="room_no" name="room_no" required>
            <option value="">Select Room</option>
            <?php foreach ($rooms as $r): ?>
            <option value="<?= $e($r['room_number']) ?>" <?= ($user['room_no'] ?? '') === $r['room_number'] ? 'selected' : '' ?>><?= $e($r['room_number']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <label class="form-label" for="ext">Extension</label>
        <input class="form-control" type="text" id="ext" name="ext" value="<?= $e($user['ext'] ?? '') ?>" required>
    </div>
    <div class="form-group">
        <label class="form-label" for="password">Password<?= $isEdit ? ' (leave blank to keep current)' : '' ?></label>
        <input class="form-control" type="password" id="password" name="password" <?= $isEdit ? '' : 'required' ?> minlength="8">
    </div>
    <div class="form-group">
        <label class="form-label" for="confirm_password">Confirm Password</label>
        <input class="form-control" type="password" id="confirm_password" name="confirm_password" <?= $isEdit ? '' : 'required' ?> minlength="8">
    </div>
    <div class="form-group">
        <label class="form-label" for="profile_pic">Profile Picture</label>
        <input class="form-control" type="file" id="profile_pic" name="profile_pic" accept="image/jpeg,image/png,image/gif,image/webp">
    </div>
    <div class="form-actions">
        <button class="btn btn-primary" type="submit">Save</button>
        <button class="btn btn-secondary" type="reset">Reset</button>
        <a class="btn btn-outline" href="<?= defined('BASE_URL') ? BASE_URL . '/admin/users' : '/admin/users' ?>">Back</a>
    </div>
</form>
<?php
$content = ob_get_clean();
$showSidebar = true;
$pageCss = 'admin.css';
$pageTitle = $isEdit ? 'Edit User' : 'Add User';
require __DIR__ . '/../../layouts/app.php';
