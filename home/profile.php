<?php
session_start();
include '../db_connect.php'; // Include your database connection file

// Fetch user data from the user table
$user_id = $_SESSION['user_id']; // Get user ID from session
$sql = "SELECT id, email FROM user WHERE id = ?"; // Removed 'role' from query
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if user exists
if ($result->num_rows > 0) {
    // Fetch the user data
    $user = $result->fetch_assoc();
    $email = $user['email'];
} else {
    // Redirect to login if user not found
    header('Location: login.php');
    exit();
}

// Close the database connection
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>
    <link rel="stylesheet" href="home.css"> 
</head>
<body>
    <?php include('header.php'); ?>
    <div class="profile-container">
        <div class="profile-sidebar">
            <h2>Account</h2>
            <ul>
                <li><a href="address.php">Addresses</a></li>
                <li><a href="order.php">Order History</a></li>
            </ul>
        </div>

        <div class="profile-content">
            <h1>Account Details</h1>
            <div class="profile-info">
                <p><strong>User ID:</strong> <?php echo htmlspecialchars($user_id); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
            </div>
            <div class="logout">
                <form action="logout.php" method="POST">
                    <button type="submit">Logout</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
