<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Admin Dashboard</h2>
        <ul>
            <li><a href="admin_dashboard.php" class="outline-btn">Home</a></li>
            <li><a href="add_product.php" class="outline-btn active">Add Product</a></li>
            <li><a href="order_details.php" class="outline-btn">Order Details</a></li>
            <li><a href="products.php" class="outline-btn">Products</a></li>
            <li><a href="user_details.php" class="outline-btn">User List</a></li>
            <li><button class="outline-btn" id="logoutBtn">Logout</button></li>
        </ul>
    </div>

    <!-- Main content -->
    <div class="main-content">
        <div class="header">
            <h1>Add Product</h1>
        </div>
        
        <!-- Form for adding a new product -->
        <form id="addProductForm" action="add_product_action.php" method="POST" enctype="multipart/form-data">
            
            <!-- Product Images Upload -->
            <div class="form-group">
                <label for="productImages">Upload Product Images (up to 5)</label>
                <input type="file" name="product_images[]" id="productImages" multiple accept="image/*" required>
                <!-- Image preview container -->
                <div id="imagePreview" class="image-preview"></div>
            </div>

            <!-- Product Name -->
            <div class="form-group">
                <label for="productName">Product Name</label>
                <input type="text" name="product_name" id="productName" placeholder="Enter product name" required>
            </div>

            <!-- Product Description -->
            <div class="form-group">
                <label for="productDescription">Product Description</label>
                <textarea name="product_description" id="productDescription" rows="4" placeholder="Enter product description" required></textarea>
            </div>

            <!-- Size Selection -->
            <div class="form-group">
                <label>Enter Available Quantity for Each Size</label>
                <div class="size-row">
                    <div class="size-column">
                        <label for="sizeS">S</label>
                        <input type="number" name="product_size_s" id="sizeS" min="0" value="0">
                    </div>
                    <div class="size-column">
                        <label for="sizeM">M</label>
                        <input type="number" name="product_size_m" id="sizeM" min="0" value="0">
                    </div>
                    <div class="size-column">
                        <label for="sizeL">L</label>
                        <input type="number" name="product_size_l" id="sizeL" min="0" value="0">
                    </div>
                    <div class="size-column">
                        <label for="sizeXL">XL</label>
                        <input type="number" name="product_size_xl" id="sizeXL" min="0" value="0">
                    </div>
                    <div class="size-column">
                        <label for="sizeXXL">XXL</label>
                        <input type="number" name="product_size_xxl" id="sizeXXL" min="0" value="0">
                    </div>
                </div>
            </div>

            <!-- Product Price -->
            <div class="form-group">
                <label for="productPrice">Price</label>
                <input type="number" name="product_price" id="productPrice" placeholder="Enter price" required>
            </div>

            <!-- Product Category -->
            <div class="form-group">
                <label for="productCategory">Product Category</label>
                <select name="product_category" id="productCategory" required>
                    <option value="2000">Plain</option>
                    <option value="3000">Printed</option>
                </select>
            </div>

            <!-- Submit Button -->
            <div class="form-group">
                <button type="submit" class="btn-submit">Add Product</button>
            </div>

        </form>
    </div>

    <!-- Product Added Confirmation Popup -->
    <div id="successModal" class="modal" style="display:none;">
        <div class="modal-content">
            <span class="close" id="closeSuccessModal">&times;</span>
            <h2>Success!</h2>
            <p>Product added successfully!</p>
            <button onclick="window.location.href='add_product.php'">OK</button>
        </div>
    </div>


    <script src="admin.js"></script>
    <script>
        // Image preview and file validation script
        document.addEventListener("DOMContentLoaded", () => {
            const productImagesInput = document.getElementById("productImages");
            const imagePreviewContainer = document.getElementById("imagePreview");

            // Function to preview selected images
            const previewImages = (files) => {
                // Clear previous previews
                imagePreviewContainer.innerHTML = '';

                // Limit to 5 images
                const maxImages = 5;

                // Loop through the selected files
                for (let i = 0; i < files.length; i++) {
                    if (i >= maxImages) {
                        alert(`You can upload a maximum of ${maxImages} images.`);
                        break;
                    }
                    
                    const file = files[i];
                    const reader = new FileReader();

                    reader.onload = (event) => {
                        const imgElement = document.createElement('img');
                        imgElement.src = event.target.result; // Set the image source
                        imgElement.alt = file.name; // Set alt text for the image

                        // Append the image element to the preview container
                        imagePreviewContainer.appendChild(imgElement);
                    };

                    reader.readAsDataURL(file); // Read the file as a data URL
                }
            };

            // Event listener for file input change
            productImagesInput.addEventListener('change', (event) => {
                const files = event.target.files; // Get the selected files
                previewImages(files); // Call the preview function
            });
        });
    </script>
</body>
</html>
