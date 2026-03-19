(function () {
    'use strict';

    function toFormData(payload) {
        var formData = new FormData();
        Object.keys(payload).forEach(function (key) {
            formData.append(key, payload[key]);
        });
        return formData;
    }

    window.cartApi = {
        add: function (productId, quantity) {
            return window.ajax('/cart/add', { method: 'POST', body: toFormData({ product_id: productId, quantity: quantity || 1 }) });
        },
        update: function (productId, quantity) {
            return window.ajax('/cart/update', { method: 'POST', body: toFormData({ product_id: productId, quantity: quantity }) });
        },
        remove: function (productId) {
            return window.ajax('/cart/remove', { method: 'POST', body: toFormData({ product_id: productId }) });
        }
    };
})();
