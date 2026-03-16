/**
 * Admin product form - image preview, Add Category modal (FR-ADM-PRD-004, 007)
 */
(function () {
    'use strict';

    var imageInput = document.getElementById('image');
    var imagePreview = document.getElementById('image-preview');
    var addCategoryBtn = document.getElementById('add-category-btn');
    var addCategoryModal = document.getElementById('add-category-modal');
    var addCategoryForm = document.getElementById('add-category-form');
    var addCategoryCancel = document.getElementById('add-category-cancel');
    var newCategoryName = document.getElementById('new_category_name');

    if (imageInput && imagePreview) {
        imageInput.addEventListener('change', function () {
            var file = this.files[0];
            imagePreview.innerHTML = '';
            if (file && file.type.indexOf('image/') === 0) {
                var reader = new FileReader();
                reader.onload = function () {
                    var img = document.createElement('img');
                    img.src = reader.result;
                    img.alt = 'Preview';
                    img.style.maxWidth = '120px';
                    img.style.maxHeight = '120px';
                    img.style.marginTop = '0.5rem';
                    imagePreview.appendChild(img);
                };
                reader.readAsDataURL(file);
            }
        });
    }

    function openAddCategoryModal() {
        if (addCategoryModal) {
            addCategoryModal.hidden = false;
            addCategoryModal.removeAttribute('hidden');
            if (newCategoryName) newCategoryName.focus();
        }
    }

    function closeAddCategoryModal() {
        if (addCategoryModal) {
            addCategoryModal.hidden = true;
            addCategoryModal.setAttribute('hidden', '');
            if (newCategoryName) newCategoryName.value = '';
        }
    }

    if (addCategoryBtn) {
        addCategoryBtn.addEventListener('click', openAddCategoryModal);
    }
    if (addCategoryCancel) {
        addCategoryCancel.addEventListener('click', closeAddCategoryModal);
    }
    if (addCategoryModal) {
        addCategoryModal.addEventListener('click', function (e) {
            if (e.target === addCategoryModal) closeAddCategoryModal();
        });
    }
    if (addCategoryForm) {
        addCategoryForm.addEventListener('submit', function (e) {
            e.preventDefault();
            var name = newCategoryName ? newCategoryName.value.trim() : '';
            if (!name) return;

            var baseUrl = (document.querySelector('meta[name="csrf-token"]') || {})
                .closest ? '' : '';
            var url = (typeof BASE_URL !== 'undefined' ? BASE_URL : '') + '/admin/categories/store';

            window.ajax(url, {
                method: 'POST',
                body: { name: name }
            }).then(function (data) {
                var categorySelect = document.getElementById('category_id');
                if (categorySelect && data && data.id) {
                    var opt = document.createElement('option');
                    opt.value = data.id;
                    opt.textContent = data.name || name;
                    opt.selected = true;
                    categorySelect.appendChild(opt);
                }
                closeAddCategoryModal();
                if (typeof window.showToast === 'function') {
                    window.showToast('success', 'Category "' + name + '" added.');
                }
            }).catch(function (err) {
                closeAddCategoryModal();
                var msg = (err && err.data && err.data.message) ? err.data.message : 'Failed to add category.';
                if (typeof window.showToast === 'function') {
                    window.showToast('error', msg);
                }
            });
        });
    }
})();
