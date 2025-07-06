<?php
session_start();

// Destroy all sessions and logout the admin
session_unset();
session_destroy();

// Redirect to the homepage (index.php)
header('Location: ../index.php');
exit;
?>
