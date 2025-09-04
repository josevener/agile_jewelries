<?php
require_once '../config/database.php';
require_once 'auth.php';
checkAuth();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Read JSON input
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);

        // Accept single or multiple IDs
        $orderIds = $data['order_ids'] ?? ($data['order_id'] ?? null);
        $status = $data['status'] ?? null;

        if (!$orderIds || !$status || !in_array($status, ['processed', 'completed', 'cancelled', 'pending'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid order ID(s) or status']);
            exit;
        }

        // Normalize into array
        if (!is_array($orderIds)) {
            $orderIds = [$orderIds];
        }

        // Filter to only integers
        $orderIds = array_filter($orderIds, fn($id) => filter_var($id, FILTER_VALIDATE_INT));

        if (empty($orderIds)) {
            http_response_code(400);
            echo json_encode(['error' => 'No valid order IDs provided']);
            exit;
        }

        // Build placeholders (?, ?, ?, ...)
        $placeholders = implode(',', array_fill(0, count($orderIds), '?'));

        $stmt = $pdo->prepare("UPDATE orders SET order_status = ? WHERE id IN ($placeholders)");
        $stmt->execute(array_merge([$status], $orderIds));

        echo json_encode([
            'success' => true,
            'updated_count' => $stmt->rowCount(),
            'new_status' => $status
        ]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}
