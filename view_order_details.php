<?php
// view_order_details.php
// Displays the details of a specific order.

require_once 'db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$order_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$order_details = null;
$order_items = [];
$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['role'];

if ($order_id > 0) {
    // Fetch order details
    $sql_order = "SELECT o.id, u.username, o.user_id, o.order_date, o.total_amount, o.status, o.delivery_address, o.contact_number
                  FROM orders o JOIN users u ON o.user_id = u.id
                  WHERE o.id = ?";
    if ($user_role !== 'admin') {
        // If not admin, ensure user can only view their own orders
        $sql_order .= " AND o.user_id = ?";
    }
    $stmt_order = $conn->prepare($sql_order);

    if ($user_role !== 'admin') {
        $stmt_order->bind_param("ii", $order_id, $user_id);
    } else {
        $stmt_order->bind_param("i", $order_id);
    }

    $stmt_order->execute();
    $result_order = $stmt_order->get_result();
    if ($result_order->num_rows > 0) {
        $order_details = $result_order->fetch_assoc();
    }
    $stmt_order->close();

    // Fetch order items if order details are found
    if ($order_details) {
        $sql_items = "SELECT mi.name, oi.quantity, oi.price_at_order
                      FROM order_items oi
                      JOIN menu_items mi ON oi.menu_item_id = mi.id
                      WHERE oi.order_id = ?";
        $stmt_items = $conn->prepare($sql_items);
        $stmt_items->bind_param("i", $order_id);
        $stmt_items->execute();
        $result_items = $stmt_items->get_result();
        while ($row = $result_items->fetch_assoc()) {
            $order_items[] = $row;
        }
        $stmt_items->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details - SpiceVilla</title>
    <link rel="stylesheet" href="spicevilla.css">
    <link rel="stylesheet" href="about.css">
    <style>
        body {
            background-color: #000000e7;
            color: aliceblue;
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            padding: 20px;
        }
        .details-container {
            max-width: 800px;
            margin: 20px auto;
            background-color: rgba(0, 0, 0, 0.7);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px gold;
        }
        .details-container h1 {
            color: gold;
            text-align: center;
            margin-bottom: 30px;
        }
        .detail-item {
            margin-bottom: 15px;
            font-size: 1.1em;
            color: aliceblue;
        }
        .detail-item strong {
            color: gold;
            display: inline-block;
            width: 150px; /* Align labels */
        }
        .order-items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }
        .order-items-table, .order-items-table th, .order-items-table td {
            border: 1px solid gold;
            color: aliceblue;
        }
        .order-items-table th, .order-items-table td {
            padding: 10px;
            text-align: left;
        }
        .order-items-table th {
            background-color: rgba(255, 215, 0, 0.2);
            color: gold;
        }
        .order-items-table tr:nth-child(even) {
            background-color: rgba(255, 255, 255, 0.05);
        }
        .status-pending { color: orange; font-weight: bold; }
        .status-processing { color: lightblue; font-weight: bold; }
        .status-completed { color: lightgreen; font-weight: bold; }
        .status-cancelled { color: red; font-weight: bold; }
        .back-btn {
            background-color: gold;
            color: black;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
            transition: background-color 0.3s ease;
            margin-top: 30px;
            display: inline-block;
        }
        .back-btn:hover {
            background-color: #ffd700;
        }
    </style>
</head>
<body>
    <div class="details-container">
        <h1>Order Details</h1>

        <?php if ($order_details): ?>
            <div class="detail-item"><strong>Order ID:</strong> <?php echo $order_details['id']; ?></div>
            <div class="detail-item"><strong>Customer:</strong> <?php echo htmlspecialchars($order_details['username']); ?></div>
            <div class="detail-item"><strong>Order Date:</strong> <?php echo $order_details['order_date']; ?></div>
            <div class="detail-item"><strong>Total Amount:</strong> &#8377;<?php echo number_format($order_details['total_amount'], 2); ?></div>
            <div class="detail-item"><strong>Status:</strong> <span class="status-<?php echo strtolower($order_details['status']); ?>"><?php echo htmlspecialchars(ucfirst($order_details['status'])); ?></span></div>
            <div class="detail-item"><strong>Delivery Address:</strong> <?php echo htmlspecialchars($order_details['delivery_address']); ?></div>
            <div class="detail-item"><strong>Contact Number:</strong> <?php echo htmlspecialchars($order_details['contact_number']); ?></div>

            <h2>Items in this Order:</h2>
            <?php if (empty($order_items)): ?>
                <p>No items found for this order.</p>
            <?php else: ?>
                <table class="order-items-table">
                    <thead>
                        <tr>
                            <th>Item Name</th>
                            <th>Quantity</th>
                            <th>Price at Order</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($order_items as $item): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['name']); ?></td>
                                <td><?php echo $item['quantity']; ?></td>
                                <td>&#8377;<?php echo number_format($item['price_at_order'], 2); ?></td>
                                <td>&#8377;<?php echo number_format($item['quantity'] * $item['price_at_order'], 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>

            <a href="<?php echo ($user_role === 'admin' ? 'admin_dashboard.php' : 'view_orders.php'); ?>" class="back-btn">Back to <?php echo ($user_role === 'admin' ? 'Admin Dashboard' : 'My Orders'); ?></a>

        <?php else: ?>
            <p style="text-align: center; color: red;">Order not found or you do not have permission to view this order.</p>
            <a href="<?php echo ($user_role === 'admin' ? 'admin_dashboard.php' : 'view_orders.php'); ?>" class="back-btn">Back to <?php echo ($user_role === 'admin' ? 'Admin Dashboard' : 'My Orders'); ?></a>
        <?php endif; ?>
    </div>
</body>
</html>