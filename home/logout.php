<?php
session_start();
session_unset(); 
session_destroy();
header('Location: ../index.php'); 
// On logout, delete the cookie
setcookie('buynow', '', time() - 3600, '/'); // Expire the cookie
unset($_SESSION['user_id']);
session_destroy();
header("Location: login.php");
exit(); 
?>
