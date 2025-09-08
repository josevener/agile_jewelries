<?php
session_start();
include_once "../../config/database.php";

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(value: ['success' => false, 'errors' => ['Unauthorized: Please log in.']]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'errors' => ['Method not allowed.']]);
    exit;
}

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);
$current_password = $data['current_password'] ?? '';
$new_password = $data['new_password'] ?? '';
$confirm_password = $data['confirm_password'] ?? '';

$errors = [];

if (empty($current_password)) {
    $errors[] = 'Current password is required.';
}

if (empty($new_password)) {
    $errors[] = 'New password is required.';
} 
else {
    if (strlen($new_password) < 8) {
        $errors[] = 'New password must be at least 8 characters long.';
    }
    // Strength check: at least one uppercase, one lowercase, and one number
    if (!preg_match('/[A-Z]/', $new_password)) {
        $errors[] = 'New password must contain at least one uppercase letter.';
    }
    if (!preg_match('/[a-z]/', $new_password)) {
        $errors[] = 'New password must contain at least one lowercase letter.';
    }
    if (!preg_match('/[0-9]/', $new_password)) {
        $errors[] = 'New password must contain at least one number.';
    }
}

if ($new_password !== $confirm_password) {
    $errors[] = 'New password and confirmation do not match.';
}

if (!empty($errors)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'errors' => $errors]);
    exit;
}

// Fetch current user's password hash
try {
    $stmt = $pdo->prepare('SELECT password_hash FROM users WHERE id = ?');
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        http_response_code(404);
        echo json_encode(['success' => false, 'errors' => ['User not found.']]);
        exit;
    }

    // Verify current password
    if (!password_verify($current_password, $user['password_hash'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'errors' => ['Incorrect current password.']]);
        exit;
    }

    // Hash new password
    $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);

    // Update password in database
    $stmt = $pdo->prepare('UPDATE users SET password_hash = ? WHERE id = ?');
    $stmt->execute([$new_password_hash, $_SESSION['user_id']]);

    echo json_encode(['success' => true, 'message' => 'Password updated successfully.']);
} 
catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'errors' => ['Database error: ' . $e->getMessage()]]);
}
