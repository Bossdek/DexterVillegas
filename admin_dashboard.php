<?php
session_start();
require('./database.php');

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Initialize messages
$success_message = '';
$error_message = '';

// Check if there's a message in the session to display
if (isset($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
} elseif (isset($_SESSION['error_message'])) {
    $error_message = $_SESSION['error_message'];
    unset($_SESSION['error_message']);
}

// Fetch all products from the database
$product_query = "SELECT * FROM product";
if (isset($_POST['search'])) {
    $search_term = mysqli_real_escape_string($connection, $_POST['search_term']);
    $product_query .= " WHERE name LIKE '%$search_term%'";
}
$product_result = mysqli_query($connection, $product_query);

// Fetch all users from the database for admin management
$user_query = "SELECT * FROM user1";
$user_result = mysqli_query($connection, $user_query);

// Fetch admin details for personal information section
$admin_id = $_SESSION['admin_id'];
$admin_query = "SELECT * FROM admin WHERE ID = ?";
$admin_stmt = mysqli_prepare($connection, $admin_query);
mysqli_stmt_bind_param($admin_stmt, "i", $admin_id);
mysqli_stmt_execute($admin_stmt);
$admin_result = mysqli_stmt_get_result($admin_stmt);
$admin = mysqli_fetch_assoc($admin_result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Welcome, <?php echo htmlspecialchars($admin['username']); ?></h2>
        
        <!-- Logout Button -->
        <a href="logout.php" class="btn btn-danger float-right">Logout</a>

        <!-- Admin's Personal Information Section -->
        <h3>Your Information</h3>
        <form method="POST" action="update_admin_info.php">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($admin['username']); ?>" required>
            </div>
            <div class="form-group">
                <label for="phonenumber">Phone Number</label>
                <input type="text" name="phonenumber" class="form-control" value="<?php echo htmlspecialchars($admin['phonenumber']); ?>">
            </div>
            <button type="submit" class="btn btn-primary">Update Information</button>
        </form>

        <!-- Success/Error Messages -->
        <?php if ($success_message): ?>
            <div class="alert alert-success mt-4"><?php echo $success_message; ?></div>
        <?php endif; ?>
        <?php if ($error_message): ?>
            <div class="alert alert-danger mt-4"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <!-- Product Management Section -->
        <h3 class="mt-4">Manage Products</h3>
        <form method="POST" class="mb-3">
            <div class="input-group">
                <input type="text" name="search_term" class="form-control" placeholder="Search products by name">
                <div class="input-group-append">
                    <button type="submit" name="search" class="btn btn-outline-secondary">Search</button>
                </div>
            </div>
        </form>
        <a href="add_product.php" class="btn btn-success mb-3">Add New Product</a>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Address</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($product = mysqli_fetch_assoc($product_result)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($product['ID']); ?></td>
                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                        <td><?php echo htmlspecialchars($product['price']); ?></td>
                        <td><?php echo htmlspecialchars($product['address']); ?></td>
                        <td><img src="uploads/<?php echo htmlspecialchars($product['image']); ?>" width="50" height="50"></td>
                        <td>
                            <a href="edit_product.php?id=<?php echo $product['ID']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="delete_product.php?id=<?php echo $product['ID']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- User Management Section -->
        <h3 class="mt-4">Manage Users</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Full Name</th>
                    <th>Username</th>
                    <th>Phone Number</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($user = mysqli_fetch_assoc($user_result)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['ID']); ?></td>
                        <td><?php echo htmlspecialchars($user['Fullname']); ?></td>
                        <td><?php echo htmlspecialchars($user['Username']); ?></td>
                        <td><?php echo htmlspecialchars($user['Phonenumber']); ?></td>
                        <td>
                            <a href="delete_user.php?id=<?php echo $user['ID']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <footer class="text-center mt-4">
        <p>&copy; 2024 Farmers' Online Marketplace. All Rights Reserved.</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
