/**
 * Product search / filter - client-side filtering by product name (FR-CART-002)
 * Targets #product-search and #products-grid with .product-card[data-product-name]
 */
(function () {
    'use strict';

    var searchInput = document.getElementById('product-search');
    var productsGrid = document.getElementById('products-grid');
    if (!searchInput || !productsGrid) return;

    var cards = productsGrid.querySelectorAll('.product-card');
    var noMatchId = 'products-no-match';
    var noMatchEl = document.getElementById(noMatchId);

    function ensureNoMatchMessage() {
        if (!document.getElementById(noMatchId)) {
            var p = document.createElement('p');
            p.id = noMatchId;
            p.className = 'products-no-match';
            p.setAttribute('aria-live', 'polite');
            p.textContent = 'No products match your search.';
            productsGrid.appendChild(p);
            return productsGrid.querySelector('#' + noMatchId);
        }
        return document.getElementById(noMatchId);
    }

    function filterProducts() {
        var query = (searchInput.value || '').trim().toLowerCase();
        var visibleCount = 0;

        cards.forEach(function (card) {
            var name = (card.getAttribute('data-product-name') || '').toLowerCase();
            var match = !query || name.indexOf(query) !== -1;
            card.style.display = match ? '' : 'none';
            if (match) visibleCount++;
        });

        var noMatch = ensureNoMatchMessage();
        noMatch.style.display = visibleCount === 0 ? 'block' : 'none';
    }

    searchInput.addEventListener('input', filterProducts);
    searchInput.addEventListener('search', filterProducts);
})();
