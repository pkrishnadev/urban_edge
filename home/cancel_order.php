<?php
// Database connection
include('../db_connect.php');
session_start();

if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    // Begin transaction
    mysqli_begin_transaction($conn);

    try {
        // Cancel the order by updating the order_status to 'Canceled'
        $cancel_sql = "UPDATE order_details SET order_status = 'Canceled' WHERE order_detail_id = '$order_id'";
        mysqli_query($conn, $cancel_sql);

        // Decrease total orders in site statistics
        $update_stats_sql = "UPDATE site_statistics SET total_orders_placed = total_orders_placed - 1 WHERE id = 1";
        mysqli_query($conn, $update_stats_sql);

        // Commit the transaction
        mysqli_commit($conn);

        // Redirect to the order history page with success message
        header("Location: order.php?message=Order cancelled successfully");
        exit();
    } catch (Exception $e) {
        // Rollback in case of error
        mysqli_rollback($conn);
        echo "Error cancelling the order: " . $e->getMessage();
    }
} else {
    echo "No order ID provided.";
}
