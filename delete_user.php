<?php
session_start();
require('./database.php');

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Check if the user ID is provided in the URL
if (!isset($_GET['id'])) {
    header("Location: admin_dashboard.php"); // Redirect if no ID is provided
    exit();
}

$user_id = $_GET['id'];

// Prepare the delete statement
$delete_query = "DELETE FROM user1 WHERE ID = ?";
$delete_stmt = mysqli_prepare($connection, $delete_query);
mysqli_stmt_bind_param($delete_stmt, "i", $user_id);

if (mysqli_stmt_execute($delete_stmt)) {
    $_SESSION['success_message'] = "User deleted successfully!";
} else {
    $_SESSION['error_message'] = "Error deleting user: " . mysqli_error($connection);
}

// Redirect back to the admin dashboard
header("Location: admin_dashboard.php");
exit();
