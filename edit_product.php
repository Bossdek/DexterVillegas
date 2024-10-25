<?php
session_start(); // Start the session
require('./database.php');

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php"); // Redirect if not logged in
    exit();
}

// Check if the product ID is provided in the URL
if (!isset($_GET['id'])) {
    header("Location: admin_dashboard.php"); // Redirect if no ID is provided
    exit();
}

$product_id = intval($_GET['id']); // Sanitize the product ID

// Fetch the product details from the database
$query = "SELECT * FROM product WHERE ID = ?";
$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt, "i", $product_id);

if (mysqli_stmt_execute($stmt)) {
    $result = mysqli_stmt_get_result($stmt);
    $product = mysqli_fetch_assoc($result);
} else {
    echo "Error fetching product details: " . mysqli_error($connection);
    exit();
}

if (!$product) {
    echo "Product not found.";
    exit();
}

// Update product details
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $address = $_POST['address'];
    $image = $_FILES['image']['name']; // Assuming you're allowing image uploads

    // Prepare the update statement
    $update_query = "UPDATE product SET name = ?, price = ?, address = ?, image = ? WHERE ID = ?";
    $update_stmt = mysqli_prepare($connection, $update_query);
    mysqli_stmt_bind_param($update_stmt, "ssssi", $name, $price, $address, $image, $product_id);

    if (mysqli_stmt_execute($update_stmt)) {
        // Handle image upload if necessary
        if (!empty($image)) {
            move_uploaded_file($_FILES['image']['tmp_name'], "uploads/" . $image);
        }
        $success_message = "Product updated successfully!";
    } else {
        $error_message = "Error updating product: " . mysqli_error($connection);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Edit Product</h2>

        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php elseif (!empty($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Product Name</label>
                <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($product['name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="price">Price</label>
                <input type="text" name="price" class="form-control" value="<?php echo htmlspecialchars($product['price']); ?>" required>
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" name="address" class="form-control" value="<?php echo htmlspecialchars($product['address']); ?>" required>
            </div>
            <div class="form-group">
                <label for="image">Image</label>
                <input type="file" name="image" class="form-control">
                <small class="form-text text-muted">Leave this empty to keep the current image.</small>
            </div>
            <button type="submit" class="btn btn-primary">Update Product</button>
        </form>

        <div class="mt-3">
            <a href="admin_dashboard.php" class="btn btn-secondary">Return to Dashboard</a>
        </div>
    </div>

    <footer class="text-center mt-4">
        <p>&copy; 2024 Farmers' Online Marketplace. All Rights Reserved.</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
