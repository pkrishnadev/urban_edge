<?php

// Include database connection
include '../db_connect.php';

// Check if order_detail_id is provided
if (isset($_POST['order_detail_id'])) {
    $order_detail_id = $_POST['order_detail_id'];

    // Fetch order details
    $order_query = "SELECT od.order_id, od.total_amount, od.order_status, od.order_date, 
                           u.email AS customer_email, u.id AS user_id, 
                           a.address_name, a.address_line1, a.address_line2, a.city, a.pincode, a.phone_number, 
                           p.payment_method
                    FROM order_details od
                    JOIN user u ON od.user_id = u.id
                    JOIN addresses a ON od.address_id = a.address_id
                    JOIN payments p ON od.order_id = p.order_id
                    WHERE od.order_detail_id = '$order_detail_id'";
    $order_result = mysqli_query($conn, $order_query);

    if (!$order_result) {
        die('Error fetching order info: ' . mysqli_error($conn));
    }

    $order = mysqli_fetch_assoc($order_result);

    if (!$order) {
        die('No order data found for the provided order_detail_id.');
    }

    // Fetch ordered items
    $items_query = "SELECT oi.quantity, p.price AS unit_price, p.title, oi.size
                    FROM order_items oi
                    JOIN products p ON oi.product_id = p.product_id
                    WHERE oi.order_detail_id = '$order_detail_id'";
    $items_result = mysqli_query($conn, $items_query);

    if (!$items_result) {
        die('Error fetching order items: ' . mysqli_error($conn));
    }
} else {
    die('Order detail ID not provided.');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - Urban Edge</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .invoice-container {
            width: 60%;
            margin: 0 auto;
            padding: 20px;
            border: 2px solid #000;
            background-color: #fff;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .section-divider {
            border-top: 1px solid #000;
            margin: 20px 0;
        }
        .shop-details, .amount-section {
            margin-bottom: 20px;
        }
        .shop-details h1, .amount-section h2 {
            margin-bottom: 10px;
        }
        .details {
            display: flex;
            justify-content: space-between;
        }
        .details div {
            width: 45%;
        }
        .table-container {
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th, table td {
            border: none;
            padding: 10px;
            text-align: left;
        }
        table th {
            border-bottom: 1px solid #000;
        }
        table tr {
            border-bottom: 1px solid #000;
        }
        .total-amount {
            text-align: right;
            font-weight: bold;
        }
        .print-btn {
            display: inline-block;
            padding: 10px 15px;
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
            text-align: center;
        }
        .print-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="invoice-container">
    <!-- Shop Details and Logo -->
    <div class="shop-details-container" style="display: flex; justify-content: space-between; align-items: center;">
        <div class="shop-details" style="flex: 1; margin-right: 20px;">
            <h1>Urban Edge</h1>
            <p>Urban Edge, Ramapuram, Pala, Kerala - 686576</p>
            <p>Email: urabnedgeinfo@gmail.com | Phone: 9497546334</p>
        </div>

        <!-- Display Company Logo -->
        <div class="logo-container" style="text-align: right;">
            <img src="assets/urbanedgelogo.jpg" alt="Company Logo" style="max-width: 150px; height: auto;">
        </div>
    </div>
    <div class="section-divider"></div>

    <!-- Shipped To and Shipped From -->
    <div class="details">
        <div>
            <h2>Shipped To:</h2>
            <p><strong>Name:</strong> <?= $order['address_name']; ?></p>
            <p><strong>Address:</strong> <?= $order['address_line1']; ?>, <?= $order['address_line2']; ?>, <?= $order['city']; ?> - <?= $order['pincode']; ?></p>
            <p><strong>Phone:</strong> <?= $order['phone_number']; ?></p>
        </div>
        <div>
            <h2>Shipped From:</h2>
            <p><strong>Name:</strong> Urban Edge</p>
            <p><strong>Address:</strong> Urban Edge, Ramapuram, Pala, Kerala - 686576</p>
            <p><strong>Phone:</strong> 9497546334</p>
        </div>
    </div>

    <div class="section-divider"></div>

    <!-- Order Summary -->
    <div class="table-container">
        <h2>Order Summary</h2>
        <table>
            <thead>
                <tr>
                    <th>Sl. No.</th>
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
                            <td>{$item['title']}</td>
                            <td>{$item['size']}</td>
                            <td>{$item['quantity']}</td>
                            <td>{$item['unit_price']}</td>
                            <td>{$total_price}</td>
                          </tr>";
                    $sl_no++;
                }
                ?>
            </tbody>
        </table>
    </div>

    <div class="section-divider"></div>

    <!-- Amount Section -->
    <div class="amount-section">
        <h2>Amount Summary</h2>
        <table>  
            <tr>
                <td><strong>Total Amount:</strong></td>
                <td class="total-amount">₹ <strong><?= $grand_total; ?> .00</strong></td>
            </tr>
        </table>
    </div>

    <!-- Print Button -->
    <button class="print-btn" onclick="window.print()">Print Invoice</button>
</div>

</body>
</html>
