/**
 * Admin product form - image preview, Add Category modal (FR-ADM-PRD-004, 007)
 */
(function () {
  "use strict";

  var imageInput = document.getElementById("image");
  var imagePreview = document.getElementById("image-preview");
  var addCategoryBtn = document.getElementById("add-category-btn");
  var addCategoryModal = document.getElementById("add-category-modal");
  var addCategoryForm = document.getElementById("add-category-form");
  var newCategoryName = document.getElementById("new_category_name");

  if (imageInput && imagePreview) {
    imageInput.addEventListener("change", function () {
      var file = this.files[0];
      imagePreview.innerHTML = "";
      if (!file) {
        return;
      }
      if (file.type.indexOf("image/") !== 0) {
        if (typeof window.showToast === "function") {
          window.showToast("error", "Please select a valid image file.");
        }
        this.value = "";
        return;
      }
      // Optional client-side size hint (~2MB limit to match backend constraints if configured)
      var maxBytes = 2 * 1024 * 1024;
      if (
        file.size &&
        file.size > maxBytes &&
        typeof window.showToast === "function"
      ) {
        window.showToast(
          "error",
          "Image is too large. Please choose a file under 2 MB.",
        );
        this.value = "";
        return;
      }
      var reader = new FileReader();
      reader.onload = function () {
        var img = document.createElement("img");
        img.src = reader.result;
        img.alt = "Preview";
        img.style.maxWidth = "120px";
        img.style.maxHeight = "120px";
        img.style.marginTop = "0.5rem";
        imagePreview.appendChild(img);
      };
      reader.readAsDataURL(file);
    });
  }

  function openAddCategoryModal() {
    if (addCategoryModal) {
      addCategoryModal.hidden = false;
      addCategoryModal.removeAttribute("hidden");
      addCategoryModal.classList.add("is-open");
      document.body.classList.add("modal-open");
      if (newCategoryName) newCategoryName.focus();
    }
  }

  function closeAddCategoryModal() {
    if (addCategoryModal) {
      addCategoryModal.hidden = true;
      addCategoryModal.setAttribute("hidden", "");
      addCategoryModal.classList.remove("is-open");
      document.body.classList.remove("modal-open");
      if (newCategoryName) newCategoryName.value = "";
    }
  }

  if (addCategoryBtn) {
    addCategoryBtn.addEventListener("click", openAddCategoryModal);
  }
  if (addCategoryModal) {
    addCategoryModal.addEventListener("click", function (e) {
      if (
        e.target === addCategoryModal ||
        e.target.closest("#add-category-cancel")
      ) {
        e.preventDefault();
        closeAddCategoryModal();
      }
    });
  }

  document.addEventListener("keydown", function (e) {
    if (e.key === "Escape" && addCategoryModal && !addCategoryModal.hidden) {
      closeAddCategoryModal();
    }
  });
  if (addCategoryForm) {
    addCategoryForm.addEventListener("submit", function (e) {
      e.preventDefault();
      var name = newCategoryName ? newCategoryName.value.trim() : "";
      if (!name) return;

      var baseUrl =
        typeof BASE_URL !== "undefined" && BASE_URL
          ? String(BASE_URL).replace(/\/+$/, "")
          : "";
      var url = baseUrl + "/admin/categories/store";

      window
        .ajax(url, {
          method: "POST",
          body: { name: name },
        })
        .then(function (data) {
          var categorySelect = document.getElementById("category_id");
          if (categorySelect && data && data.id) {
            var optionValue = String(data.id);
            var existing = categorySelect.querySelector(
              'option[value="' + optionValue.replace(/"/g, '\\"') + '"]',
            );
            if (existing) {
              existing.selected = true;
            } else {
              var opt = document.createElement("option");
              opt.value = optionValue;
              opt.textContent = data.name || name;
              opt.selected = true;
              categorySelect.appendChild(opt);
            }
            categorySelect.value = optionValue;
            categorySelect.dispatchEvent(
              new Event("change", { bubbles: true }),
            );
          }
          closeAddCategoryModal();
          if (typeof window.showToast === "function") {
            window.showToast("success", 'Category "' + name + '" added.');
          }
        })
        .catch(function (err) {
          closeAddCategoryModal();
          var msg =
            err && err.data && err.data.message
              ? err.data.message
              : "Failed to add category.";
          if (typeof window.showToast === "function") {
            window.showToast("error", msg);
          }
        });
    });
  }
})();
