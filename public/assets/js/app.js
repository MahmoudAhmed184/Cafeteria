/**
 * Global JS utilities - Cafeteria Management System
 * AJAX helpers, CSRF token injection (NFR-SEC-009)
 */

(function () {
    'use strict';

    /**
     * Get CSRF token from meta tag or hidden input
     * @returns {string}
     */
    function getCsrfToken() {
        var meta = document.querySelector('meta[name="csrf-token"]');
        if (meta && meta.getAttribute('content')) {
            return meta.getAttribute('content');
        }
        var input = document.querySelector('input[name="csrf_token"]');
        return input ? input.value : '';
    }

    /**
     * AJAX request with CSRF and JSON support
     * @param {string} url
     * @param {Object} options - fetch options (method, body, headers, etc.)
     * @returns {Promise<Object>} parsed JSON or throws on non-ok
     */
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

    /**
     * Show toast notification (targets #toast-container)
     * @param {string} type - 'success' or 'error'
     * @param {string} message
     */
    window.showToast = function (type, message) {
        var container = document.getElementById('toast-container');
        if (!container) return;
        var toast = document.createElement('div');
        toast.className = 'toast toast-' + (type === 'success' ? 'success' : 'error');
        toast.setAttribute('role', 'alert');
        toast.textContent = message;
        container.appendChild(toast);
        setTimeout(function () {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 4000);
    };
})();
