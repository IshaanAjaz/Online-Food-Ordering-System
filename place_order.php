<?php
// place_order.php
// Handles placing a new order from the customer's cart.

require_once 'db_connect.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $response['message'] = 'User not logged in.';
    echo json_encode($response);
    exit();
}

$user_id = $_SESSION['user_id'];

// Get raw POST data
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    $response['message'] = 'Invalid JSON input.';
    echo json_encode($response);
    exit();
}

$delivery_address = $data['delivery_address'] ?? '';
$contact_number = $data['contact_number'] ?? '';
$cart_items = $data['items'] ?? [];

if (empty($cart_items)) {
    $response['message'] = 'Cart is empty. Cannot place an empty order.';
    echo json_encode($response);
    exit();
}

// Calculate total amount
$total_amount = 0;
foreach ($cart_items as $item) {
    $total_amount += $item['price'] * $item['quantity'];
}

// Start transaction
$conn->begin_transaction();

try {
    // Insert into orders table
    $stmt_order = $conn->prepare("INSERT INTO orders (user_id, total_amount, delivery_address, contact_number, status) VALUES (?, ?, ?, ?, 'pending')");
    $stmt_order->bind_param("idss", $user_id, $total_amount, $delivery_address, $contact_number);
    $stmt_order->execute();
    $order_id = $conn->insert_id;
    $stmt_order->close();

    // Insert into order_items table
    $stmt_order_item = $conn->prepare("INSERT INTO order_items (order_id, menu_item_id, quantity, price_at_order) VALUES (?, ?, ?, ?)");
    foreach ($cart_items as $item) {
        $stmt_order_item->bind_param("iiid", $order_id, $item['id'], $item['quantity'], $item['price']);
        $stmt_order_item->execute();
    }
    $stmt_order_item->close();

    // Commit transaction
    $conn->commit();
    $response['success'] = true;
    $response['message'] = 'Order placed successfully!';
    $response['order_id'] = $order_id;

} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();
    $response['message'] = 'Error placing order: ' . $e->getMessage();
}

$conn->close();
echo json_encode($response);
?>