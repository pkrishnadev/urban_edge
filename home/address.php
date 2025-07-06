<?php
session_start();
include '../db_connect.php'; // Include your DB connection

$userId = $_SESSION['user_id']; // Assuming user_id is stored in session after login

// Fetch saved addresses for the logged-in user
$query = "SELECT * FROM addresses WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$addresses = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shipping Addresses</title>
    <link rel="stylesheet" href="home.css"> 
<body>
<?php include('header.php'); ?>
<div class="address-container">
    <h2>Your Shipping Addresses</h2>

    <div class="top-buttons">
        <a href="add_address.php" class="add-address-button">Add a new address</a>
    </div>

    <?php if (empty($addresses)): ?>
        <p>No saved addresses </p>
    <?php else: ?>
        <div class="address-list">
            <?php foreach ($addresses as $address): ?>
                <div class="address-card">
                    <p><strong><?php echo $address['address_name']; ?></strong></p>
                    <p><?php echo $address['address_line1']; ?></p>
                    <p><?php echo $address['address_line2']; ?></p>
                    <p><?php echo $address['city']; ?>, <?php echo $address['street']; ?></p>
                    <p><?php echo $address['pincode']; ?></p>
                    <p><?php echo $address['phone_number']; ?></p>
                    <p>Address type: <?php echo ucfirst($address['address_type']); ?></p> <!-- Updated address type -->
                    <div class="address-actions">
                        <form action="remove_address.php" method="POST" class="remove-form">
                            <input type="hidden" name="address_id" value="<?php echo $address['address_id']; ?>">
                            <button type="submit" class="remove-button">Remove</button>
                        </form>
                        <a href="edit_address.php?address_id=<?php echo $address['address_id']; ?>" class="edit-button">Edit</a>

                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script src="home.js"></script>
<?php include('footer.php'); ?>
</body>
</html>
