<?php
session_start(); // Start the session
require('./database.php');

// Check if the product ID is provided in the URL
if (!isset($_GET['id'])) {
    header("Location: products.php"); // Redirect to products page if no ID is provided
    exit();
}

$product_id = $_GET['id'];

// Fetch product details from the database
$query = "SELECT p.ID, p.name, p.price, p.image, p.address, u.fullname 
          FROM product p 
          JOIN user1 u ON p.user_id = u.ID 
          WHERE p.ID = ?";
$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt, "i", $product_id);

if (mysqli_stmt_execute($stmt)) {
    $result = mysqli_stmt_get_result($stmt);
    $product = mysqli_fetch_assoc($result);
} else {
    echo "Error fetching product details: " . mysqli_error($connection);
    exit(); // Stop script execution on error
}

if (!$product) {
    echo "Product not found.";
    exit(); // Stop script execution if no product is found
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($product['name']); ?> | Product Details</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2><?php echo htmlspecialchars($product['name']); ?></h2>
        <div class="row">
            <div class="col-md-6">
                <img src="uploads/<?php echo $product['image']; ?>" class="img-fluid" alt="<?php echo htmlspecialchars($product['name']); ?>">
            </div>
            <div class="col-md-6">
                <h4>Price: ₱<?php echo number_format($product['price'], 2); ?></h4>
                <p><strong>Address:</strong> <?php echo htmlspecialchars($product['address']); ?></p>
                <p><strong>Seller:</strong> <?php echo htmlspecialchars($product['fullname']); ?></p>
                <div class="mt-3">
                    <a href="index.php" class="btn btn-secondary">Back to Products</a>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="product_post.php" class="btn btn-primary">Post a Product</a>
                        <!-- Edit Product Button -->
                        <a href="edit_product.php?id=<?php echo $product['ID']; ?>" class="btn btn-warning">Edit Product</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <footer class="text-center mt-4">
        <p>&copy; 2024 Farmers' Online Marketplace. All Rights Reserved.</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
