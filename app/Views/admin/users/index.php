<h1>Users</h1>

<table border="1" cellpadding="10">

<thead>

<tr>
<th>Image</th>
<th>Name</th>
<th>Room</th>
<th>Ext</th>
<th>Actions</th>
</tr>

</thead>

<tbody>

<?php if (!empty($users)): ?>

<?php foreach ($users as $user): ?>

<tr>

<td>

<?php if (!empty($user['profile_pic'])): ?>

<img src="/<?= $user['profile_pic'] ?>" width="50">

<?php else: ?>

<img src="/assets/images/default-avatar.png" width="50">

<?php endif; ?>

</td>

<td>
<?= htmlspecialchars($user['name']) ?>
</td>

<td>
<?= htmlspecialchars($user['room_no']) ?>
</td>

<td>
<?= htmlspecialchars($user['ext']) ?>
</td>

<td>

<a href="/admin/users/edit?id=<?= $user['id'] ?>">
Edit
</a>

<a
href="/admin/users/delete?id=<?= $user['id'] ?>"
onclick="return confirm('Delete this user?')"
>
Delete
</a>

</td>

</tr>

<?php endforeach; ?>

<?php else: ?>

<tr>
<td colspan="5">No users found</td>
</tr>

<?php endif; ?>

</tbody>

</table>