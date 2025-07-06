<?php
include '../db_connect.php'; // Include your database connection

if (isset($_POST['email'])) {
    $email = $_POST['email'];
    
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo 'exists'; // Return 'exists' if email is found
    } else {
        echo 'not_exists'; // Return 'not_exists' if email is available
    }
}
?>
