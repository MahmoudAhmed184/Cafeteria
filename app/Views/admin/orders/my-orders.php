<h1>My Orders</h1>

<table border="1" cellpadding="10">

<thead>
<tr>
<th>Date</th>
<th>Status</th>
<th>Total</th>
<th>Action</th>
</tr>
</thead>

<tbody>

<?php foreach ($orders as $order): ?>

<tr>

<td>
<?= $order['created_at'] ?>
</td>

<td>
<?= $order['status'] ?>
</td>

<td>
<?= $order['total_amount'] ?> EGP
</td>

<td>

<?php if ($order['status'] === 'Processing'): ?>

<a href="/orders/cancel?id=<?= $order['id'] ?>">
Cancel
</a>

<?php endif; ?>

<a href="/orders/details?id=<?= $order['id'] ?>">
View
</a>

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>