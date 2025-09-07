<?php
header('Content-Type: application/json');
require_once 'config/database.php';

function sanitize($field, $filter)
{
    return trim(filter_input(INPUT_POST, $field, $filter));
}

try {
    // Input sanitization
    $customer_name = sanitize('customer_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $phone_number = sanitize('phone_number', FILTER_SANITIZE_NUMBER_INT);
    $address = sanitize('address', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $province = sanitize('province', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $city = sanitize('city', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $barangay = sanitize('barangay', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $men_set = filter_input(INPUT_POST, 'men_set', FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
    $women_set = filter_input(INPUT_POST, 'women_set', FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
    $ip_address = sanitize('ip_address', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // Base price
    $set_price = 898;

    // Calculate amount
    $amount = ($men_set + $women_set) * $set_price;

    // Validation
    $errors = [];
    if (!filter_var($ip_address, FILTER_VALIDATE_IP)) {
        $ip_address = '::1';
    }
    if (!$customer_name) {
        $errors[] = 'Please enter your full name.';
    }
    if (!$phone_number || !preg_match('/^\+?[0-9]{10,11}$/', $phone_number)) {
        $errors[] = 'Please enter a valid phone number (10-11 digits, remove the + sign if exists).';
    }
    if (!$address) {
        $errors[] = 'Please enter your address.';
    }
    if (!$province) {
        $errors[] = 'Please enter your province.';
    }
    if (!$city) {
        $errors[] = 'Please enter your city.';
    }
    if (!$barangay) {
        $errors[] = 'Please enter your barangay.';
    }
    if (!$men_set && !$women_set) {
        $errors[] = 'Please select at least one product (Men\'s or Women\'s Set).';
    }

    if ($errors) {
        http_response_code(400);
        echo json_encode(['success' => false, 'errors' => $errors]);
        exit;
    }

    // Insert
    $stmt = $pdo->prepare('
        INSERT INTO orders (customer_name, phone_number, address, province, city, barangay, men_set, women_set, amount, ip_address)
        VALUES (:customer_name, :phone_number, :address, :province, :city, :barangay, :men_set, :women_set, :amount, :ip_address)
    ');
    $stmt->execute([
        ':customer_name' => $customer_name,
        ':phone_number' => $phone_number,
        ':address' => $address,
        ':province' => $province,
        ':city' => $city,
        ':barangay' => $barangay,
        ':men_set' => $men_set,
        ':women_set' => $women_set,
        ':amount' => $amount,
        ':ip_address' => $ip_address
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Your order has been submitted successfully!',
        'order' => [
            'customer_name' => $customer_name,
            'phone_number' => $phone_number,
            'address' => $address,
            'province' => $province,
            'city' => $city,
            'barangay' => $barangay,
            'men_set' => $men_set,
            'women_set' => $women_set,
            'amount' => $amount
        ]
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'errors' => ['Database error: ' . $e->getMessage()]]);
}
