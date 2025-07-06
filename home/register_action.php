<?php
session_start(); // Start the session to store user info

// Database connection
include '../db_connect.php';

$emailError = $passwordError = $termsError = "";
$isFormValid = true;

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
        $emailError = "Email already exists";
        $isFormValid = false;
    }

    // Validate password
    if (empty($password)) {
        $passwordError = "Password is required";
        $isFormValid = false;
    }

    // Check if terms are accepted
    if (empty($terms)) {
        $termsError = "Agree to the Terms and Conditions to continue";
        $isFormValid = false;
    }

    // If all validations pass
    if ($isFormValid) {
        // Hash the password for security
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $defaultRole = 'user';

        // Store user data in the database
        $insertQuery = "INSERT INTO user (email, password, role) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("sss", $email, $hashedPassword, $defaultRole);

        if ($stmt->execute()) {
            // Fetch the user from the database after successful insertion
            $userId = $stmt->insert_id;

            // Increment total_customers in site_statistics
            $updateStatisticsQuery = "UPDATE site_statistics SET total_customers = total_customers + 1 WHERE id = 1";
            if ($conn->query($updateStatisticsQuery) === TRUE) {
                // Store user details in session to log in the user
                $_SESSION['user_id'] = $userId;
                $_SESSION['email'] = $email;
                $_SESSION['role'] = $defaultRole;

                // Redirect to index.php after registration and login
                header("Location: ../index.php");
                exit();
            } else {
                echo "Error updating statistics: " . $conn->error;
            }
        } else {
            echo "Error: " . $conn->error;
        }
    }
}
?>
