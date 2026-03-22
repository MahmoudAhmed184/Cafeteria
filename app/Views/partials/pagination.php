<?php

$currentPage = max(1, (int) ($currentPage ?? 1));
$totalPages = max(1, (int) ($totalPages ?? 1));
$basePath = (string) ($basePath ?? '');

if ($totalPages <= 1) {
    return;
}

$makeUrl = function ($page) use ($basePath) {
    return htmlspecialchars(
        $basePath . (str_contains($basePath, '?') ? '&' : '?') . 'page=' . $page,
        ENT_QUOTES,
        'UTF-8'
    );
};
?>
<div class="flex items-center gap-2 mt-0" aria-label="Pagination">
    <?php if ($currentPage > 1): ?>
    <a href="<?= $makeUrl($currentPage - 1) ?>"
        class="w-10 h-10 flex items-center justify-center rounded-lg bg-surface-container-highest text-on-surface-variant hover:bg-surface-container-high transition-colors"
        aria-label="Previous page">
        <span class="material-symbols-outlined">chevron_left</span>
    </a>
    <?php else: ?>
    <span class="w-10 h-10 flex items-center justify-center rounded-lg bg-surface-container-highest text-on-surface-variant opacity-40 cursor-not-allowed">
        <span class="material-symbols-outlined">chevron_left</span>
    </span>
    <?php endif; ?>

    <?php for ($page = 1; $page <= $totalPages; $page++): ?>
        <?php if ($page === $currentPage): ?>
        <span class="w-10 h-10 flex items-center justify-center rounded-lg bg-primary text-on-primary font-black shadow-md"
            aria-current="page"><?= $page ?></span>
        <?php elseif ($page === 1 || $page === $totalPages || abs($page - $currentPage) <= 1): ?>
        <a href="<?= $makeUrl($page) ?>"
            class="w-10 h-10 flex items-center justify-center rounded-lg bg-surface-container-highest text-on-surface-variant hover:bg-surface-container-high transition-colors font-bold text-sm"
            aria-label="Go to page <?= $page ?>"><?= $page ?></a>
        <?php elseif (abs($page - $currentPage) === 2): ?>
        <span class="px-2 text-on-surface-variant">…</span>
        <?php endif; ?>
    <?php endfor; ?>

    <?php if ($currentPage < $totalPages): ?>
    <a href="<?= $makeUrl($currentPage + 1) ?>"
        class="w-10 h-10 flex items-center justify-center rounded-lg bg-surface-container-highest text-on-surface-variant hover:bg-surface-container-high transition-colors"
        aria-label="Next page">
        <span class="material-symbols-outlined">chevron_right</span>
    </a>
    <?php else: ?>
    <span class="w-10 h-10 flex items-center justify-center rounded-lg bg-surface-container-highest text-on-surface-variant opacity-40 cursor-not-allowed">
        <span class="material-symbols-outlined">chevron_right</span>
    </span>
    <?php endif; ?>
</div>
