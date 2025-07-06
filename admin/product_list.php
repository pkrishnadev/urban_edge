<?php 
// Check if the admin is logged in
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ../home/login.php');
    exit;
}
// Include database connection
include('../db_connect.php');


// Get the category from the URL parameters
$category_name = isset($_GET['category']) ? $_GET['category'] : '';

// Sanitize the input to prevent SQL injection
$category_name = mysqli_real_escape_string($conn, $category_name);

// Fetch products of the selected category with their images and sizes from the products table
$query = "
    SELECT p.product_id, p.title, p.price, p.stock_s, p.stock_m, p.stock_l, p.stock_xl, p.stock_xxl, pi.image_path
    FROM products p
    LEFT JOIN product_images pi ON p.product_id = pi.product_id
    LEFT JOIN categories c ON p.category_id = c.category_id
    WHERE c.category_name = '$category_name'
    GROUP BY p.product_id
    ORDER BY p.product_id
";

$result = mysqli_query($conn, $query);

// Count total number of products
$total_products = mysqli_num_rows($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
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
            <h1>Product List</h1>
            <div class="total-products-card" style="float: right;">
                <p>Total Products: <?php echo $total_products; ?></p>
            </div>
        </div>

        <div class="product-list">
            <?php if ($total_products > 0): ?>
                <?php $product_number = 1; // Initialize product numbering ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <div class="product-row">
                        <span class="product-number"><?php echo $product_number; ?></span> <!-- Display product number -->

                        <!-- Display one image for each product -->
                        <img src="<?php echo htmlspecialchars($row['image_path']); ?>" alt="<?php echo htmlspecialchars($row['title']); ?>" class="product-image">

                        <div class="product-details">
                            <h3 class="product-title"><?php echo htmlspecialchars($row['title']); ?></h3>
                            <p class="product-price">Price: ₹<?php echo htmlspecialchars($row['price']); ?></p>

                            <!-- Display sizes in a much smaller table with new class name -->
                            <table class="size-table-custom">
                                <thead>
                                    <tr>
                                        <th>S</th>
                                        <th>M</th>
                                        <th>L</th>
                                        <th>XL</th>
                                        <th>XXL</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><?php echo ($row['stock_s'] > 0) ? $row['stock_s'] : '<span style="color: red;">No Stock</span>'; ?></td>
                                        <td><?php echo ($row['stock_m'] > 0) ? $row['stock_m'] : '<span style="color: red;">No Stock</span>'; ?></td>
                                        <td><?php echo ($row['stock_l'] > 0) ? $row['stock_l'] : '<span style="color: red;">No Stock</span>'; ?></td>
                                        <td><?php echo ($row['stock_xl'] > 0) ? $row['stock_xl'] : '<span style="color: red;">No Stock</span>'; ?></td>
                                        <td><?php echo ($row['stock_xxl'] > 0) ? $row['stock_xxl'] : '<span style="color: red;">No Stock</span>'; ?></td>
                                    </tr>
                                </tbody>

                            </table>
                        </div>

                        <div class="button-group" style="text-align: center;">
                            <!-- Update button linking to edit_product.php with product_id -->
                            <a href="edit_product.php?product_id=<?php echo $row['product_id']; ?>" class="update-button">Update</a>
                            <button class="delete-button" data-product-id="<?php echo $row['product_id']; ?>">Delete</button>
                        </div>
                    </div>
                    <?php $product_number++;  ?>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No products found in this category.</p>
            <?php endif; ?>
        </div>
    </div>
    
        <!-- Logout Confirmation Popup -->
    <div id="logoutModal" class="modal">
        <div class="modal-content">
            <span class="close" id="closeModal">&times;</span>
            <h2>Confirm Logout</h2>
            <p>Are you sure you want to logout?</p>
            <button id="confirmLogout">Logout</button>
            <button id="cancelLogout">Cancel</button>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <span class="close" id="closeDeleteModal">&times;</span>
            <h2>Confirm Delete</h2>
            <p>Are you sure you want to delete this product?</p>
            <button id="confirmDelete">Delete</button>
            <button id="cancelDelete">Cancel</button>
        </div>
    </div>

    <!-- Success Message Modal -->
    <?php if (isset($_SESSION['message'])): ?>
        <div id="successModal" class="modal">
            <div class="modal-content">
                <span class="close" id="closeSuccessModal">&times;</span>
                <h2><?php echo $_SESSION['message']; ?></h2>
            </div>
        </div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    <!--delete button------------ -->
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        let deleteButtons = document.querySelectorAll('.delete-button');
        let deleteModal = document.getElementById('deleteModal');
        let confirmDeleteButton = document.getElementById('confirmDelete');
        let cancelDeleteButton = document.getElementById('cancelDelete');
        let closeDeleteModalButton = document.getElementById('closeDeleteModal');
        let productIdToDelete;

        deleteButtons.forEach(button => {
            button.addEventListener('click', function () {
                productIdToDelete = this.getAttribute('data-product-id');
                deleteModal.style.display = 'block';
            });
        });

        confirmDeleteButton.addEventListener('click', function () {
            // Make an AJAX request to delete the product
            fetch('delete_product.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ id: productIdToDelete }),
            })
            .then(response => {
                if (response.ok) {
                    // Remove the product row from the DOM
                    document.querySelector(`button[data-product-id="${productIdToDelete}"]`).closest('.product-row').remove();
                }
            })
            .catch(error => console.error('Error:', error));

            deleteModal.style.display = 'none'; // Hide the modal
        });

        cancelDeleteButton.addEventListener('click', function () {
            deleteModal.style.display = 'none'; // Hide the modal
        });

        closeDeleteModalButton.addEventListener('click', function () {
            deleteModal.style.display = 'none'; // Hide the modal
        });
    });
</script>

    <script src="admin.js"></script>
</body>
</html>
