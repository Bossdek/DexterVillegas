<?php
session_start(); // Start the session

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout | Farmers' Online Marketplace</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }
        .container {
            margin-top: 100px;
            text-align: center;
        }
        footer {
            background-color: #343a40;
            color: white;
            padding: 15px 0;
            position: relative;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>You have been logged out successfully!</h2>
        <p>Thank you for visiting Farmers' Online Marketplace.</p>
        <a href="login.php" class="btn btn-success">Login Again</a>
    </div>

    <footer class="text-center mt-4">
        <p>&copy; 2024 Farmers' Online Marketplace. All Rights Reserved.</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
