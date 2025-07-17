<?php
// delete_order.php
// Handles deletion of confirmed or cancelled orders by an admin.

require_once 'db_connect.php';

header('Content-Type: application/json');
$response = ['success' => false, 'message' => ''];

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    $response['message'] = 'Unauthorized access. Admin privileges required.';
    echo json_encode($response);
    exit();
}

$order_id = $_POST['order_id'] ?? 0;

if ($order_id <= 0) {
    $response['message'] = 'Invalid order ID provided.';
    echo json_encode($response);
    exit();
}

// Start transaction for atomicity
$conn->begin_transaction();

try {
    // First, check the status of the order to ensure it's 'completed' or 'cancelled'
    $stmt_check = $conn->prepare("SELECT status FROM orders WHERE id = ?");
    $stmt_check->bind_param("i", $order_id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    $order = $result_check->fetch_assoc();
    $stmt_check->close();

    if (!$order) {
        throw new Exception("Order not found.");
    }

    $order_status = $order['status'];
    if ($order_status !== 'completed' && $order_status !== 'cancelled') {
        throw new Exception("Only 'completed' or 'cancelled' orders can be deleted. Current status: " . $order_status);
    }

    // Delete related order_items first due to foreign key constraint
    $stmt_delete_items = $conn->prepare("DELETE FROM order_items WHERE order_id = ?");
    $stmt_delete_items->bind_param("i", $order_id);
    $stmt_delete_items->execute();
    $stmt_delete_items->close();

    // Now delete the order itself
    $stmt_delete_order = $conn->prepare("DELETE FROM orders WHERE id = ?");
    $stmt_delete_order->bind_param("i", $order_id);
    $stmt_delete_order->execute();
    $stmt_delete_order->close();

    $conn->commit();
    $response['success'] = true;
    $response['message'] = 'Order ID ' . $order_id . ' and its items deleted successfully.';

} catch (Exception $e) {
    $conn->rollback();
    $response['message'] = 'Error deleting order: ' . $e->getMessage();
}

$conn->close();
echo json_encode($response);
?>