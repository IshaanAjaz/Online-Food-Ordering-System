<?php
// view_orders.php
// Allows customers to view their own orders and admins to view all orders.

require_once 'db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['role'];

$orders = [];

if ($user_role === 'admin') {
    // Admin sees all orders
    $sql_orders = "SELECT o.id, u.username, o.order_date, o.total_amount, o.status, o.delivery_address, o.contact_number
                   FROM orders o JOIN users u ON o.user_id = u.id
                   ORDER BY o.order_date DESC";
    $result_orders = $conn->query($sql_orders);
} else {
    // Customer sees only their own orders
    $sql_orders = "SELECT o.id, u.username, o.order_date, o.total_amount, o.status, o.delivery_address, o.contact_number
                   FROM orders o JOIN users u ON o.user_id = u.id
                   WHERE o.user_id = ?
                   ORDER BY o.order_date DESC";
    $stmt_orders = $conn->prepare($sql_orders);
    $stmt_orders->bind_param("i", $user_id);
    $stmt_orders->execute();
    $result_orders = $stmt_orders->get_result();
}


if ($result_orders->num_rows > 0) {
    while ($row = $result_orders->fetch_assoc()) {
        $orders[] = $row;
    }
}

if (isset($stmt_orders)) {
    $stmt_orders->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo ($user_role === 'admin' ? 'All Orders' : 'My Orders'); ?> - SpiceVilla</title>
    <link rel="stylesheet" href="spicevilla.css">
    <link rel="stylesheet" href="about.css">
    <style>
        body {
            background-color: #000000e7;
            color: aliceblue;
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            padding: 20px;
        }
        .orders-container {
            max-width: 1200px;
            margin: 20px auto;
            background-color: rgba(0, 0, 0, 0.7);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px gold;
        }
        .orders-container h1 {
            color: gold;
            text-align: center;
            margin-bottom: 25px;
        }
        .orders-container .back-btn {
            background-color: gold;
            color: black;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
            transition: background-color 0.3s ease;
            margin-bottom: 20px;
            display: inline-block;
        }
        .orders-container .back-btn:hover {
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
        .status-pending { color: orange; font-weight: bold; }
        .status-processing { color: lightblue; font-weight: bold; }
        .status-completed { color: lightgreen; font-weight: bold; }
        .status-cancelled { color: red; font-weight: bold; }
    </style>
</head>
<body>
    <div class="orders-container">
        <h1><?php echo ($user_role === 'admin' ? 'All Customer Orders' : 'My Orders'); ?></h1>
        <a href="<?php echo ($user_role === 'admin' ? 'admin_dashboard.php' : 'index.php'); ?>" class="back-btn">Back to <?php echo ($user_role === 'admin' ? 'Admin Dashboard' : 'Home'); ?></a>

        <?php if (empty($orders)): ?>
            <p style="text-align: center; color: gold;">No orders found.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <?php if ($user_role === 'admin'): ?>
                            <th>Customer</th>
                        <?php endif; ?>
                        <th>Order Date</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th>Delivery Address</th>
                        <th>Contact Number</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?php echo $order['id']; ?></td>
                            <?php if ($user_role === 'admin'): ?>
                                <td><?php echo htmlspecialchars($order['username']); ?></td>
                            <?php endif; ?>
                            <td><?php echo $order['order_date']; ?></td>
                            <td>&#8377;<?php echo number_format($order['total_amount'], 2); ?></td>
                            <td><span class="status-<?php echo strtolower($order['status']); ?>"><?php echo htmlspecialchars(ucfirst($order['status'])); ?></span></td>
                            <td><?php echo htmlspecialchars($order['delivery_address']); ?></td>
                            <td><?php echo htmlspecialchars($order['contact_number']); ?></td>
                            <td><a href="view_order_details.php?id=<?php echo $order['id']; ?>" style="color: gold; text-decoration: none;">View</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>