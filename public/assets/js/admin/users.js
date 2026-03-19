(function () {
    'use strict';

    var form = document.querySelector('form[action$="/admin/users/store"], form[action$="/admin/users/update"]');
    if (!form) {
        return;
    }

    form.addEventListener('submit', function (event) {
        var email = form.querySelector('input[name="email"]');
        if (email && email.value && email.validity.typeMismatch) {
            event.preventDefault();
            email.focus();
        }
    });
})();
