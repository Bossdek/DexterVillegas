<?php
session_start(); // Start a session
require('./database.php');

// Initialize search term
$search_term = '';

// Fetch products from the database with search functionality
$query = "SELECT p.ID, p.name, p.price, p.image, u.fullname 
          FROM product p 
          JOIN user1 u ON p.user_id = u.ID";

if (isset($_POST['search'])) {
    $search_term = mysqli_real_escape_string($connection, $_POST['search_term']);
    $query .= " WHERE p.name LIKE '%$search_term%'"; // Filter products by search term
}

$result = mysqli_query($connection, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home | Farmers' Online Marketplace</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }
        header {
            background-color: #28a745;
            padding: 20px 0;
        }
        header h1 {
            margin: 0;
        }
        nav a {
            color: white;
            margin: 0 15px;
            text-decoration: none;
        }
        nav a:hover {
            text-decoration: underline;
        }
        .hero {
            background-image: url('your-hero-image.jpg'); /* Replace with your image path */
            background-size: cover;
            background-position: center;
            height: 300px;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }
        .hero h2 {
            font-size: 3rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }
        .btn-container {
            margin: 20px 0;
        }
        .product-card {
            transition: transform 0.2s;
            border-radius: 8px;
            overflow: hidden;
        }
        .product-card:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
        }
        .product-card img {
            height: 200px; /* Fixed height for images */
            object-fit: cover; /* Cover to maintain aspect ratio */
        }
        footer {
            background-color: #343a40;
            color: white;
            padding: 15px 0;
            position: relative;
        }
        footer p {
            margin: 0;
        }
        .container {
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <header class="text-white text-center">
        <div class="container">
            <h1>Farmers' Online Marketplace</h1>
            <nav>
                <a href="index.php">Home</a>
                <a href="products.php">Products</a>
                <a href="register.php">Register</a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="profile.php">View Profile</a>
                    <a href="logout.php">Logout</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <div class="hero">
        <h2>Welcome to Farmers' Marketplace!</h2>
    </div>

    <div class="container">
        <div class="text-center btn-container">
            <a href="product_post.php" class="btn btn-success btn-lg">Post a Product</a>
        </div>

        <!-- Search Bar -->
        <form method="POST" class="mb-4">
            <div class="input-group">
                <input type="text" name="search_term" class="form-control" placeholder="Search products..." value="<?php echo htmlspecialchars($search_term); ?>">
                <div class="input-group-append">
                    <button type="submit" name="search" class="btn btn-outline-secondary">Search</button>
                </div>
            </div>
        </form>

        <h3 class="text-center mt-4">Featured Products</h3>
        <div class="row">
            <?php while ($product = mysqli_fetch_assoc($result)): ?>
                <div class="col-md-4 mb-4">
                    <div class="card product-card">
                        <img src="uploads/<?php echo $product['image']; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($product['name']); ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                            <p class="card-text">Price: ₱<?php echo number_format($product['price'], 2); ?></p>
                            <p class="card-text">Sold by: <?php echo htmlspecialchars($product['fullname']); ?></p>
                            <a href="product_detail.php?id=<?php echo $product['ID']; ?>" class="btn btn-primary">View Details</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
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
