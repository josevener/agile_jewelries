<?php
session_start();

// Check if logout is triggered properly (example: via query string ?confirm=true)
if (isset($_GET['confirm']) && $_GET['confirm'] === 'true') {
    // Destroy session
    $_SESSION = [];
    session_destroy();
    header("Location: login.php");
    exit;
} else {
    // If accessed manually, just go back to where the user came from
    $redirect = $_SERVER['HTTP_REFERER'] ?? 'dashboard.php';
    header("Location: " . $redirect);
    exit;
}
