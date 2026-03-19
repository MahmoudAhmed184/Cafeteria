(function () {
    'use strict';

    var form = document.querySelector('form[action$="/admin/checks"]');
    if (!form) {
        return;
    }

    var from = form.querySelector('input[name="date_from"]');
    var to = form.querySelector('input[name="date_to"]');

    form.addEventListener('submit', function (event) {
        if (from && to && from.value && to.value && from.value > to.value) {
            event.preventDefault();
            to.focus();
        }
    });
})();
