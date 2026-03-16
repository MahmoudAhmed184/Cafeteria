<h1>Products</h1>

<a href="/admin/products/create">Add Product</a>

<table border="1" cellpadding="10">
    <thead>
        <tr>
            <th>Image</th>
            <th>Name</th>
            <th>Price</th>
            <th>Availability</th>
            <th>Actions</th>
        </tr>
    </thead>

    <tbody>

        <?php foreach ($products as $product): ?>

        <tr>

            <td>
                <img src="/<?= $product['image'] ?>" width="50">
            </td>

            <td>
                <?= htmlspecialchars($product['name']) ?>
            </td>

            <td>
                <?= $product['price'] ?> EGP
            </td>

            <td>
                <?= $product['is_available'] ? "Available" : "Unavailable" ?>
            </td>

            <td>

                <a href="/admin/products/edit?id=<?= $product['id'] ?>">
                    Edit
                </a>

                <a href="/admin/products/delete?id=<?= $product['id'] ?>">
                    Delete
                </a>

            </td>

        </tr>

        <?php endforeach; ?>

    </tbody>

</table>