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
            closeAddCategoryModal();
        });
    }
})();
