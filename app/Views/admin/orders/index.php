<h1>Admin Orders</h1>

<table border="1" cellpadding="10">

<thead>

<tr>
<th>Date</th>
<th>User</th>
<th>Room</th>
<th>Ext</th>
<th>Amount</th>
<th>Actions</th>
</tr>

</thead>

<tbody>

<?php if (!empty($orders)): ?>

<?php foreach ($orders as $order): ?>

<tr>

<td>
<?= htmlspecialchars($order['created_at']) ?>
</td>

<td>
<?= htmlspecialchars($order['user_name']) ?>
</td>

<td>
<?= htmlspecialchars($order['room_no']) ?>
</td>

<td>
<?= htmlspecialchars($order['ext']) ?>
</td>

<td>
<?= htmlspecialchars($order['total_amount']) ?> EGP
</td>

<td>

<?php if ($order['status'] === 'Processing'): ?>

<a href="/admin/orders/deliver?id=<?= $order['id'] ?>">
Deliver
</a>

<?php endif; ?>


<?php if ($order['status'] === 'Out for Delivery'): ?>

<a href="/admin/orders/done?id=<?= $order['id'] ?>">
Done
</a>

<?php endif; ?>


<a href="/admin/orders/details?id=<?= $order['id'] ?>">
View
</a>

</td>

</tr>

<?php endforeach; ?>

<?php else: ?>

<tr>
<td colspan="6">No orders found</td>
</tr>

<?php endif; ?>

</tbody>

</table>