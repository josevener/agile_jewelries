<?php
header('Content-Type: application/json');
require_once 'config/database.php';

function sanitize($field, $filter) {
    return trim(filter_input(INPUT_POST, $field, $filter));
}

try {
    // Input sanitization
    $name     = sanitize('name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $phone    = sanitize('phone', FILTER_SANITIZE_NUMBER_INT);
    $address  = sanitize('address', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $province = sanitize('province', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $city     = sanitize('city', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $barangay = sanitize('barangay', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $menSet   = filter_input(INPUT_POST, 'menSet', FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
    $womenSet = filter_input(INPUT_POST, 'womenSet', FILTER_VALIDATE_BOOLEAN) ? 1 : 0;

    // Validation
    $errors = [];
    if (!$name) $errors[] = 'Full Name is required.';
    if (!$phone || !preg_match('/^[0-9]{10,11}$/', $phone)) $errors[] = 'Valid Phone Number is required.';
    if (!$address) $errors[] = 'Address is required.';
    if (!$province) $errors[] = 'Province is required.';
    if (!$city) $errors[] = 'City is required.';
    if (!$barangay) $errors[] = 'Barangay is required.';
    if (!$menSet && !$womenSet) $errors[] = 'At least one product must be selected.';

    if ($errors) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => implode(' ', $errors)]);
        exit;
    }

    // Insert
    $stmt = $pdo->prepare('
        INSERT INTO orders (name, phone, address, province, city, barangay, men_set, women_set)
        VALUES (:name, :phone, :address, :province, :city, :barangay, :menSet, :womenSet)
    ');
    $stmt->execute([
        ':name'     => $name,
        ':phone'    => $phone,
        ':address'  => $address,
        ':province' => $province,
        ':city'     => $city,
        ':barangay' => $barangay,
        ':menSet'   => $menSet,
        ':womenSet' => $womenSet
    ]);

    echo json_encode(['success' => true, 'message' => 'Order submitted successfully']);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error.']);
}
