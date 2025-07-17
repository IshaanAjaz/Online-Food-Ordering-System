<?php
// logout.php
// Destroys the session and redirects to login page.

session_start();
session_unset(); // Unset all session variables
session_destroy(); // Destroy the session

header("Location: login.php"); // Redirect to login page
exit();
?>
