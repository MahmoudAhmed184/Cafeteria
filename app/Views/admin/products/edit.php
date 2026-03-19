<h1>Edit Product</h1>

<form action="/admin/products/update?id=<?= (int)$product['id'] ?>" method="POST" enctype="multipart/form-data">

    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

    <div>
        <label>Product Name</label>
        <input 
            type="text"
            name="name"
            value="<?= htmlspecialchars($product['name']) ?>"
            required
        >
    </div>

    <br>

    <div>
        <label>Price</label>
        <input
            type="number"
            name="price"
            step="0.01"
            value="<?= $product['price'] ?>"
            required
        >
    </div>

    <br>

    <div>
        <label>Category</label>

        <select name="category_id">

            <?php foreach ($categories as $category): ?>

                <option 
                    value="<?= $category['id'] ?>"
                    <?= $category['id'] == $product['category_id'] ? 'selected' : '' ?>
                >
                    <?= htmlspecialchars($category['name']) ?>
                </option>

            <?php endforeach; ?>

        </select>

    </div>

    <br>

    <div>
        <label>Current Image</label>

        <br>

        <img src="/<?= $product['image'] ?>" width="80">
    </div>

    <br>

    <div>
        <label>New Image (optional)</label>
        <input type="file" name="image">
    </div>

    <br>

    <button type="submit">
        Update Product
    </button>

</form>