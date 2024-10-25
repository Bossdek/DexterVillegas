<?php
$servername = "localhost";  // Usually localhost, or the IP/hostname of your DB server
$username = "root";          // Your MySQL username
$password = "";              // Your MySQL password
$database = "farmersdb1";    // Your database name

// Create a connection
$connection = mysqli_connect($servername, $username, $password, $database);

// Check the connection
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
