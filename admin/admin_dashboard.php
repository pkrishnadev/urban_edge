<?php

// Check if the admin is logged in
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ../home/login.php');
    exit;
}
// Include your database connection file
include '../db_connect.php';

// Fetch statistics from the 'site_statistics' table
$query = "SELECT total_orders_placed, total_customers, total_products, total_revenue FROM site_statistics WHERE id = 1";
$result = mysqli_query($conn, $query);

if ($result) {
    $stats = mysqli_fetch_assoc($result);
    $totalOrders = $stats['total_orders_placed'];
    $totalCustomers = $stats['total_customers'];
    $totalProducts = $stats['total_products'];
    $totalRevenue = $stats['total_revenue'];
} else {
    // Handle query error
    die("Error fetching site statistics: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <div class="sidebar">
        <h2>Admin Dashboard</h2>
        <ul>
            <li><a href="admin_dashboard.php" class="outline-btn">Home</a></li>
            <li><a href="add_product.php" class="outline-btn">Add Product</a></li>
            <li><a href="order_details.php" class="outline-btn">Order Details</a></li>
            <li><a href="products.php" class="outline-btn">Products</a></li>
            <li><a href="user_details.php" class="outline-btn">User List</a></li>
            <li><button class="outline-btn" id="logoutBtn">Logout</button></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>Welcome, Admin of Urban Edge</h1>
            <p>Manage your online store effectively and keep track of all essential data here.</p>
        </div>

        <!-- Summary Cards Section -->
        <div class="summary-cards">
            <div class="summary-card">
                <h3>Total Orders Placed</h3>
                <p><?php echo $totalOrders; ?></p>
                <span class="description">Total orders placed on the website</span>
            </div>
            <div class="summary-card">
                <h3>Total Customers</h3>
                <p><?php echo $totalCustomers; ?></p>
                <span class="description">Total registered customers</span>
            </div>
            <div class="summary-card">
                <h3>Total Products</h3>
                <p><?php echo $totalProducts; ?></p>
                <span class="description">Products currently listed</span>
            </div>
            <div class="summary-card">
                <h3>Total Revenue</h3>
                <p>₹<?php echo number_format($totalRevenue, 2); ?></p> <!-- Changed to Indian Rupees -->
                <span class="description">Total revenue generated</span>
            </div>
        </div>
    </div>



    <script src="admin.js"></script>
</body>
</html>
