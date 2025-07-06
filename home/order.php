<?php
include('../db_connect.php');
session_start();

// Fetch order details for the logged-in user
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM order_details WHERE user_id = '$user_id' ORDER BY order_date DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="home.css">
    <title>Order History</title>
</head>
<body>
<?php include('header.php'); ?>

    <div class="order-container">
        <h1 class="order_head">ORDER HISTORY</h1>
        <hr class="order_line">

        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="order-card">
                    <!-- Order Status -->
                    <div class="order-status <?php echo ($row['order_status'] === 'Canceled') ? 'status-canceled' : 'status-placed'; ?>">
                        <?php echo "Order " . ucfirst($row['order_status']); ?>
                    </div>

                    <!-- Product Images -->
                    <div class="order-images">
                        <?php
                        // Fetch one image for each unique product in the order
                        $order_detail_id = $row['order_detail_id'];
                        $product_sql = "
                            SELECT pi.image_path, oi.product_id, p.title
                            FROM order_items oi
                            JOIN product_images pi ON oi.product_id = pi.product_id
                            JOIN products p ON oi.product_id = p.product_id
                            WHERE oi.order_detail_id = '$order_detail_id'
                            GROUP BY oi.product_id
                            LIMIT 5
                        ";
                        $product_result = mysqli_query($conn, $product_sql);

                        if (mysqli_num_rows($product_result) > 0) {
                            while ($product = mysqli_fetch_assoc($product_result)) { ?>
                                <div class="product-review">
                                    <img src="<?php echo $product['image_path']; ?>" alt="Product Image" class="order-product-image">
                                    <button onclick="window.location.href='add_review.php?product_id=<?php echo $product['product_id']; ?>'">
                                        Add Review
                                    </button>
                                </div>
                            <?php }
                        } else { ?>
                            <p>No product images available.</p>
                        <?php } ?>
                    </div>

                    <!-- Order Details and Actions -->
                    <div class="order-details">
                        <div class="order-info">
                            <p><?php echo date('d M Y', strtotime($row['order_date'])); ?></p>
                            <p>Order ID: <?php echo $row['order_id']; ?></p> <!-- Displaying order_id -->
                        </div>
                        <div class="order-actions">
                            <?php if ($row['order_status'] !== 'Canceled'): ?>
                                <button class="cancel-order" onclick="cancelOrder(<?php echo $row['order_detail_id']; ?>)">Cancel</button>
                            <?php endif; ?>
                            <button class="view-order" onclick="window.location.href='user_view_order.php?order_detail_id=<?php echo $row['order_detail_id']; ?>'">View Order</button>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="no-orders-message">No orders have been placed yet.</p>
        <?php endif; ?>
    </div>

    <script>
        function cancelOrder(order_detail_id) {
            if (confirm('Are you sure you want to cancel this order?')) {
                // Add AJAX call to handle order cancellation
                window.location.href = 'cancel_order.php?order_id=' + order_detail_id;
            }
        }
    </script>

<?php include('footer.php'); ?>

</body>
</html>
