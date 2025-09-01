<?php
// admin/api/get_orders.php
header('Content-Type: application/json');
require_once '../../config/database.php';

try {
    // Fetch all orders
    $stmt = $pdo->query('SELECT * FROM orders ORDER BY id DESC');
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Format boolean fields for readability
    foreach ($orders as &$order) {
        $order['men_set'] = $order['men_set'] ? 'Yes' : 'No';
        $order['women_set'] = $order['women_set'] ? 'Yes' : 'No';
    }

    echo json_encode([
        'success' => true,
        'orders' => $orders
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Database error: ' . $e->getMessage()
    ]);
}
