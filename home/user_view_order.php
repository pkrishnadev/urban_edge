<?php
// Check if the user is logged in
session_start();

if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header('Location: ../home/login.php');
    exit;
}

// Connect to the database
include '../db_connect.php';

if (isset($_GET['order_detail_id'])) {
    $order_detail_id = $_GET['order_detail_id'];

    // Fetch order details
    $order_query = "SELECT od.order_detail_id, od.order_id, od.user_id, od.address_id, od.total_amount, od.order_status, od.order_date, 
        u.email AS customer_email, a.address_name, a.address_line1, a.address_line2, a.city, a.pincode, a.phone_number, 
        a.address_type, p.payment_method
        FROM order_details od
        JOIN user u ON od.user_id = u.id
        JOIN addresses a ON od.address_id = a.address_id
        JOIN payments p ON od.order_id = p.order_id
        WHERE od.order_detail_id = '$order_detail_id'";
    $order_result = mysqli_query($conn, $order_query);
    $order = mysqli_fetch_assoc($order_result);

    // Fetch ordered items (including only one product image per product) with unit price from products table
    $items_query = "SELECT oi.quantity, oi.size, p.title, p.price AS unit_price, pi.image_path
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User View Order</title>
    <link rel="stylesheet" href="../home/home.css">
    <?php include('header.php'); ?>
</head>
<body>
   
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
            <p><strong>Order Status:</strong> <?= ucfirst($order['order_status']); ?></p>
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
                    <td><strong><?= $grand_total; ?></strong></td>
                </tr>
            </tbody>
        </table>

        <!-- Print Invoice Button -->
        <form action="../admin/print_invoice.php" method="post" target="_blank">
          <input type="hidden" name="order_detail_id" value="<?= $order['order_detail_id']; ?>">
          <button type="submit" name="print_invoice" class="print-btn">Print Invoice</button>
        </form>
    </div>

    <style>
        /* Styles for the order page */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow-x: hidden; 
            margin-top: 5%;
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
            width: 100%;
            border-collapse: collapse;
        }
        table.order-table th, table.order-table td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        button.print-btn {
            margin: 30px 0px;
            padding: 10px 15px;
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        button.print-btn:hover {
            background-color: #0056b3;
        }
    </style>
</body>
</html>
