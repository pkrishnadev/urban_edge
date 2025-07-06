<?php
session_start(); // Start the session to store user info

// Database connection
include '../db_connect.php';

$isFormValid = true;
$errorMessage = ""; // Initialize error message

// Handle AJAX email validation
if (isset($_POST['validate_email']) && $_POST['validate_email'] === 'true') {
    $email = $_POST['email'];
    $checkEmailQuery = "SELECT * FROM user WHERE email = ?";
    $stmt = $conn->prepare($checkEmailQuery);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    echo $result->num_rows > 0 ? 'exists' : 'available';
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $terms = isset($_POST['terms']) ? $_POST['terms'] : '';

    // Check if email already exists in the database
    $checkEmailQuery = "SELECT * FROM user WHERE email = ?";
    $stmt = $conn->prepare($checkEmailQuery);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $errorMessage = "Email already exists";
        $isFormValid = false;
    }

    // Validate password
    if (empty($password)) {
        $errorMessage = "Password is required";
        $isFormValid = false;
    }

    // Check if terms are accepted
    if (empty($terms)) {
        $errorMessage = "Agree to the Terms and Conditions to continue";
        $isFormValid = false;
    }

    // If all validations pass
    if ($isFormValid) {
        // Hash the password for security
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $defaultRole = 'user';

        // Use a transaction to ensure data integrity
        $conn->begin_transaction();

        try {
            // Store user data in the database
            $insertQuery = "INSERT INTO user (email, password, role) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($insertQuery);
            $stmt->bind_param("sss", $email, $hashedPassword, $defaultRole);

            if (!$stmt->execute()) {
                throw new Exception("Error inserting user: " . $conn->error);
            }

            // Fetch the user from the database after successful insertion
            $userId = $stmt->insert_id;

            // Increment total_customers in site_statistics
            $updateStatisticsQuery = "UPDATE site_statistics SET total_customers = total_customers + 1 WHERE id = 1";
            if (!$conn->query($updateStatisticsQuery)) {
                throw new Exception("Error updating site_statistics: " . $conn->error);
            }

            // Commit the transaction
            $conn->commit();

            // Store user details in session to log in the user
            $_SESSION['user_id'] = $userId;
            $_SESSION['email'] = $email;
            $_SESSION['role'] = $defaultRole;

            // Redirect to index.php after registration and login
            header("Location: ../index.php");
            exit();
        } catch (Exception $e) {
            // Rollback the transaction on failure
            $conn->rollback();
            $errorMessage = "Transaction failed: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Fashion</title>
    <link rel="stylesheet" href="home.css">
    <script>
        // Display the error message as a popup alert
        <?php if (!empty($errorMessage)): ?>
        alert("<?php echo $errorMessage; ?>");
        <?php endif; ?>
    </script>
</head>
<body>
<?php include('header.php'); ?>
    <div class="main-content">
        <div class="register-card">
            <div class="left-section">
                <img src="../assets/register.jpg" alt="Fashion Image">
            </div>
            <div class="right-section">
                <h2>Register Now</h2>
                <p>Create a new account with an email and password</p>
                <form id="registerForm" method="POST" action="register.php">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="Enter your email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="Enter your password" required>
                    </div>
                    <div class="form-group terms-container">
                        <label for="termsCheckbox" class="terms-label">
                            <input type="checkbox" id="termsCheckbox" name="terms" required>
                            I agree to the <a href="terms_conditions.php" class="terms-link">Terms and Conditions</a>
                        </label>
                    </div>


                    <button type="submit" id="registerButton" class="register-btn">Register</button>
                    <p class="login-redirect">Already have an account? <a href="login.php" class="login-link">Login</a></p>
                </form>
            </div>
        </div>
    </div>
<script src="home.js"></script>
</body>
</html>
