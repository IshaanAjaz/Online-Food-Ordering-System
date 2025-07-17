<?php
// edit_menu_item.php
// Admin page to edit existing menu items.

require_once 'db_connect.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$item = null;
$message = '';

if (isset($_GET['id'])) {
    $item_id = $_GET['id'];
    $stmt = $conn->prepare("SELECT id, name, description, price, category, image_url, is_available FROM menu_items WHERE id = ?");
    $stmt->bind_param("i", $item_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $item = $result->fetch_assoc();
    } else {
        $message = "Menu item not found.";
    }
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['item_id'])) {
    $item_id = $_POST['item_id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $is_available = isset($_POST['is_available']) ? 1 : 0;
    $image_url = $_POST['image_url'];

    $stmt = $conn->prepare("UPDATE menu_items SET name = ?, description = ?, price = ?, category = ?, is_available = ?, image_url = ? WHERE id = ?");
    $stmt->bind_param("ssdsisi", $name, $description, $price, $category, $is_available, $image_url, $item_id);

    if ($stmt->execute()) {
        $message = "Menu item updated successfully!";
        // Refresh item data after update
        $stmt_refresh = $conn->prepare("SELECT id, name, description, price, category, image_url, is_available FROM menu_items WHERE id = ?");
        $stmt_refresh->bind_param("i", $item_id);
        $stmt_refresh->execute();
        $result_refresh = $stmt_refresh->get_result();
        $item = $result_refresh->fetch_assoc();
        $stmt_refresh->close();
    } else {
        $message = "Error updating item: " . $stmt->error;
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
    <title>Edit Menu Item - SpiceVilla Admin</title>
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
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Edit Menu Item</h1>
        <?php if (!empty($message)): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>

        <?php if ($item): ?>
            <form action="edit_menu_item.php" method="POST">
                <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">

                <label for="name">Item Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($item['name']); ?>" required>

                <label for="description">Description:</label>
                <textarea id="description" name="description" rows="4"><?php echo htmlspecialchars($item['description']); ?></textarea>

                <label for="price">Price (&#8377;):</label>
                <input type="number" id="price" name="price" step="0.01" min="0" value="<?php echo htmlspecialchars($item['price']); ?>" required>

                <label for="category">Category:</label>
                <select id="category" name="category" required>
                    <option value="Pizza" <?php echo ($item['category'] == 'Pizza') ? 'selected' : ''; ?>>Pizza</option>
                    <option value="Indian" <?php echo ($item['category'] == 'Indian') ? 'selected' : ''; ?>>Indian</option>
                    <option value="Chinese" <?php echo ($item['category'] == 'Chinese') ? 'selected' : ''; ?>>Chinese</option>
                    <option value="Pasta & Rolls" <?php echo ($item['category'] == 'Pasta & Rolls') ? 'selected' : ''; ?>>Pasta & Rolls</option>
                    <option value="Burgers & Sandwiches" <?php echo ($item['category'] == 'Burgers & Sandwiches') ? 'selected' : ''; ?>>Burgers & Sandwiches</option>
                    <option value="Shakes & Mocktails" <?php echo ($item['category'] == 'Shakes & Mocktails') ? 'selected' : ''; ?>>Shakes & Mocktails</option>
                    <option value="Other" <?php echo ($item['category'] == 'Other') ? 'selected' : ''; ?>>Other</option>
                </select>

                <label for="image_url">Image URL (Optional):</label>
                <input type="text" id="image_url" name="image_url" value="<?php echo htmlspecialchars($item['image_url']); ?>">

                <label>
                    <input type="checkbox" id="is_available" name="is_available" <?php echo $item['is_available'] ? 'checked' : ''; ?>>
                    Available
                </label>
                <br>
                <button type="submit">Update Item</button>
            </form>
        <?php else: ?>
            <p>Error: Menu item not found or invalid ID.</p>
        <?php endif; ?>
        <a href="admin_dashboard.php" class="back-link">Back to Admin Dashboard</a>
    </div>
</body>
</html>