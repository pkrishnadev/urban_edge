<?php
session_start();
include '../db_connect.php'; // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the user exists in the 'user' table (both admin and regular users are in this table)
    $queryUser = "SELECT * FROM user WHERE email = ?";
    $stmtUser = $conn->prepare($queryUser);
    $stmtUser->bind_param("s", $email);
    $stmtUser->execute();
    $resultUser = $stmtUser->get_result();

    if ($resultUser->num_rows > 0) {
        // User exists, fetch user data
        $user = $resultUser->fetch_assoc();
        
        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Password is correct, start the session and set session variables
            $_SESSION['user_logged_in'] = true;
            $_SESSION['user_id'] = $user['id']; // Store user ID for future use
            $_SESSION['user_email'] = $email; // Store email for future use
            $_SESSION['user_role'] = $user['role']; // Store role for future use

            // Redirect based on user role
            if ($user['role'] == 'admin') {
                // Admin login successful, set admin_logged_in session and redirect to admin dashboard
                $_SESSION['admin_logged_in'] = true;  // Set this session variable for admin
                header('Location: ../admin/admin_dashboard.php');
            } else {
                // Regular user login successful, redirect to home page
                header('Location: ../index.php');
            }
            exit;
        } else {
            // Incorrect password
            $error = "Invalid email or password";
        }
    } else {
        // If user not found, show an error
        $error = "Invalid email or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Fashion</title>
    <link rel="stylesheet" href="home.css">
</head>
<body>
<?php include('header.php'); ?>
    <div class="main-content">
        <div class="login-container">
            <!-- Left Side with Image -->
            <div class="left-image-section">
                <img src="../assets/login.jpg" alt="Fashion Image">
            </div>

            <!-- Right Side with Login Form -->
            <div class="right-login-section">
                <div class="login-content">
                    <h2>Log In to Your Account</h2>
                    <form method="POST" action="">
                        <input type="email" name="email" placeholder="Email" required>
                        <input type="password" name="password" placeholder="Password" required>
                        <button type="submit">Login</button>
                    </form>
                    <p>New to here? <a href="register.php" class="register-link">Register</a></p>
                </div>
            </div>
        </div>
    </div>
    
    <?php if (isset($error)): ?>
        <script>
            alert("<?= $error ?>");
        </script>
    <?php endif; ?>
    
    <script src="home.js"></script>
</body>
</html>
