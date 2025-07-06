<?php

include '../db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['status'];

    $update_query = "UPDATE orders SET status = '$new_status' WHERE order_id = $order_id";
    
    if ($conn->query($update_query) === TRUE) {
        echo "Order status updated successfully";
    } else {
        echo "Error updating order: " . $conn->error;
    }

    // Redirect to product list page after updating the order status
    header('Location: order_details.php');
}
?>
