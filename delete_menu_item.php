<?php
// delete_menu_item.php
// Admin page to delete menu items.

require_once 'db_connect.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$message = '';

if (isset($_GET['id'])) {
    $item_id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM menu_items WHERE id = ?");
    $stmt->bind_param("i", $item_id);

    if ($stmt->execute()) {
        $message = "Menu item deleted successfully!";
    } else {
        $message = "Error deleting item: " . $stmt->error;
    }
    $stmt->close();
} else {
    $message = "No item ID provided for deletion.";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Menu Item - SpiceVilla Admin</title>
    <link rel="stylesheet" href="spicevilla.css">
    <link rel="stylesheet" href="about.css">
    <style>
        body {
            background-color: #000000e7;
            color: aliceblue;
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            text-align: center;
        }
        .message-container {
            background-color: rgba(0, 0, 0, 0.7);
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 15px gold;
            width: 400px;
        }
        .message-container h1 {
            color: gold;
            margin-bottom: 20px;
        }
        .message {
            color: #ffd700;
            margin-bottom: 20px;
        }
        .back-link {
            display: inline-block;
            background-color: gold;
            color: black;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
        .back-link:hover {
            background-color: #ffd700;
        }
    </style>
</head>
<body>
    <div class="message-container">
        <h1>Delete Item Status</h1>
        <p class="message"><?php echo $message; ?></p>
        <a href="admin_dashboard.php" class="back-link">Back to Admin Dashboard</a>
    </div>
</body>
</html>