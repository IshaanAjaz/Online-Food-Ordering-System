<?php
// add_menu_item.php
// Admin page to add new menu items.

require_once 'db_connect.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $is_available = isset($_POST['is_available']) ? 1 : 0;
    $image_url = $_POST['image_url']; // Optional image URL

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO menu_items (name, description, price, category, is_available, image_url) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdsis", $name, $description, $price, $category, $is_available, $image_url);

    if ($stmt->execute()) {
        $message = "Menu item added successfully!";
    } else {
        $message = "Error: " . $stmt->error;
    }
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Menu Item - SpiceVilla Admin</title>
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
        }
        .form-container {
            background-color: rgba(0, 0, 0, 0.7);
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 15px gold;
            width: 500px;
            text-align: center;
        }
        .form-container h1 {
            color: gold;
            margin-bottom: 20px;
        }
        .form-container label {
            display: block;
            margin-bottom: 8px;
            text-align: left;
            color: gold;
        }
        .form-container input[type="text"],
        .form-container input[type="number"],
        .form-container textarea,
        .form-container select {
            appearance: none;
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid gold;
            border-radius: 5px;
            background-color: rgba(255, 255, 255, 0.1);
            color: aliceblue;
        }
        .form-container input[type="checkbox"] {
            margin-right: 10px;
        }
        .form-container button {
            background-color: gold;
            color: black;
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
            transition: background-color 0.3s ease;
            margin-top: 20px;
        }
        .form-container button:hover {
            background-color: #ffd700;
        }
        .message {
            margin-top: 20px;
            color: #ffd700;
        }
        .back-link {
            margin-top: 20px;
            display: block;
            color: gold;
            text-decoration: none;
        }
        .back-link:hover {
            text-decoration: underline;
        }
         #category{
            color: gold;
            background-color: black;
         }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Add New Menu Item</h1>
        <?php if (!empty($message)): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>
        <form action="add_menu_item.php" method="POST">
            <label for="name">Item Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="4"></textarea>

            <label for="price">Price (&#8377;):</label>
            <input type="number" id="price" name="price" step="0.01" min="0" required>

            <label for="category">Category:</label>
            <select id="category" name="category">
                <option value="">Select Category</option>
                <option value="Pizza">Pizza</option>
                <option value="Indian">Indian</option>
                <option value="Chinese">Chinese</option>
                <option value="Pasta & Rolls">Pasta & Rolls</option>
                <option value="Burgers & Sandwiches">Burgers & Sandwiches</option>
                <option value="Shakes & Mocktails">Shakes & Mocktails</option>
                <option value="Other">Other</option>
            </select>

            <label for="image_url">Image URL (Optional):</label>
            <input type="text" id="image_url" name="image_url">

            <label>
                <input type="checkbox" id="is_available" name="is_available" checked>
                Available
            </label>
            <br>
            <button type="submit">Add Item</button>
        </form>
        <a href="admin_dashboard.php" class="back-link">Back to Admin Dashboard</a>
    </div>
</body>
</html>