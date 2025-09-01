<?php
header('Content-Type: application/json');

include_once 'config/database.php';

try {

    // Get POST data
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
    $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);
    $province = filter_input(INPUT_POST, 'province', FILTER_SANITIZE_STRING);
    $city = filter_input(INPUT_POST, 'city', FILTER_SANITIZE_STRING);
    $barangay = filter_input(INPUT_POST, 'barangay', FILTER_SANITIZE_STRING);
    $menSet = filter_input(INPUT_POST, 'menSet', FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
    $womenSet = filter_input(INPUT_POST, 'womenSet', FILTER_VALIDATE_BOOLEAN) ? 1 : 0;

    // Validate data
    $errors = [];
    if (empty($name)) $errors[] = 'Full Name is required.';
    if (empty($phone) || !preg_match('/^[0-9]{10,11}$/', $phone)) $errors[] = 'Valid Phone Number is required.';
    if (empty($address)) $errors[] = 'Address is required.';
    if (empty($province)) $errors[] = 'Province is required.';
    if (empty($city)) $errors[] = 'City is required.';
    if (empty($barangay)) $errors[] = 'Barangay is required.';
    if (!$menSet && !$womenSet) $errors[] = 'At least one product must be selected.';

    if (!empty($errors)) {
        echo json_encode(['success' => false, 'message' => implode(' ', $errors)]);
        exit;
    }

    // Insert into database
    $stmt = $pdo->prepare('
        INSERT INTO orders (name, phone, address, province, city, barangay, men_set, women_set)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ');
    $stmt->execute([$name, $phone, $address, $province, $city, $barangay, $menSet, $womenSet]);

    echo json_encode(['success' => true, 'message' => 'Order submitted successfully']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
