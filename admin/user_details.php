<?php
// Check if the admin is logged in
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ../home/login.php');
    exit;
}

// Connect to the database
include '../db_connect.php';

$popupMessage = ''; // Variable to store JavaScript popup message

// Handle user deletion
if (isset($_POST['delete_user_id'])) {
    $userIdToDelete = $_POST['delete_user_id'];

    // SQL query to delete the user
    $query = "DELETE FROM user WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userIdToDelete);

    // Attempt to delete the user
    if ($stmt->execute()) {
        // Decrement the total_customers in the site_statistics table
        $updateStatisticsQuery = "UPDATE site_statistics SET total_customers = total_customers - 1, last_updated = NOW() WHERE id = 1";
        if ($conn->query($updateStatisticsQuery)) {
            $popupMessage = "User deleted successfully and statistics updated.";
        } else {
            $popupMessage = "User deleted, but failed to update statistics.";
        }
    } else {
        $popupMessage = "Error deleting user.";
    }

    $stmt->close();
}

// Handle role update
if (isset($_POST['update_role_id'])) {
    $userIdToUpdate = $_POST['update_role_id'];
    $newRole = $_POST['new_role'];

    // Fetch the user's email to prevent role update for admin@gmail.com
    $query = "SELECT email FROM user WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userIdToUpdate);
    $stmt->execute();
    $stmt->bind_result($userEmail);
    $stmt->fetch();
    $stmt->close();

    // Check if the email is admin@gmail.com
    if ($userEmail === 'admin@gmail.com') {
        $popupMessage = "You cannot update the role of admin@gmail.com.";
    } else {
        // SQL query to update the user's role
        $updateRoleQuery = "UPDATE user SET role = ? WHERE id = ?";
        $stmt = $conn->prepare($updateRoleQuery);
        $stmt->bind_param("si", $newRole, $userIdToUpdate);

        // Attempt to update the role
        if ($stmt->execute()) {
            $popupMessage = "Role updated successfully.";
        } else {
            $popupMessage = "Error updating role.";
        }

        $stmt->close();
    }
}

// Fetch users from the user table
$query = "SELECT id, email, role FROM user";
$result = $conn->query($query);
$total_users = $result->num_rows;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Details</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <div class="sidebar">
        <h2 class="sidebar-title">Admin Dashboard</h2>
        <ul class="sidebar-nav">
            <li><a href="admin_dashboard.php" class="sidebar-link">Home</a></li>
            <li><a href="add_product.php" class="sidebar-link">Add Product</a></li>
            <li><a href="order_details.php" class="sidebar-link">Order Details</a></li>
            <li><a href="products.php" class="sidebar-link">Products</a></li>
            <li><a href="user_details.php" class="sidebar-link">User List</a></li>
            <li><button class="sidebar-logout-btn" id="logoutBtn">Logout</button></li>
        </ul>
    </div>
    
    <div class="main-content">
        <div class="header">
            <h1 class="header-title">User Details</h1>
            <div class="total-users-card">
                <h2 class="total-users-text">Total Users: <?php echo $total_users; ?></h2>
            </div>
        </div>

        <table class="user-table">
            <thead>
                <tr>
                    <th class="table-header">SL No.</th>
                    <th class="table-header">Email</th>
                    <th class="table-header">Role</th>
                    <th class="table-header">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php $count = 1; ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr class="user-row">
                            <td class="table-cell"><?php echo $count++; ?></td>
                            <td class="table-cell"><?php echo $row['email']; ?></td>
                            <td class="table-cell">
                                <?php if ($row['email'] !== 'admin@gmail.com'): ?>
                                    <!-- Editable role field for users except admin@gmail.com -->
                                    <form action="user_details.php" method="POST" class="update-role-form">
                                        <input type="hidden" name="update_role_id" value="<?php echo $row['id']; ?>">
                                        <select name="new_role" class="role-dropdown" required>
                                            <option value="user" <?php echo ($row['role'] == 'user') ? 'selected' : ''; ?>>User</option>
                                            <option value="admin" <?php echo ($row['role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                                        </select>
                                        <button type="submit" class="update-role-btn">Update</button>
                                    </form>
                                <?php else: ?>
                                    <span class="role-text">Admin</span> <!-- Display "Admin" for admin@gmail.com -->
                                <?php endif; ?>
                            </td>
                            <td class="table-cell">
                                <!-- Delete button with JavaScript confirmation -->
                                <form onsubmit="return confirmUserDeletion(this);" action="user_details.php" method="POST" class="delete-user-form">
                                    <input type="hidden" name="delete_user_id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" class="remove-user-btn">Remove</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="no-users">No users found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script src="admin.js"></script>
    <?php if (!empty($popupMessage)): ?>
        <script>
            alert("<?php echo $popupMessage; ?>");
        </script>
    <?php endif; ?>
</body>
</html>
