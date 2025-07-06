<?php
session_start();
include '../db_connect.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Ensure cart is not empty
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<h2>Your cart is empty!</h2>";
    exit;
}

// Ensure all required session variables are set
if (!isset($_SESSION['payment_id']) || !isset($_SESSION['total_amount']) || !isset($_SESSION['order_date']) || !isset($_SESSION['address_id'])) {
    echo "<h2>Error: Missing session data.</h2>";
    exit;
}

// Fetch session data
$user_id = $_SESSION['user_id'];
$payment_id = $_SESSION['payment_id'];
$total_amount = $_SESSION['total_amount'];
$payment_method = $_SESSION['payment_method'];
$order_date = $_SESSION['order_date'];
$address_id = $_SESSION['address_id'];
$order_id = time(); // Generate unique order ID (you can also use auto-increment)

// Insert data into the `payments` table
$query = "INSERT INTO payments (payment_id, order_id, amount, payment_method, payment_date) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param('iiiss', $payment_id, $order_id, $total_amount, $payment_method, $order_date);
if (!$stmt->execute()) {
    echo "Error inserting payment: " . $stmt->error;
    exit;
}

// Insert data into the `order_details` table
$query = "INSERT INTO order_details (order_id, user_id, address_id, total_amount, order_date) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param('iiids', $order_id, $user_id, $address_id, $total_amount, $order_date);
if (!$stmt->execute()) {
    echo "Error inserting order details: " . $stmt->error;
    exit;
}

// Fetch the order_detail_id for use in the order_items table
$order_detail_id = $stmt->insert_id; // Assuming auto-increment ID

// Insert data into the `order_items` table and update product stock
foreach ($_SESSION['cart'] as $key => $item) {
    $order_item_id = $item['order_item_id'];
    $product_id = $item['product_id'];
    $quantity = $item['quantity'];
    $price = $item['total_price'];
    $size = $item['size'];

    // Insert order item into the order_items table
    $query = "INSERT INTO order_items (order_detail_id, product_id, quantity, price, size) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('iiids', $order_detail_id, $product_id, $quantity, $price, $size);
    if (!$stmt->execute()) {
        echo "Error inserting order items: " . $stmt->error;
        exit;
    }

    // Update stock in the products table based on the size
    $stock_column = 'stock_' . strtolower($size); // Example: stock_s, stock_m, etc.
    $update_stock_query = "UPDATE products SET $stock_column = $stock_column - ? WHERE product_id = ?";
    $update_stmt = $conn->prepare($update_stock_query);
    $update_stmt->bind_param('ii', $quantity, $product_id);
    if (!$update_stmt->execute()) {
        echo "Error updating product stock: " . $update_stmt->error;
        exit;
    }
}

// Update site statistics
$query = "UPDATE site_statistics SET total_orders_placed = total_orders_placed + 1, total_revenue = total_revenue + ? WHERE id = 1";
$stmt = $conn->prepare($query);
$stmt->bind_param('d', $total_amount);
if (!$stmt->execute()) {
    echo "Error updating site statistics: " . $stmt->error;
    exit;
}

$stmt->close();
$conn->close();

// Clear the cart and other session data
unset($_SESSION['cart']);
unset($_SESSION['total_amount']);
unset($_SESSION['order_date']);
unset($_SESSION['payment_id']);
unset($_SESSION['address_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link rel="stylesheet" href="home.css">
</head>
<body>
<?php include('header.php'); ?>

<div class="order-confirmation-container">
    <div class="order-confirmation">
        <h1>Thank you for shopping with Urban Edge!</h1>
        <p>We appreciate your trust in our brand and look forward to serving you again. 
           Your style journey starts here—stay trendy with Urban Edge!</p>
        <div class="order-details">
            <p><strong>Order ID:</strong> <?php echo $order_id; ?></p>
        </div>
        <div class="order-actions">
            <a href="http://localhost/urban_edge/home/order.php" class="button view-orders-btn">View Orders</a>
        </div>
    </div>
</div>

<?php include('footer.php'); ?>

<script src="home.js" defer></script>

<style>
    .order-confirmation-container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 80vh;
        background: #f9f9f9;
        padding: 20px;
    }
    .order-confirmation {
        background: #fff;
        border-radius: 5px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        padding: 30px;
        max-width: 500px;
        text-align: center;
    }
    .order-confirmation h1 {
        font-size: 2rem;
        color: #2c3e50;
        margin-bottom: 15px;
        font-weight: 700;
    }
    .order-confirmation p {
        font-size: 1.1rem;
        color: #7f8c8d;
        margin: 10px 0;
        line-height: 1.5;
    }
    .order-details {
        margin: 20px 0;
        font-size: 1.1rem;
        color: #34495e;
    }
    .order-details p {
        margin: 5px 0;
    }
    .order-actions {
        margin-top: 30px;
        text-align: center;
    }
    .order-actions .button {
        display: inline-block;
        padding: 12px 25px;
        font-size: 1rem;
        text-decoration: none;
        border-radius: 6px;
        background-color: #333;
        color: #fff;
        transition: all 0.3s ease;
        font-weight: bold;
    }
    .order-actions .button:hover {
        background-color: black;
        transform: scale(1.05);
    }
    a {
        color: #007bff;
        text-decoration: none;
    }
    a:hover {
        text-decoration: underline;
    }
</style>
</body>



</html>
