<?php
session_start();
include '../db_connect.php'; // Include your DB connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $addressId = $_POST['address_id'];
    $userId = $_SESSION['user_id'];

    // Ensure the address belongs to the logged-in user
    $deleteQuery = "DELETE FROM addresses WHERE address_id = ? AND user_id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("ii", $addressId, $userId);

    if ($stmt->execute()) {
        // Redirect back to the address page after deletion
        header("Location: address.php");
        exit();
    } else {
        echo "Error deleting address.";
    }
}
?>
