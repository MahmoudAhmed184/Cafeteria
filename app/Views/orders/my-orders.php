<h1>My Orders</h1>

<table border="1" cellpadding="10">

<thead>

<tr>
<th>Date</th>
<th>Status</th>
<th>Total</th>
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
<?= htmlspecialchars($order['status']) ?>
</td>

<td>
<?= htmlspecialchars($order['total_amount']) ?> EGP
</td>

<td>

<!-- Cancel Order -->

<?php if ($order['status'] === 'Processing'): ?>

<a 
href="/orders/cancel?id=<?= $order['id'] ?>"
onclick="return confirm('Are you sure you want to cancel this order?')"
>
Cancel
</a>

<?php endif; ?>


<!-- View Order Details -->

<a 
href="#"
class="view-order"
data-id="<?= $order['id'] ?>"
>
View
</a>

</td>

</tr>

<?php endforeach; ?>

<?php else: ?>

<tr>

<td colspan="4">
No orders found
</td>

</tr>

<?php endif; ?>

</tbody>

</table>


<!-- Include Orders JavaScript -->

<script src="/assets/js/orders.js"></script>