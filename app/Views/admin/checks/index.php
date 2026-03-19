<h1>Financial Checks</h1>

<form method="GET">

<label>Date From</label>
<input type="date" name="from">

<label>Date To</label>
<input type="date" name="to">

<button type="submit">
Filter
</button>

</form>


<table border="1" cellpadding="10">

<thead>

<tr>
<th>User</th>
<th>Total Amount</th>
<th>Details</th>
</tr>

</thead>

<tbody>

<?php if (!empty($checks)): ?>

<?php foreach ($checks as $check): ?>

<tr>

<td>
<?= htmlspecialchars($check['user_name']) ?>
</td>

<td>
<?= htmlspecialchars($check['total_amount']) ?> EGP
</td>

<td>

<a
href="#"
class="view-user-orders"
data-id="<?= $check['user_id'] ?>"
>
View Orders
</a>

</td>

</tr>

<?php endforeach; ?>

<?php else: ?>

<tr>
<td colspan="3">No data found</td>
</tr>

<?php endif; ?>

</tbody>

</table>


<script src="/assets/js/admin/checks.js"></script>