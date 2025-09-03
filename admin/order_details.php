<?php
header('Content-Type: application/json');
require_once '../config/database.php';

if (!isset($_GET['id'])) {
    echo json_encode(["error" => "Missing order ID"]);
    exit;
}

$orderId = intval($_GET['id']);
$stmt = $pdo->prepare("SELECT id, customer_name, phone_number, address, province, city, barangay, men_set, women_set  
                       FROM orders WHERE id = ?");
$stmt->execute([$orderId]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if ($order) {
    echo json_encode($order);
} else {
    echo json_encode(["error" => "Order not found"]);
}
exit;
