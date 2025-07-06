<?php
// Start session to check if the user is logged in
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$isLoggedIn = isset($_SESSION['user_id']); // Assuming session holds 'user_id' for logged-in users
$cartItems = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; // Example of cart item count
?>

<header class="header-overlay">
    <link rel="stylesheet" href="home.css">

    <!-- Menu button on the left -->
    <div class="menu-btn">
        <button id="menuToggle">&#9776;</button> <!-- Hamburger menu icon -->
    </div>

    <!-- Logo in the center -->
    <div class="logo">
        <a href="../index.php">
            <img src="http://localhost/urban_edge/assets/urban_edge.png" alt="Urban Edge Logo">
        </a>
    </div>

    <!-- Cart and Login buttons on the right -->
    <div class="header-right">
        <?php if ($isLoggedIn): ?>
            <button class="header-btn" onclick="location.href='cart.php'">Cart (<?= $cartItems ?>)</button>
        <?php else: ?>
            <button class="header-btn" onclick="location.href='http://localhost/urban_edge/home/login.php'">Login</button>
        <?php endif; ?>
    </div>


    <!-- Sidebar Menu -->
    <div id="sidebar" class="sidebar">
        <div class="sidebar-content">
            <button class="close-btn" id="closeMenu">&times;</button>
            <?php if ($isLoggedIn): ?>
                <a href="profile.php">Profile</a>
                <a href="order.php">Orders</a>
                <!-- Link to address page -->
                <a href="address.php">Address</a>
            <?php else: ?>
                <p>Login or register to continue</p>
                <a href="login.php">Login</a>
                <a href="register.php">Register</a>
            <?php endif; ?>
        </div>
    </div>

    <script src="home.js" defer></script>
</header>


