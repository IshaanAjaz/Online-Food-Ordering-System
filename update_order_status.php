<?php
// update_order_status.php
require_once 'db_connect.php';

header('Content-Type: application/json');
$response = ['success' => false, 'message' => ''];

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    $response['message'] = 'Unauthorized access.';
    echo json_encode($response);
    exit();
}

$order_id = $_POST['order_id'] ?? 0;
$new_status = $_POST['status'] ?? '';

if ($order_id <= 0 || !in_array($new_status, ['pending', 'processing', 'completed', 'cancelled'])) {
    $response['message'] = 'Invalid order ID or status.';
    echo json_encode($response);
    exit();
}

$stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
$stmt->bind_param("si", $new_status, $order_id);

if ($stmt->execute()) {
    $response['success'] = true;
    $response['message'] = 'Order status updated successfully.';
} else {
    $response['message'] = 'Error updating order status: ' . $stmt->error;
}

$stmt->close();
$conn->close();
echo json_encode($response);
?>