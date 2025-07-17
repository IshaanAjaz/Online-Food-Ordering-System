<?php
// admin_dashboard.php
// Admin panel to manage menu items and view orders.

require_once 'db_connect.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$menu_items = [];
$orders = [];

// Fetch menu items
$sql_menu = "SELECT id, name, description, price, category, is_available FROM menu_items ORDER BY category, name";
$result_menu = $conn->query($sql_menu);
if ($result_menu->num_rows > 0) {
    while ($row = $result_menu->fetch_assoc()) {
        $menu_items[] = $row;
    }
}

// Fetch orders
$sql_orders = "SELECT o.id, u.username, o.order_date, o.total_amount, o.status, o.delivery_address, o.contact_number
               FROM orders o JOIN users u ON o.user_id = u.id
               ORDER BY o.order_date DESC";
$result_orders = $conn->query($sql_orders);
if ($result_orders->num_rows > 0) {
    while ($row = $result_orders->fetch_assoc()) {
        $orders[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - SpiceVilla</title>
    <link rel="stylesheet" href="spicevilla.css">
    <link rel="stylesheet" href="about.css">
    <style>
        body {
            background-color: #000000e7;
            color: aliceblue;
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            padding: 20px;
        }
        .admin-container {
            max-width: 1200px;
            margin: 20px auto;
            background-color: rgba(0, 0, 0, 0.7);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px gold;
        }
        .admin-container h1, .admin-container h2 {
            color: gold;
            text-align: center;
            margin-bottom: 25px;
        }
        .admin-actions {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 40px;
        }
        .admin-actions .btn {
            background-color: gold;
            color: black;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
        .admin-actions .btn:hover {
            background-color: #ffd700;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
        }
        table, th, td {
            border: 1px solid gold;
            color: aliceblue;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: rgba(255, 215, 0, 0.2);
            color: gold;
        }
        tr:nth-child(even) {
            background-color: rgba(255, 255, 255, 0.05);
        }
        .action-buttons a, .action-buttons button { /* Added button to selector */
            background-color: #4CAF50;
            color: white;
            padding: 5px 10px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            border-radius: 4px;
            margin-right: 5px;
            border: none; /* Ensure buttons have no default border */
            cursor: pointer;
        }
        .action-buttons a.edit, .action-buttons button.edit { background-color: #008CBA; }
        .action-buttons a.delete, .action-buttons button.delete { background-color: #f44336; }
        .action-buttons a:hover, .action-buttons button:hover { opacity: 0.8; }

        .status-pending { color: orange; font-weight: bold; }
        .status-processing { color: lightblue; font-weight: bold; }
        .status-completed { color: lightgreen; font-weight: bold; }
        .status-cancelled { color: red; font-weight: bold; }
    </style>
</head>
<body>
    <div class="admin-container">
        <h1>Welcome, Admin <?php echo $_SESSION['username']; ?>!</h1>
        <div class="admin-actions">
            <a href="add_menu_item.php" class="btn">Add New Menu Item</a>
            <a href="logout.php" class="btn">Logout</a>
        </div>

        <h2>Manage Menu Items</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Category</th>
                    <th>Available</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($menu_items)): ?>
                    <tr><td colspan="7">No menu items found.</td></tr>
                <?php else: ?>
                    <?php foreach ($menu_items as $item): ?>
                        <tr>
                            <td><?php echo $item['id']; ?></td>
                            <td><?php echo htmlspecialchars($item['name']); ?></td>
                            <td><?php echo htmlspecialchars($item['description']); ?></td>
                            <td>&#8377;<?php echo number_format($item['price'], 2); ?></td>
                            <td><?php echo htmlspecialchars($item['category']); ?></td>
                            <td><?php echo $item['is_available'] ? 'Yes' : 'No'; ?></td>
                            <td class="action-buttons">
                                <a href="edit_menu_item.php?id=<?php echo $item['id']; ?>" class="edit">Edit</a>
                                <a href="delete_menu_item.php?id=<?php echo $item['id']; ?>" class="delete" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <h2>Customer Orders</h2>
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Order Date</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                    <th>Delivery Address</th>
                    <th>Contact Number</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($orders)): ?>
                    <tr><td colspan="8">No orders found.</td></tr>
                <?php else: ?>
                    <?php foreach ($orders as $order): ?>
                        <tr id="order-row-<?php echo $order['id']; ?>"> <!-- Added ID for easy removal -->
                            <td><?php echo $order['id']; ?></td>
                            <td><?php echo htmlspecialchars($order['username']); ?></td>
                            <td><?php echo $order['order_date']; ?></td>
                            <td>&#8377;<?php echo number_format($order['total_amount'], 2); ?></td>
                            <td>
                                <select class="order-status-select" data-order-id="<?php echo $order['id']; ?>">
                                    <option value="pending" <?php echo ($order['status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
                                    <option value="processing" <?php echo ($order['status'] == 'processing') ? 'selected' : ''; ?>>Processing</option>
                                    <option value="completed" <?php echo ($order['status'] == 'completed') ? 'selected' : ''; ?>>Completed</option>
                                    <option value="cancelled" <?php echo ($order['status'] == 'cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                                </select>
                            </td>
                            <td><?php echo htmlspecialchars($order['delivery_address']); ?></td>
                            <td><?php echo htmlspecialchars($order['contact_number']); ?></td>
                            <td class="action-buttons">
                                <a href="view_order_details.php?id=<?php echo $order['id']; ?>" class="edit">View Details</a>
                                <button class="update-status-btn edit" data-order-id="<?php echo $order['id']; ?>">Update</button>
                                <?php if ($order['status'] === 'completed' || $order['status'] === 'cancelled'): ?>
                                    <button class="delete-order-btn delete" data-order-id="<?php echo $order['id']; ?>">Delete</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script>
        // JavaScript for updating order status
        document.querySelectorAll('.update-status-btn').forEach(button => {
            button.addEventListener('click', async (event) => {
                const orderId = event.target.dataset.orderId;
                const statusSelect = document.querySelector(`.order-status-select[data-order-id="${orderId}"]`);
                const newStatus = statusSelect.value;

                if (confirm(`Are you sure you want to change the status of Order ID ${orderId} to "${newStatus}"?`)) {
                    try {
                        const formData = new FormData();
                        formData.append('order_id', orderId);
                        formData.append('status', newStatus);

                        const response = await fetch('update_order_status.php', {
                            method: 'POST',
                            body: formData
                        });

                        const result = await response.json();

                        if (result.success) {
                            alert(result.message);
                            // Update the status text on the page without a full reload
                            const statusCell = statusSelect.closest('td');
                            statusCell.innerHTML = `<span class="status-${newStatus.toLowerCase()}">${newStatus.charAt(0).toUpperCase() + newStatus.slice(1)}</span>`;
                            // Re-enable the select and update button if needed, or simply refresh the row
                            // For simplicity, we'll just reload the page to reflect the delete button change
                            location.reload();
                        } else {
                            alert('Error: ' + result.message);
                        }
                    } catch (error) {
                        console.error('Error updating order status:', error);
                        alert('An error occurred while updating order status.');
                    }
                }
            });
        });

        // JavaScript for deleting orders
        document.querySelectorAll('.delete-order-btn').forEach(button => {
            button.addEventListener('click', async (event) => {
                const orderId = event.target.dataset.orderId;

                if (confirm(`Are you sure you want to permanently delete Order ID ${orderId}? This action cannot be undone.`)) {
                    try {
                        const formData = new FormData();
                        formData.append('order_id', orderId);

                        const response = await fetch('delete_order.php', {
                            method: 'POST',
                            body: formData
                        });

                        const result = await response.json();

                        if (result.success) {
                            alert(result.message);
                            // Remove the row from the table
                            const orderRow = document.getElementById(`order-row-${orderId}`);
                            if (orderRow) {
                                orderRow.remove();
                            }
                        } else {
                            alert('Error: ' + result.message);
                        }
                    } catch (error) {
                        console.error('Error deleting order:', error);
                        alert('An error occurred while deleting the order.');
                    }
                }
            });
        });
    </script>
</body>
</html>