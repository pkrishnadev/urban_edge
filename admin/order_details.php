<?php
// Check if the admin is logged in
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ../home/login.php');
    exit;
}

// Include database connection
include('../db_connect.php');

// Fetch all orders
$query = "SELECT od.order_detail_id, u.email AS customer_email, od.total_amount, od.order_date, od.order_status 
          FROM order_details od
          JOIN user u ON od.user_id = u.id
          ORDER BY od.order_date DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <!-- Sidebar -->
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

    <!-- Main Content -->
    <div class="main-content">
        <h1>Order Details</h1>

        <!-- Orders Table -->
        <table class="order-table">
            <thead>
                <tr>
                    <th>Sl. No.</th>
                    <th>Customer Email</th>
                    <th>Total Amount</th>
                    <th>Date Ordered</th>
                    <th>Status</th>
                    <th>View Details</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($result) > 0) {
                    $sl_no = 1;
                    while ($row = mysqli_fetch_assoc($result)) {
                        $statusClass = $row['order_status'] == 'canceled' ? 'canceled' : '';
                        echo "<tr class='{$statusClass}'>
                                <td>{$sl_no}</td>
                                <td>{$row['customer_email']}</td>
                                <td>{$row['total_amount']}</td>
                                <td>{$row['order_date']}</td>
                                <td>{$row['order_status']}</td>
                                <td><a href='view_order.php?order_detail_id={$row['order_detail_id']}' class='btn-view'>View</a></td>
                            </tr>";
                        $sl_no++;
                    }
                } else {
                    echo "<tr><td colspan='6'>No orders found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>



    <script src="admin.js"></script>
</body>
</html>
