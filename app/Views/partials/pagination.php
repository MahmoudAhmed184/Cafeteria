<div class="pagination">

<?php if ($currentPage > 1): ?>

<a href="?page=<?= $currentPage - 1 ?>">
Previous
</a>

<?php endif; ?>


<?php for ($i = 1; $i <= $totalPages; $i++): ?>

<a href="?page=<?= $i ?>"
   style="<?= $i == $currentPage ? 'font-weight:bold;' : '' ?>">

<?= $i ?>

</a>

<?php endfor; ?>


<?php if ($currentPage < $totalPages): ?>

<a href="?page=<?= $currentPage + 1 ?>">
Next
</a>

<?php endif; ?>

</div>