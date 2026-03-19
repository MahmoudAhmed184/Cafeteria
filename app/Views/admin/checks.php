<?php

$summary = isset($summary) && is_array($summary) ? $summary : [];
$usersList = isset($usersList) && is_array($usersList) ? $usersList : [];
$e = static fn($value) => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
ob_start();
?>
<h1 class="admin-page-title">Financial Checks</h1>
<form method="get" action="<?= defined('BASE_URL') ? BASE_URL . '/admin/checks' : '/admin/checks' ?>" class="card form-grid">
    <div class="form-group">
        <label class="form-label" for="date_from">From</label>
        <input class="form-control" type="date" id="date_from" name="date_from" value="<?= $e($_GET['date_from'] ?? '') ?>">
    </div>
    <div class="form-group">
        <label class="form-label" for="date_to">To</label>
        <input class="form-control" type="date" id="date_to" name="date_to" value="<?= $e($_GET['date_to'] ?? '') ?>">
    </div>
    <div class="form-group">
        <label class="form-label" for="user_id">User</label>
        <select class="form-control" id="user_id" name="user_id">
            <option value="">All users</option>
            <?php foreach ($usersList as $user): ?>
            <option value="<?= (int) ($user['id'] ?? 0) ?>" <?= ((string) ($user['id'] ?? '') === (string) ($_GET['user_id'] ?? '')) ? 'selected' : '' ?>><?= $e($user['name'] ?? '') ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-actions">
        <button class="btn btn-primary" type="submit">Filter</button>
    </div>
</form>
<div class="card">
    <table class="table">
        <thead>
            <tr>
                <th>User</th>
                <th>Total Spent</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($summary === []): ?>
            <tr><td colspan="2">No data found.</td></tr>
            <?php else: ?>
            <?php foreach ($summary as $row): ?>
            <tr>
                <td><?= $e($row['name'] ?? '') ?></td>
                <td><?= $e(number_format((float) ($row['total_spent'] ?? 0), 2)) ?> EGP</td>
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
$pageTitle = 'Financial Checks';
require __DIR__ . '/../layouts/app.php';
