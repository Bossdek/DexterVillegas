<?php
session_start(); // Start the session
require('./database.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user data from the database
$query = "SELECT ID, fullname, username, password, Phonenumber, address FROM user1 WHERE ID = ?";
$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id); // Assuming ID is an integer

if (mysqli_stmt_execute($stmt)) {
    $result = mysqli_stmt_get_result($stmt);
    
    if ($result) { // Check if result is valid
        $user = mysqli_fetch_assoc($result);
    } else {
        echo "Error fetching user data: " . mysqli_error($connection);
        exit(); // Stop script execution on error
    }
} else {
    echo "Error executing query: " . mysqli_error($connection);
    exit(); // Stop script execution on error
}

// Update user data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = $_POST['fullname'];
    $username = $_POST['username'];
    $phonenumber = $_POST['phonenumber'];
    $address = $_POST['address'];

    // Prepare update statement
    $update_query = "UPDATE user1 SET fullname = ?, username = ?, Phonenumber = ?, address = ? WHERE ID = ?";
    $update_stmt = mysqli_prepare($connection, $update_query);
    mysqli_stmt_bind_param($update_stmt, "ssssi", $fullname, $username, $phonenumber, $address, $user_id);

    if (mysqli_stmt_execute($update_stmt)) {
        $success_message = "Profile updated successfully!";
        
        // Refresh user data after update
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            if ($result) {
                $user = mysqli_fetch_assoc($result);
            } else {
                echo "Error fetching updated user data: " . mysqli_error($connection);
                exit(); // Stop script execution on error
            }
        }
    } else {
        $error_message = "Error updating profile: " . mysqli_error($connection);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Profile</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>User Profile</h2>

        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php elseif (!empty($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="fullname">Full Name</label>
                <input type="text" name="fullname" class="form-control" value="<?php echo htmlspecialchars($user['fullname']); ?>" required>
            </div>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            </div>
            <div class="form-group">
                <label for="phonenumber">Phone Number</label>
                <input type="text" name="phonenumber" class="form-control" value="<?php echo htmlspecialchars($user['Phonenumber']); ?>">
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" name="address" class="form-control" value="<?php echo htmlspecialchars($user['address']); ?>">
            </div>
            <button type="submit" class="btn btn-primary">Update Profile</button>
        </form>

        <!-- Return to Home Button -->
        <div class="mt-3">
            <a href="index.php" class="btn btn-secondary">Return to Home</a>
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
