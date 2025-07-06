<?php
session_start();
include '../db_connect.php'; // Assuming your database connection is in this file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit();
}

$user_id = $_SESSION['user_id']; // Get the logged-in user's ID

// If form is submitted, process the input
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $address_name = $_POST['address_name'];
    $address_line1 = $_POST['address_line1'];
    $address_line2 = $_POST['address_line2'];
    $city = $_POST['city'];
    $street = $_POST['district'];
    $pincode = $_POST['pincode'];
    $phone_number = $_POST['phone_number'];
    $address_type = $_POST['address_type'];

    // Server-side validation
    if (!preg_match('/^\d{6}$/', $pincode)) {
        echo "<script>alert('Invalid Pincode. Must be 6 digits.');</script>";
    } elseif (!preg_match('/^\d{10}$/', $phone_number)) {
        echo "<script>alert('Invalid Phone Number. Must be 10 digits.');</script>";
    } else {
        // Insert into the addresses table
        $query = "INSERT INTO addresses (user_id, address_name, address_line1, address_line2, city, street, pincode, phone_number, address_type) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("issssssis", $user_id, $address_name, $address_line1, $address_line2, $city, $street, $pincode, $phone_number, $address_type);

        if ($stmt->execute()) {
            echo "<script>alert('Address added successfully!'); window.location.href = 'address.php';</script>";
        } else {
            echo "<script>alert('Error adding address');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Address</title>
    <link rel="stylesheet" href="home.css"> 
</head>
<body>
<?php include('header.php'); ?>
<div class="main-content">
    <div class="address-form-container">
        <h2>Add Shipping Address</h2>
        <form action="add_address.php" method="POST" onsubmit="return validateForm()">
            <div class="form-group">
                <label for="address_name">Name</label>
                <input type="text" id="address_name" name="address_name" required placeholder="Your full name">
            </div>
            <div class="form-group">
                <label for="address_line1">Address Line 1</label>
                <input type="text" id="address_line1" name="address_line1" required placeholder="Address Line 1">
            </div>
            <div class="form-group">
                <label for="address_line2">Address Line 2</label>
                <input type="text" id="address_line2" name="address_line2" placeholder="Address Line 2 (optional)">
            </div>
            <div class="form-group row">
                <div class="flex-field">
                    <label for="city">City</label>
                    <input type="text" id="city" name="city" required placeholder="City">
                </div>
                <div class="flex-field">
                    <label for="district">District</label>
                    <input type="text" id="district" name="district" required placeholder="District">
                </div>
            </div>
            <div class="form-group row">
                <div class="flex-field">
                    <label for="pincode">Pincode</label>
                    <input type="text" id="pincode" name="pincode" required placeholder="Pincode">
                </div>
                <div class="flex-field">
                    <label for="phone_number">Phone(+91) </label>
                    <input type="text" id="phone_number" name="phone_number" required placeholder="Phone number">
                </div>
            </div>
            <div class="form-group">
                <label for="address_type">Address Type</label>
                <select id="address_type" name="address_type" required>
                    <option value="home">Home</option>
                    <option value="work">Work</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <button type="submit" class="submit-button">Add Address</button>
        </form>
    </div>
</div>
<script src="home.js"></script> 
<script>
function validateForm() {
    const pincode = document.getElementById('pincode').value;
    const phoneNumber = document.getElementById('phone_number').value;

    if (!/^\d{6}$/.test(pincode)) {
        alert('Invalid Pincode. Must be 6 digits.');
        return false;
    }

    if (!/^\d{10}$/.test(phoneNumber)) {
        alert('Invalid Phone Number. Must be 10 digits.');
        return false;
    }

    return true;
}
</script>
</body>
</html>
