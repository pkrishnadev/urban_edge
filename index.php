<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="home/home.css">
   
</head>
<body>
    <?php
    // Include the database connection file
    include('db_connect.php');

    // Start session to check if the user is logged in
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    $isLoggedIn = isset($_SESSION['user_id']); // Assuming session holds 'user_id' for logged-in users
    $cartItems = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; // Example of cart item count
    ?>

    <!-- header---------------------------------------------------- -->

    <header class="header-overlay">
        <!-- Menu button on the left -->
        <div class="menu-btn">
            <button id="menuToggle">&#9776;</button> <!-- Hamburger menu icon -->
        </div>

        <!-- Logo in the center -->
        <div class="logo">
            <a href="index.php">
                <img src="assets/urban_edge.png" alt="Urban Edge Logo">
            </a>
        </div>

        <!-- Cart and Login buttons on the right -->
        <div class="header-right">
            <?php if ($isLoggedIn): ?>
                <button class="header-btn" onclick="location.href='http://localhost/urban_edge/home/cart.php'">Cart (<?= $cartItems ?>)</button>
            <?php else: ?>
                <button class="header-btn" onclick="location.href='http://localhost/urban_edge/home/login.php'">Login</button>
            <?php endif; ?>
        </div>


        <!-- Sidebar Menu -->
        <div id="sidebar" class="sidebar">
            <div class="sidebar-content">
                <button class="close-btn" id="closeMenu">&times;</button>
                <?php if ($isLoggedIn): ?>
                    <a href="home/profile.php">Profile</a>
                    <a href="home/order.php">Orders</a>
                    <a href="home/address.php">Address</a>
                <?php else: ?>
                    <p>Login or register to continue</p>
                    <a href="home/login.php">Login</a>
                    <a href="home/register.php">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </header>
    <!-- main banner ---------------------------------------------------- -->

    <div class="main-banner">
        <img src="assets/landingbanner.png" alt="Main Banner">
        <div class="banner-content">
            <h1>Discover </h1>
            <h1>Your</h1>
            <h1>Style</h1>
            <p>"Explore our collection of unisex t-shirts, featuring a perfect blend of comfort 
                and style with options ranging from timeless plain designs to bold and creative printed patterns"</p>
            <a href="http://localhost/urban_edge/home/product_list.php?category_id=2000,3000&search=" class="shop-btn">SHOP NOW</a>
        </div>
    </div>

    
    <!-- cateorgry ---------------------------------------------------- -->             

        <div class="category-section">
            <div class="category plain">
                <a href="home/product_list.php?category_id=2000">
                    <img src="assets/home_plain.png" alt="Plain T-Shirts" class="category-image">
                    <div class="text-container">
                        <h3>Plain T-Shirts</h3>
                        <span class="explore-button">EXPLORE</span>
                    </div>
                </a>
            </div>
            <div class="category printed">
                <a href="home/product_list.php?category_id=3000">
                    <img src="assets/home_printed.png" alt="Printed T-Shirts" class="category-image">
                    <div class="text-container">
                        <h3>Printed T-Shirts</h3>
                        <span class="explore-button">EXPLORE</span>
                    </div>
                </a>
            </div>
        </div>


    <!-- banner---------------------------------------------------- -->

    <div class="banner-slideshow-container">
        <div class="banner-slide banner-fade">
            <img src="assets/banner1.jpg" alt="Banner 1">
        </div>
        <div class="banner-slide banner-fade">
            <img src="assets/banner2.jpg" alt="Banner 2">
        </div>
        <div class="banner-slide banner-fade">
            <img src="assets/banner3.jpg" alt="Banner 3">
        </div>
        <div class="banner-slide banner-fade">
            <img src="assets/banner4.jpg" alt="Banner 4">
        </div>
    </div>
    <script>
        let currentIndex = 0;

        function showBannerSlides() {
            const slides = document.querySelectorAll('.banner-slide');
            slides.forEach((slide, index) => {
                slide.style.display = index === currentIndex ? 'block' : 'none';
            });
            currentIndex = (currentIndex + 1) % slides.length;
        }

        setInterval(showBannerSlides, 4000);
        document.addEventListener('DOMContentLoaded', showBannerSlides);
    </script>
    <!-- About Banner ---------------------------------------------------- -->
    <div class="about-banner">
            <img src="assets/aboutbanner.png" alt="Main Banner">
            <div class="about-banner-text">
                <h1>Bold Fashion,</h1>
                <h1>Simple Choices</h1>
                <p>Discover a wide range of t-shirts</p>
                <p>designed for the modern trendsetter</p>
            </div>
        </div>
         
    <!-- footer---------------------------------------------------- -->              
    <div class="footer">
        <div class="footer-content">
            <div class="footer-left">
                <img src="assets/urban_edge_white.png" alt="Urban Edge Logo" class="footer-logo">
                <p class="footer-tagline">“STYLE THAT STANDS OUT, JUST LIKE YOU”</p>
            </div>
            <div class="footer-center">
                <a href="home/about_us.php">ABOUT US</a>
                <a href="home/contact_us.php">CONTACT US</a>
                <a href="home/faqs.php">FAQS</a>
                <a href="home/terms_conditions.php">TERMS AND CONDITIONS</a>
                <a href="home/privacy_policy.php">PRIVACY POLICY</a>
            </div>
            <div class="footer-right">
                <a href="https://facebook.com" target="_blank">FACEBOOK</a>
                <a href="https://instagram.com" target="_blank">INSTAGRAM</a>
                <a href="https://twitter.com" target="_blank">TWITTER</a>
            </div>
        </div>
        <div class="footer-bottom">
            <p>2024 Urban Edge. All Rights Reserved. | Designed And Developed By Urban Edge Team.</p>
        </div>
    </div>  
    <script src="home/home.js"></script>      
</body>
</html>

