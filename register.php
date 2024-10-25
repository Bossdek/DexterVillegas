<?php
require('./database.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $role = $_POST['role'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
    $phone_number = $_POST['phone_number'];

    if ($role == 'admin') {
        // Admin registration query
        $query = "INSERT INTO admin (username, password, phonenumber) VALUES (?, ?, ?)";
    } else {
        // User registration query
        $fullname = $_POST['fullname'];
        $address = $_POST['address'];
        $query = "INSERT INTO user1 (Fullname, Username, Password, Phonenumber, Address) VALUES (?, ?, ?, ?, ?)";
    }

    $stmt = mysqli_prepare($connection, $query);

    if ($stmt === false) {
        die("mysqli_prepare() failed: " . mysqli_error($connection));
    }

    if ($role == 'admin') {
        mysqli_stmt_bind_param($stmt, "sss", $username, $password, $phone_number);
    } else {
        mysqli_stmt_bind_param($stmt, "sssss", $fullname, $username, $password, $phone_number, $address);
    }

    if (mysqli_stmt_execute($stmt)) {
        $success_message = ucfirst($role) . " registration successful! You can now log in.";
    } else {
        $error_message = ucfirst($role) . " registration failed: " . mysqli_stmt_error($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Styling for registration page */
        .register-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>Register</h2>
        
        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php elseif (!empty($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="role">Register as:</label>
                <select name="role" class="form-control" required>
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div class="form-group" id="fullname-group">
                <input type="text" name="fullname" class="form-control" placeholder="Full Name (for Users only)">
            </div>
            <div class="form-group">
                <input type="text" name="username" class="form-control" placeholder="Username" required>
            </div>
            <div class="form-group">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>
            <div class="form-group">
                <input type="text" name="phone_number" class="form-control" placeholder="Phone Number" required>
            </div>
            <div class="form-group" id="address-group">
                <input type="text" name="address" class="form-control" placeholder="Address (for Users only)">
            </div>
            <button type="submit" class="btn btn-success btn-block">Register</button>
        </form>
        <div class="text-center mt-3">
            <p>Already have an account? <a href="login.php">Log in</a></p>
        </div>
    </div>

    <script>
        const roleSelect = document.querySelector('select[name="role"]');
        const fullnameGroup = document.getElementById('fullname-group');
        const addressGroup = document.getElementById('address-group');

        roleSelect.addEventListener('change', function() {
            if (this.value === 'admin') {
                fullnameGroup.style.display = 'none';
                addressGroup.style.display = 'none';
            } else {
                fullnameGroup.style.display = 'block';
                addressGroup.style.display = 'block';
            }
        });

        // Hide fields if admin is selected by default
        roleSelect.dispatchEvent(new Event('change'));
    </script>
</body>
</html>
