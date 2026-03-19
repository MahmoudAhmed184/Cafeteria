(function () {
  "use strict";

  function toFormData(payload) {
    var formData = new FormData();
    Object.keys(payload).forEach(function (key) {
      formData.append(key, payload[key]);
    });
    return formData;
  }

  function withBase(path) {
    var base =
      typeof BASE_URL !== "undefined" && BASE_URL
        ? String(BASE_URL).replace(/\/+$/, "")
        : "";
    return base + path;
  }

  window.cartApi = {
    add: function (productId, quantity) {
      return window.ajax(withBase("/cart/add"), {
        method: "POST",
        body: toFormData({ product_id: productId, quantity: quantity || 1 }),
      });
    },
    update: function (productId, quantity) {
      return window.ajax(withBase("/cart/update"), {
        method: "POST",
        body: toFormData({ product_id: productId, quantity: quantity }),
      });
    },
    remove: function (productId) {
      return window.ajax(withBase("/cart/remove"), {
        method: "POST",
        body: toFormData({ product_id: productId }),
      });
    },
  };

  document.addEventListener("click", function (e) {
    var btn = e.target.closest(".add-to-cart-btn");
    if (!btn) return;

    e.preventDefault();
    var productId = btn.getAttribute("data-product-id");
    if (!productId) return;

    var oldText = btn.textContent;
    btn.textContent = "Adding...";
    btn.disabled = true;

    window.cartApi
      .add(productId, 1)
      .then(function () {
        if (typeof window.showToast === "function") {
          window.showToast("success", "Added to cart.");
        }
        setTimeout(function () {
          window.location.reload();
        }, 500);
      })
      .catch(function (err) {
        btn.textContent = oldText;
        btn.disabled = false;
        var msg =
          err && err.data && err.data.message
            ? err.data.message
            : "Failed to add item.";
        if (typeof window.showToast === "function") {
          window.showToast("error", msg);
        }
      });
  });
})();
