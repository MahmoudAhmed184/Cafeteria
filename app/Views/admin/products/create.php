<h1>Add Product</h1>

<form action="/admin/products/store" method="POST" enctype="multipart/form-data">

    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

    <div>
        <label>Product Name</label>
        <input type="text" name="name" required>
    </div>

    <br>

    <div>
        <label>Price</label>
        <input type="number" name="price" step="0.01" required>
    </div>

    <br>

    <div>
        <label>Category</label>
        <select name="category_id" required>

            <?php foreach ($categories as $category): ?>

                <option value="<?= $category['id'] ?>">
                    <?= htmlspecialchars($category['name']) ?>
                </option>

            <?php endforeach; ?>

        </select>
    </div>

    <br>

    <div>
        <label>Product Image</label>
        <input type="file" name="image" required>
    </div>

    <br>

    <button type="submit">
        Create Product
    </button>

</form>