<?php 
// Check if the admin is logged in
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ../home/login.php');
    exit;
}

// Connect to the database
include '../db_connect.php';

if (isset($_GET['order_detail_id'])) {
    $order_detail_id = $_GET['order_detail_id'];

    // Fetch order details
    $order_query = "SELECT od.order_detail_id, od.order_id, od.user_id AS user_id, od.address_id, od.total_amount, od.order_status, od.order_date, 
    u.email AS customer_email, a.address_name, a.address_line1, a.address_line2, a.city, a.pincode, a.phone_number, 
    a.address_type, p.payment_method
    FROM order_details od
    JOIN user u ON od.user_id = u.id
    JOIN addresses a ON od.address_id = a.address_id
    JOIN payments p ON od.order_id = p.order_id
    WHERE od.order_detail_id = '$order_detail_id'";
    $order_result = mysqli_query($conn, $order_query);
    $order = mysqli_fetch_assoc($order_result);

    // Fetch ordered items (including only one product image per product)
    $items_query = "SELECT oi.quantity, p.price AS unit_price, oi.size, p.title, pi.image_path
    FROM order_items oi
    JOIN products p ON oi.product_id = p.product_id
    LEFT JOIN (
        SELECT product_id, MIN(image_path) AS image_path
        FROM product_images
        GROUP BY product_id
    ) pi ON oi.product_id = pi.product_id
    WHERE oi.order_detail_id = '$order_detail_id'";
    $items_result = mysqli_query($conn, $items_query);

} else {
    echo "No order selected.";
    exit();
}

// Update order status if the form is submitted
if (isset($_POST['update_status'])) {
    $new_status = $_POST['order_status'];
    $update_query = "UPDATE order_details SET order_status = '$new_status' WHERE order_detail_id = '$order_detail_id'";
    mysqli_query($conn, $update_query);
    header("Location: order_details.php");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Order</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <!-- slidebar -->
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
    <div class="order-container">
        <h1>Order Details</h1>

        <!-- Customer Information -->
        <div class="customer-info">
            <h2>Customer Information</h2>
            <p><strong>Name:</strong> <?= $order['address_name']; ?></p>
            <p><strong>Address:</strong></p>
            <p><?= $order['address_line1']; ?></p>
            <p><?= $order['address_line2']; ?></p>
            <p><?= $order['city']; ?></p>
            <p><?= $order['pincode']; ?></p>
            <p><strong>Address Type:</strong> <?= $order['address_type']; ?></p>
            <p><strong>Phone:</strong> <?= $order['phone_number']; ?></p>
        </div>

        <!-- Order Information -->
        <div class="order-info">
            <h2>Order Information</h2>
            <p><strong>Order ID:</strong> <?= $order['order_id']; ?></p>
            <p><strong>Customer ID:</strong> <?= $order['user_id']; ?></p>
            <p><strong>Order Date:</strong> <?= $order['order_date']; ?></p>
            <p><strong>Payment Method:</strong> <?= $order['payment_method']; ?></p>

            <!-- Order Status -->
            <form method="post" action="">
                <label for="order_status"><strong>Order Status:</strong></label>
                <select name="order_status" id="order_status">
                    <option value="placed" <?= $order['order_status'] == 'placed' ? 'selected' : ''; ?>>Placed</option>
                    <option value="shipped" <?= $order['order_status'] == 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                    <option value="delivered" <?= $order['order_status'] == 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                    <option value="canceled" <?= $order['order_status'] == 'canceled' ? 'selected' : ''; ?>>Canceled</option>
                </select>
                <button type="submit" name="update_status">Update Status</button>
            </form>
        </div>

        <!-- Order Items Table -->
        <h2>Ordered Products</h2>
        <table class="order-table">
            <thead>
                <tr>
                    <th>Sl. No.</th>
                    <th>Product Image</th>
                    <th>Product Title</th>
                    <th>Size</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Total Price</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sl_no = 1;
                $grand_total = 0;

                while ($item = mysqli_fetch_assoc($items_result)) {
                    // Calculate Total Price for each item
                    $total_price = $item['quantity'] * $item['unit_price'];
                    $grand_total += $total_price;
                    echo "<tr>
                            <td>{$sl_no}</td>
                            <td><img src='{$item['image_path']}' alt='Product Image' style='width: 50px; height: 65px;'></td>
                            <td>{$item['title']}</td>
                            <td>{$item['size']}</td>
                            <td>{$item['quantity']}</td>
                            <td>{$item['unit_price']}</td>
                            <td>{$total_price}</td>
                        </tr>";
                    $sl_no++;
                }
                ?>
                <tr>
                    <td colspan="6" align="right"><strong>Total Amount:</strong></td>
                    <td><strong><?= $order['total_amount']; ?></strong></td>
                </tr>
            </tbody>
        </table>

        <!-- Print Invoice Button -->
        <form action="print_invoice.php" method="post" target="_blank">
          <input type="hidden" name="order_detail_id" value="<?= $order['order_detail_id']; ?>">
          <button type="submit" name="print_invoice" class="print-btn">Print Invoice</button>
        </form>

    </div>

    <style>
        /* Styles for the order page */
        .order-container {
            width: 80%;
            margin: 20px;
            margin-left: 280px;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h1, h2 {
            color: #333;
        }
        .customer-info, .order-info {
            display: inline-block;
            width: 45%;
            vertical-align: top;
            margin-bottom: 20px;
        }
        .order-info {
            text-align: right;
        }
        table.order-table {
            width: 90%;
            border-collapse: collapse;
        }
        table.order-table th, table.order-table td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        table.order-table th {
            background-color: #1abc9c;
        }
        .order-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        button {
            padding: 10px 15px;
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        button:hover {
            background-color: #0056b3;
        }
        select {
            padding: 5px;
            margin-left: 10px;
        }
        img {
            display: block;
            margin: 0 auto;
        }
    </style>

    <script src="admin.js"></script>
</body>
</html>
