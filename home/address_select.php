<?php
session_start();
include '../db_connect.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch the logged-in user ID
$userId = $_SESSION['user_id'];

// Fetch saved addresses for the logged-in user
$query = "SELECT * FROM addresses WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$addresses = $result->fetch_all(MYSQLI_ASSOC);

// Handle form submission: Save the selected address ID in the session
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['address_id'])) {
    // Save the selected address ID in the session
    $_SESSION['address_id'] = $_POST['address_id'];

    // Redirect to the payment page
    header("Location: payment.php");
    exit();
}

// If no addresses are found, redirect to the address page
if (empty($addresses)) {
    header("Location: add_address.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Shipping Address</title>
    <link rel="stylesheet" href="home.css">
</head>
<body>
<?php include('header.php'); ?>

<div class="order-address-container">
    <h2 class="order-address-title">Select a Shipping Address</h2>

    <form action="" method="POST">
        <?php if (empty($addresses)): ?>
            <p class="no-address-message">No saved addresses. Please <a href="add_address.php">add an address</a>.</p>
        <?php else: ?>
            <div class="order-address-list">
                <?php foreach ($addresses as $address): ?>
                    <div class="order-address-card">
                        <label class="order-address-label">
                            <input type="radio" name="address_id" 
                                   value="<?php echo $address['address_id']; ?>" 
                                   required>
                            <strong><?php echo $address['address_name']; ?></strong><br>
                            <?php echo $address['address_line1']; ?><br>
                            <?php echo $address['address_line2']; ?><br>
                            <?php echo $address['city']; ?>, <?php echo $address['street']; ?><br>
                            <?php echo $address['pincode']; ?><br>
                            <?php echo $address['phone_number']; ?><br>
                            Address type: <?php echo ucfirst($address['address_type']); ?>
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="order-proceed-section">
            <a href="add_address.php" class="add-address-btn">Add Address</a> 
            <button type="submit" class="order-proceed-btn">Proceed to Payment</button>
        </div>
    </form>
</div>

<script src="home.js"></script>
<?php include('footer.php'); ?>
</body>
</html>

<?php
// Free results and close statements
$result->free();
$stmt->close();
$conn->close();  
?>
