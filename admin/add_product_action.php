<?php

// Include d
require_once '../db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    $product_title = mysqli_real_escape_string($conn, $_POST['product_name']);
    $product_description = mysqli_real_escape_string($conn, $_POST['product_description']);
    $price = mysqli_real_escape_string($conn, $_POST['product_price']);
    $category_id = mysqli_real_escape_string($conn, $_POST['product_category']);

    // Get the size quantities from the form
    $size_s = isset($_POST['product_size_s']) ? (int)$_POST['product_size_s'] : 0;
    $size_m = isset($_POST['product_size_m']) ? (int)$_POST['product_size_m'] : 0;
    $size_l = isset($_POST['product_size_l']) ? (int)$_POST['product_size_l'] : 0;
    $size_xl = isset($_POST['product_size_xl']) ? (int)$_POST['product_size_xl'] : 0;
    $size_xxl = isset($_POST['product_size_xxl']) ? (int)$_POST['product_size_xxl'] : 0;

    // Prepare SQL to insert product data into the products table
    $insertProductSQL = "INSERT INTO products (title, description, price, category_id, stock_s, stock_m, stock_l, stock_xl, stock_xxl) 
                         VALUES ('$product_title', '$product_description', '$price', '$category_id', '$size_s', '$size_m', '$size_l', '$size_xl', '$size_xxl')";
    
    // Execute the query
    if (mysqli_query($conn, $insertProductSQL)) {
        $product_id = mysqli_insert_id($conn); // Get the last inserted product ID

        // Handle image uploads (if multiple images)
        if (isset($_FILES['product_images']) && $_FILES['product_images']['error'][0] != UPLOAD_ERR_NO_FILE) {
            $total_files = count($_FILES['product_images']['name']);
            $uploads_dir = '../admin/uploads/'; // Directory for uploads
            $base_url = 'http://localhost/urban_edge/admin/uploads/'; // Base URL for accessing images

            for ($i = 0; $i < $total_files; $i++) {
                if ($_FILES['product_images']['error'][$i] === UPLOAD_ERR_OK) { // Check if there's no error in upload
                    $image_name = $_FILES['product_images']['name'][$i];
                    $image_tmp = $_FILES['product_images']['tmp_name'][$i];
                    $target_file = $uploads_dir . basename($image_name);
                    
                    // Move uploaded file to target directory
                    if (move_uploaded_file($image_tmp, $target_file)) {
                        // Store the absolute path for the database
                        $absolute_path = $base_url . $image_name;
                        // Insert each image path into the product_images table
                        $insertImageSQL = "INSERT INTO product_images (product_id, image_path) VALUES ('$product_id', '$absolute_path')";
                        mysqli_query($conn, $insertImageSQL);
                    } else {
                        echo "Error uploading image: " . $image_name . "<br>";
                    }
                } else {
                    echo "Error in image upload: " . $_FILES['product_images']['error'][$i] . "<br>";
                }
            }
        }


        // Increment the total_products field in the site_statistics table
        $updateStatisticsSQL = "UPDATE site_statistics SET total_products = total_products + 1, last_updated = NOW() WHERE id = 1";
        if (!mysqli_query($conn, $updateStatisticsSQL)) {
            echo "Error updating site statistics: " . mysqli_error($conn);
        }

        // Success message and redirect
        echo "<script>alert('Product added successfully!'); window.location.href='add_product.php';</script>";
    } else {
        echo "Error: " . $insertProductSQL . "<br>" . mysqli_error($conn);
    }

    // Close database connection
    mysqli_close($conn);
}
?>
