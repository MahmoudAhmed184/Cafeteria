(function () {
    'use strict';

    window.orderApi = {
        items: function (orderId) {
            return window.ajax('/orders/items?order_id=' + encodeURIComponent(orderId), { method: 'GET' });
        },
        cancel: function (orderId) {
            var formData = new FormData();
            formData.append('order_id', orderId);
            return window.ajax('/orders/cancel', { method: 'POST', body: formData });
        }
    };
})();
