<?php
// Check if the admin is logged in
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ../home/login.php');
    exit;
}
// Include database connection
include('../db_connect.php');
session_start();

header('Content-Type: application/json'); // Set header for JSON response

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the raw POST data
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['id'])) {
        $product_id = intval($data['id']);

        // Start a transaction to ensure both deletions happen together
        mysqli_begin_transaction($conn);

        try {
            // First, delete the product images from the product_images table
            $deleteImagesQuery = "DELETE FROM product_images WHERE product_id = $product_id";
            if (!mysqli_query($conn, $deleteImagesQuery)) {
                throw new Exception(mysqli_error($conn));
            }

            // Next, delete the product itself from the products table
            $deleteProductQuery = "DELETE FROM products WHERE product_id = $product_id";
            if (!mysqli_query($conn, $deleteProductQuery)) {
                throw new Exception(mysqli_error($conn));
            }

            // Decrement the total_products field in the site_statistics table
            $updateStatisticsSQL = "UPDATE site_statistics SET total_products = total_products - 1, last_updated = NOW() WHERE id = 1";
            if (!mysqli_query($conn, $updateStatisticsSQL)) {
                throw new Exception("Error updating site statistics: " . mysqli_error($conn));
            }

            // Commit the transaction if both queries succeed
            mysqli_commit($conn);

            echo json_encode(['status' => 'success']); // Send a success response
        } catch (Exception $e) {
            // Rollback the transaction if any query fails
            mysqli_rollback($conn);

            // Send an error response with the exception message
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }

        exit();
    }
}

echo json_encode(['status' => 'error', 'message' => 'Invalid request']); // Handle invalid requests
?>
