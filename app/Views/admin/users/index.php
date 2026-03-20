<?php

$users = isset($users) && is_array($users) ? $users : [];
$e = static fn($value) => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
ob_start();
?>
<div class="dashboard-header">
    <h1 class="admin-page-title">Users</h1>
    <a class="btn btn-primary" href="<?= defined('BASE_URL') ? BASE_URL . '/admin/users/create' : '/admin/users/create' ?>">Add User</a>
</div>
<div class="card">
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Room</th>
                <th>Ext</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($users === []): ?>
            <tr><td colspan="6">No users found.</td></tr>
            <?php else: ?>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><?= (int) ($user['id'] ?? 0) ?></td>
                <td><?= $e($user['name'] ?? '') ?></td>
                <td><?= $e($user['email'] ?? '') ?></td>
                <td><?= $e($user['room_no'] ?? '') ?></td>
                <td><?= $e($user['ext'] ?? '') ?></td>
                <td class="actions">
                    <a class="btn btn-outline" href="<?= defined('BASE_URL') ? BASE_URL . '/admin/users/edit?id=' . (int) ($user['id'] ?? 0) : '/admin/users/edit?id=' . (int) ($user['id'] ?? 0) ?>">Edit</a>
                    <form method="post" action="<?= defined('BASE_URL') ? BASE_URL . '/admin/users/delete' : '/admin/users/delete' ?>">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars((string)csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
                        <input type="hidden" name="id" value="<?= (int) ($user['id'] ?? 0) ?>">
                        <button type="submit" class="btn btn-outline">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php
$content = ob_get_clean();
$showSidebar = true;
$pageCss = 'admin.css';
$pageTitle = 'Users';
require __DIR__ . '/../../layouts/app.php';
