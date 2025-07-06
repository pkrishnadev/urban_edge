<?php
session_start();
include '../db_connect.php'; // Assuming your database connection is in this file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit();
}

$user_id = $_SESSION['user_id']; // Get the logged-in user's ID

// Initialize address data
$address = null;

// Check if an address ID is provided for editing
if (isset($_GET['address_id'])) {
    $address_id = $_GET['address_id'];

    // Fetch the existing address details
    $query = "SELECT * FROM addresses WHERE address_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $address_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $address = $result->fetch_assoc();
    } else {
        echo "<script>alert('Address not found.'); window.location.href = 'addresses.php';</script>";
        exit();
    }
}

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

    // Validate pincode (6 digits)
    if (!preg_match('/^[0-9]{6}$/', $pincode)) {
        echo "<script>alert('Pincode must be exactly 6 digits.'); window.location.href = 'edit_address.php?address_id=$address_id';</script>";
        exit();
    }

    // Validate phone number (10 digits)
    if (!preg_match('/^[0-9]{10}$/', $phone_number)) {
        echo "<script>alert('Phone number must be exactly 10 digits.'); window.location.href = 'edit_address.php?address_id=$address_id';</script>";
        exit();
    }

    // Update the existing address
    $query = "UPDATE addresses SET address_name = ?, address_line1 = ?, address_line2 = ?, city = ?, street = ?, pincode = ?, phone_number = ?, address_type = ? WHERE address_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssssssi", $address_name, $address_line1, $address_line2, $city, $street, $pincode, $phone_number, $address_type, $address_id);

    if ($stmt->execute()) {
        echo "<script>alert('Address updated successfully!'); window.location.href = 'address.php';</script>";
    } else {
        echo "<script>alert('Error updating address');</script>";
    }    
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Address</title>
    <link rel="stylesheet" href="home.css">
    <script>
        function validateForm() {
            var pincode = document.getElementById('pincode').value;
            var phone_number = document.getElementById('phone_number').value;

            // Validate pincode (6 digits)
            var pincodePattern = /^[0-9]{6}$/;
            if (!pincodePattern.test(pincode)) {
                alert("Pincode must be exactly 6 digits.");
                return false;
            }

            // Validate phone number (10 digits)
            var phonePattern = /^[0-9]{10}$/;
            if (!phonePattern.test(phone_number)) {
                alert("Phone number must be exactly 10 digits.");
                return false;
            }

            return true; // Allow form submission if both validations pass
        }
    </script>
</head>
<body>
<?php include('header.php'); ?>
<div class="main-content">
    <div class="address-form-container">
        <h2>Edit Shipping Address</h2>
        <form action="edit_address.php?address_id=<?php echo $address['address_id']; ?>" method="POST" onsubmit="return validateForm()">
            <div class="form-group">
                <label for="address_name">Name</label>
                <input type="text" id="address_name" name="address_name" required placeholder="Your full name" value="<?php echo htmlspecialchars($address['address_name']); ?>">
            </div>
            <div class="form-group">
                <label for="address_line1">Address Line 1</label>
                <input type="text" id="address_line1" name="address_line1" required placeholder="Address Line 1" value="<?php echo htmlspecialchars($address['address_line1']); ?>">
            </div>
            <div class="form-group">
                <label for="address_line2">Address Line 2</label>
                <input type="text" id="address_line2" name="address_line2" placeholder="Address Line 2 (optional)" value="<?php echo htmlspecialchars($address['address_line2']); ?>">
            </div>
            <div class="form-group row"> <!-- Flex container for city and district -->
                <div class="flex-field">
                    <label for="city">City</label>
                    <input type="text" id="city" name="city" required placeholder="City" value="<?php echo htmlspecialchars($address['city']); ?>">
                </div>
                <div class="flex-field">
                    <label for="district">District</label>
                    <input type="text" id="district" name="district" required placeholder="District" value="<?php echo htmlspecialchars($address['street']); ?>">
                </div>
            </div>
            <div class="form-group row"> <!-- Flex container for pincode and phone number -->
                <div class="flex-field">
                    <label for="pincode">Pincode</label>
                    <input type="text" id="pincode" name="pincode" required placeholder="Pincode" value="<?php echo htmlspecialchars($address['pincode']); ?>">
                </div>
                <div class="flex-field">
                    <label for="phone_number">Phone (+91)</label>
                    <input type="text" id="phone_number" name="phone_number" required placeholder="Phone number" value="<?php echo htmlspecialchars($address['phone_number']); ?>">
                </div>
            </div>
            <div class="form-group">
                <label for="address_type">Address Type</label>
                <select id="address_type" name="address_type" required>
                    <option value="home" <?php echo ($address['address_type'] == 'home') ? 'selected' : ''; ?>>Home</option>
                    <option value="work" <?php echo ($address['address_type'] == 'work') ? 'selected' : ''; ?>>Work</option>
                    <option value="other" <?php echo ($address['address_type'] == 'other') ? 'selected' : ''; ?>>Other</option>
                </select>
            </div>
            <button type="submit" class="submit-button">Update Address</button>
        </form>
    </div>
</div>

<script src="home.js"></script>
</body>
</html>
