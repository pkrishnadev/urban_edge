<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <div class="sidebar">
        <h2>Admin Dashboard</h2>
        <ul>
            <li><a href="admin_dashboard.php" class="outline-btn">Home</a></li>
            <li><a href="add_product.php" class="outline-btn">Add Product</a></li>
            <li><a href="order_details.php" class="outline-btn">Order Details</a></li>
            <li><a href="products.php" class="outline-btn">Products</a></li>
            <li><a href="user_details.php" class="outline-btn">User List</a></li>
            <li><button class="outline-btn" id="logoutBtn">Logout</button></li>
        </ul>
    </div>
    
    <div class="main-content">
        <div class="header">
            <h1>Select Product Category</h1>
        </div>

        <! category ------------------------------------------ ->
        <section class="categories">
            <div class="category">
                <a href="product_list.php?category=plain"> <!-- Link to product_list.php with category -->
                    <div class="category-image-container">
                        <img src="assets\plain_tshirt.jpg" alt="Plain T-Shirts" class="category-image">
                    </div>
                    <div class="category-title">
                        <h2>Plain T-Shirts</h2>
                    </div>
                </a>
            </div>
            <div class="category">
                <a href="product_list.php?category=printed"> <!-- Link to product_list.php with category -->
                    <div class="category-image-container">
                        <img src="assets\printed_tshirt.png" alt="Printed T-Shirts" class="category-image">
                    </div>
                    <div class="category-title">
                        <h2>Printed T-Shirts</h2>
                    </div>
                </a>
            </div>
        </section>


    </div>


    <script src="admin.js"></script>
</body>
</html>
