<?php
session_start(); // Start the session
require('./database.php');

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php"); // Redirect if not logged in
    exit();
}

// Fetch the admin's current information
$admin_id = $_SESSION['admin_id'];
$admin_query = "SELECT * FROM admin WHERE ID = ?";
$admin_stmt = mysqli_prepare($connection, $admin_query);
mysqli_stmt_bind_param($admin_stmt, "i", $admin_id);
mysqli_stmt_execute($admin_stmt);
$admin_result = mysqli_stmt_get_result($admin_stmt);
$admin = mysqli_fetch_assoc($admin_result);

// Handle form submission to update admin information
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $phonenumber = $_POST['phonenumber'];

    // Prepare the update statement
    $update_query = "UPDATE admin SET username = ?, phonenumber = ? WHERE ID = ?";
    $update_stmt = mysqli_prepare($connection, $update_query);
    mysqli_stmt_bind_param($update_stmt, "ssi", $username, $phonenumber, $admin_id);

    if (mysqli_stmt_execute($update_stmt)) {
        $success_message = "Admin information updated successfully!";
    } else {
        $error_message = "Error updating admin information: " . mysqli_error($connection);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Admin Information</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Update Admin Information</h2>

        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php elseif (!empty($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

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

        <div class="mt-3">
            <a href="admin_dashboard.php" class="btn btn-secondary">Return to Dashboard</a>
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
