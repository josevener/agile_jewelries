<?php
require_once '../config/database.php';
require_once 'auth.php';
checkAuth();

try {
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';

    $query = 'SELECT * FROM orders';
    if ($search !== '') {
        $query .= ' WHERE customer_name LIKE ? 
                OR phone_number LIKE ? 
                OR address LIKE ? 
                OR order_status LIKE ? 
                OR barangay LIKE ? 
                OR city LIKE ? 
                OR province LIKE ?';
    }
    $query .= ' ORDER BY id DESC';

    $stmt = $pdo->prepare($query);

    if ($search !== '') {
        $searchParam = "%$search%";
        $values = array_fill(0, 6, $searchParam);
        $stmt->execute($values);
    } else {
        $stmt->execute();
    }

    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $orders = array_map(function ($o) {
        $o['men_set']   = $o['men_set'] ? 'Yes' : 'No';
        $o['women_set'] = $o['women_set'] ? 'Yes' : 'No';
        return $o;
    }, $orders);

    header('Content-Type: application/json');
    echo json_encode($orders);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
