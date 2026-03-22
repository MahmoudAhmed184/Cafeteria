(function () {
  "use strict";

  function toFormData(payload) {
    var formData = new FormData();
    Object.keys(payload).forEach(function (key) {
      formData.append(key, payload[key]);
    });

    var csrfMeta = document.querySelector('meta[name="csrf-token"]');
    if (csrfMeta && !formData.has("csrf_token")) {
      formData.append("csrf_token", csrfMeta.content);
    }
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
    clear: function () {
      return window.ajax(withBase("/cart/clear"), {
        method: "POST",
        body: toFormData({}),
      });
    },
    confirm: function (url, roomNo, notes, userId) {
      var payload = { room_no: roomNo, notes: notes || "" };
      if (userId) payload.user_id = userId;

      return window.ajax(withBase(url), {
        method: "POST",
        body: toFormData(payload),
      });
    },
  };

  document.addEventListener("click", function (e) {
    var btn = e.target.closest(".add-to-cart-btn");
    if (!btn) return;

    e.preventDefault();
    var productId = btn.getAttribute("data-product-id");
    if (!productId) return;

    var oldHtml = btn.innerHTML;
    btn.innerHTML =
      '<span class="material-symbols-outlined text-sm">hourglass_top</span>';
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
        btn.innerHTML = oldHtml;
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

  document.addEventListener("click", function (e) {
    var plusBtn = e.target.closest(".cart-qty-plus");
    var minusBtn = e.target.closest(".cart-qty-minus");
    if (!plusBtn && !minusBtn) return;

    e.preventDefault();
    var btn = plusBtn || minusBtn;
    var productId = btn.getAttribute("data-product-id");
    var currentQty = parseInt(btn.getAttribute("data-qty"), 10) || 1;
    var newQty = plusBtn ? currentQty + 1 : currentQty - 1;

    if (newQty <= 0) {
      window.cartApi
        .remove(productId)
        .then(function () {
          window.location.reload();
        })
        .catch(function (err) {
          var msg =
            err && err.data && err.data.message
              ? err.data.message
              : "Failed to remove item.";
          if (typeof window.showToast === "function") {
            window.showToast("error", msg);
          }
        });
    } else {
      window.cartApi
        .update(productId, newQty)
        .then(function () {
          window.location.reload();
        })
        .catch(function (err) {
          var msg =
            err && err.data && err.data.message
              ? err.data.message
              : "Failed to update quantity.";
          if (typeof window.showToast === "function") {
            window.showToast("error", msg);
          }
        });
    }
  });

  document.addEventListener("click", function (e) {
    var btn = e.target.closest(".cart-remove-btn");
    if (!btn) return;

    e.preventDefault();
    var productId = btn.getAttribute("data-product-id");

    window.cartApi
      .remove(productId)
      .then(function () {
        if (typeof window.showToast === "function") {
          window.showToast("success", "Item removed.");
        }
        setTimeout(function () {
          window.location.reload();
        }, 300);
      })
      .catch(function (err) {
        var msg =
          err && err.data && err.data.message
            ? err.data.message
            : "Failed to remove item.";
        if (typeof window.showToast === "function") {
          window.showToast("error", msg);
        }
      });
  });

  document.addEventListener("click", function (e) {
    var btn = e.target.closest("#cart-clear-btn");
    if (!btn) return;

    e.preventDefault();
    window.cartApi
      .clear()
      .then(function () {
        if (typeof window.showToast === "function") {
          window.showToast("success", "Cart cleared.");
        }
        setTimeout(function () {
          window.location.reload();
        }, 300);
      })
      .catch(function () {
        if (typeof window.showToast === "function") {
          window.showToast("error", "Failed to clear cart.");
        }
      });
  });

  document.addEventListener("DOMContentLoaded", function () {
    var form = document.getElementById("confirm-order-form");
    if (!form) return;

    form.addEventListener("submit", function (e) {
      e.preventDefault();

      var roomSelect = form.querySelector("#room_no");
      var notesField = form.querySelector("#notes");
      var roomNo = roomSelect ? roomSelect.value : "";
      var notes = notesField ? notesField.value : "";

      var userIdInput = document.getElementById("manual-order-user-id");
      var userId = userIdInput ? userIdInput.value : null;

      if (!roomNo) {
        if (typeof window.showToast === "function") {
          window.showToast("error", "Please select a room.");
        }
        return;
      }

      var submitBtn = form.querySelector('button[type="submit"]');
      if (submitBtn) {
        submitBtn.textContent = "Placing order...";
        submitBtn.disabled = true;
      }

      var confirmUrl = form.getAttribute("action") || "/orders/confirm";

      window.cartApi
        .confirm(confirmUrl, roomNo, notes, userId)
        .then(function (data) {
          if (data && data.success) {
            if (typeof window.showToast === "function") {
              window.showToast("success", "Order placed successfully!");
            }
            setTimeout(function () {
              window.location.reload();
            }, 800);
          } else {
            var msg =
              data && data.message ? data.message : "Failed to place order.";
            if (typeof window.showToast === "function") {
              window.showToast("error", msg);
            }
            if (submitBtn) {
              submitBtn.textContent = "Confirm order";
              submitBtn.disabled = false;
            }
          }
        })
        .catch(function (err) {
          var msg =
            err && err.data && err.data.message
              ? err.data.message
              : "Failed to place order.";
          if (typeof window.showToast === "function") {
            window.showToast("error", msg);
          }
          if (submitBtn) {
            submitBtn.textContent = "Confirm order";
            submitBtn.disabled = false;
          }
        });
    });
  });
})();
