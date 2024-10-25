<?php
session_start();
require('./database.php');

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Check if the product ID is provided
if (isset($_GET['id'])) {
    $product_id = intval($_GET['id']); // Get the product ID from the query string

    // Prepare the delete query
    $delete_query = "DELETE FROM product WHERE ID = ?";
    $delete_stmt = mysqli_prepare($connection, $delete_query);

    if ($delete_stmt) {
        mysqli_stmt_bind_param($delete_stmt, "i", $product_id);
        $result = mysqli_stmt_execute($delete_stmt);

        // Check if the deletion was successful
        if ($result) {
            $_SESSION['success_message'] = "Product deleted successfully!";
        } else {
            $_SESSION['error_message'] = "Error deleting product. Please try again.";
        }

        mysqli_stmt_close($delete_stmt);
    } else {
        $_SESSION['error_message'] = "Database error. Please try again.";
    }
} else {
    $_SESSION['error_message'] = "No product ID provided.";
}

// Redirect back to the admin dashboard
header("Location: admin_dashboard.php");
exit();
?>
