<?php

$currentPage = max(1, (int) ($currentPage ?? 1));
$totalPages = max(1, (int) ($totalPages ?? 1));
$basePath = (string) ($basePath ?? '');

if ($totalPages <= 1) {
    return;
}
?>
<nav class="pagination" aria-label="Pagination">
    <?php for ($page = 1; $page <= $totalPages; $page++): ?>
    <a class="pagination-link <?= $page === $currentPage ? 'is-active' : '' ?>" href="<?= htmlspecialchars($basePath . (str_contains($basePath, '?') ? '&' : '?') . 'page=' . $page, ENT_QUOTES, 'UTF-8') ?>"><?= $page ?></a>
    <?php endfor; ?>
</nav>
