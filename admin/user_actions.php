<?php
require_once '../config/database.php';
require_once 'auth.php';
checkAuth();

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
if (!$data) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$action = $data['action'] ?? null;
$id = $data['id'] ?? null;
$first_name = trim($data['first_name'] ?? '');
$middle_name = trim($data['middle_name'] ?? '');
$last_name = trim($data['last_name'] ?? '');
$email = trim($data['email'] ?? '');
$password = trim($data['password'] ?? '');
$phone_number = trim($data['phone_number'] ?? '');
$status = $data['status'] ?? 'active';
$currentUserId = $_SESSION['user_id'] ?? null;

try {
    if ($action === 'delete') {
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'User ID is required for deletion.']);
            exit;
        }

        // Prevent deleting own account
        if ($id == $currentUserId) {
            echo json_encode(['success' => false, 'message' => 'You cannot delete your own account.']);
            exit;
        }

        // Check if user exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE id = ?");
        $stmt->execute([$id]);
        if (!$stmt->fetch()) {
            echo json_encode(['success' => false, 'message' => 'User not found.']);
            exit;
        }

        // Delete user
        $stmt = $pdo->prepare("UPDAtE users set STATUS = 'inactive' WHERE id = ?");
        $stmt->execute([$id]);
        echo json_encode(['success' => true, 'message' => 'User deleted successfully']);
        exit;
    }

    if ($first_name === '' || $last_name === '' || $email === '') {
        echo json_encode(['success' => false, 'message' => 'First name, last name, and email are required.']);
        exit;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Invalid email format.']);
        exit;
    }

    // Password validation regex
    function isValidPassword($pwd)
    {
        return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $pwd);
    }

    if ($id) {
        // Prevent editing own account
        if ($id == $currentUserId) {
            echo json_encode(['success' => false, 'message' => 'You cannot edit your own account.']);
            exit;
        }

        // Check for duplicate email excluding current user
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt->execute([$email, $id]);
        if ($stmt->fetch()) {
            echo json_encode(['success' => false, 'message' => 'Email is already taken by another user.']);
            exit;
        }

        // Update existing (no password update)
        $stmt = $pdo->prepare("UPDATE users 
                               SET first_name = ?, middle_name = ?, last_name = ?, email = ?, phone_number = ?, STATUS = ? 
                               WHERE id = ?");
        $stmt->execute([$first_name, $middle_name, $last_name, $email, $phone_number, $status, $id]);
        echo json_encode(['success' => true, 'message' => 'User updated successfully']);
    } else {
        // Check for duplicate email before insert
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            echo json_encode(['success' => false, 'message' => 'Email is already registered.']);
            exit;
        }

        // Validate and hash password
        if ($password === '' || !isValidPassword($password)) {
            echo json_encode(['success' => false, 'message' => 'Password must have at least 8 characters, 1 uppercase, 1 lowercase, and 1 number.']);
            exit;
        }
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert new
        $stmt = $pdo->prepare("INSERT INTO users (first_name, middle_name, last_name, email, password_hash, phone_number, STATUS) 
                               VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$first_name, $middle_name, $last_name, $email, $hashedPassword, $phone_number, $status]);
        echo json_encode(['success' => true, 'message' => 'User created successfully']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
