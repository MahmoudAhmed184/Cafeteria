(function () {
    'use strict';

    function getCsrfToken() {
        var meta = document.querySelector('meta[name="csrf-token"]');
        if (meta && meta.getAttribute('content')) {
            return meta.getAttribute('content');
        }
        var input = document.querySelector('input[name="csrf_token"]');
        return input ? input.value : '';
    }

    window.ajax = function (url, options) {
        options = options || {};
        var method = (options.method || 'GET').toUpperCase();
        var headers = options.headers || {};
        if (!headers['Content-Type'] && options.body && typeof options.body === 'object' && !(options.body instanceof FormData)) {
            headers['Content-Type'] = 'application/json';
        }
        var token = getCsrfToken();
        if (token) {
            headers['X-CSRF-TOKEN'] = token;
            headers['Csrf-Token'] = token;
        }
        var fetchOpts = {
            method: method,
            headers: headers,
            credentials: 'same-origin'
        };
        if (options.body !== undefined) {
            fetchOpts.body = typeof options.body === 'object' && !(options.body instanceof FormData)
                ? JSON.stringify(options.body)
                : options.body;
        }
        return fetch(url, fetchOpts).then(function (response) {
            var contentType = response.headers.get('Content-Type') || '';
            var isJson = contentType.indexOf('application/json') !== -1;
            var next = isJson ? response.json() : response.text();
            return next.then(function (data) {
                if (!response.ok) {
                    var err = new Error(response.statusText || 'Request failed');
                    err.status = response.status;
                    err.data = data;
                    throw err;
                }
                return data;
            });
        });
    };

    window.showToast = function (type, message) {
        var container = document.getElementById('toast-container');
        if (!container) return;
        var toast = document.createElement('div');
        toast.className = 'toast toast-' + (type === 'success' ? 'success' : 'error');
        toast.setAttribute('role', 'alert');

        var icon = document.createElement('span');
        icon.className = 'material-symbols-outlined text-xl';
        icon.textContent = type === 'success' ? 'check_circle' : 'error';
        icon.style.color = type === 'success' ? '#2e7d32' : '#d32f2f';

        var text = document.createElement('span');
        text.textContent = message;

        toast.appendChild(icon);
        toast.appendChild(text);
        container.appendChild(toast);
        setTimeout(function () {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 4000);
    };

    function validateForm(form) {
        if (!form) return true;
        var firstInvalid = null;
        var elements = Array.prototype.slice.call(form.elements || []);
        elements.forEach(function (el) {
            if (el.willValidate) {
                el.setCustomValidity('');
            }
        });

        if (form.classList.contains('auth-form')) {
            var email = form.querySelector('#email');
            var password = form.querySelector('#password');
            if (email && email.value.trim() === '') {
                email.setCustomValidity('Email is required.');
            }
            if (password && password.value.trim() === '') {
                password.setCustomValidity('Password is required.');
            }
        }

        if (form.id === 'confirm-order-form') {
            var room = form.querySelector('#room_no');
            if (room && room.value.trim() === '') {
                room.setCustomValidity('Please select a room.');
            }
            var userIdInput = document.getElementById('manual-order-user-id');
            if (userIdInput && userIdInput.form === form && !userIdInput.value) {
                userIdInput.setCustomValidity('Please choose a user for this manual order.');
            }
        }

        if (form.id === 'product-form') {
            var name = form.querySelector('#name');
            var price = form.querySelector('#price');
            var category = form.querySelector('#category_id');
            if (name && name.value.trim() === '') {
                name.setCustomValidity('Product name is required.');
            }
            if (price) {
                var value = parseFloat(price.value);
                if (isNaN(value) || value <= 0) {
                    price.setCustomValidity('Price must be greater than 0.');
                }
            }
            if (category && !category.value) {
                category.setCustomValidity('Please select a category.');
            }
        }

        if (!form.reportValidity()) {
            elements.forEach(function (el) {
                if (el.willValidate && !el.validity.valid && !firstInvalid) {
                    firstInvalid = el;
                }
            });
            if (firstInvalid && typeof firstInvalid.focus === 'function') {
                firstInvalid.focus();
            }
            return false;
        }
        return true;
    }

    function attachValidation(formSelector) {
        var form = document.querySelector(formSelector);
        if (!form) return;
        form.addEventListener('submit', function (e) {
            if (!validateForm(form)) {
                e.preventDefault();
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        attachValidation('.auth-form');
        attachValidation('#confirm-order-form');
        attachValidation('#product-form');

        var existingToasts = document.querySelectorAll('.toast-container .toast');
        existingToasts.forEach(function (toast) {
            setTimeout(function () {
                if (toast.parentNode) {
                    toast.parentNode.removeChild(toast);
                }
            }, 4000);
        });
    });
})();
