<?php
ob_start(); // Start output buffering to capture any stray output
session_start();

// Initialize response
$response = ['success' => false, 'message' => 'Invalid request.'];

// Log session ID and CSRF token for debugging
error_log("Session ID in logout.php: " . session_id());
if (isset($_SESSION['logout_csrf_token'])) {
    error_log("CSRF token in logout.php: " . $_SESSION['logout_csrf_token']);
} else {
    error_log("CSRF token not found in session for logout.php");
}

// Check if request is POST and has correct content type
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
    // Read JSON input
    $input = json_decode(file_get_contents('php://input'), true);
    error_log("Received CSRF token: " . ($input['csrf_token'] ?? 'none'));

    if (isset($input['csrf_token']) && isset($_SESSION['logout_csrf_token']) && $input['csrf_token'] === $_SESSION['logout_csrf_token']) {
        // Valid logout request
        error_log("Session destroyed in logout.php");
        // Clear session data
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }
        // Destroy the session
        session_destroy();
        // Regenerate session ID
        session_regenerate_id(true);
        $response = ['success' => true, 'message' => 'Logged out successfully.'];
    } else {
        $response['message'] = 'Invalid CSRF token.';
    }
} else {
    // Redirect for non-POST requests (e.g., direct URL access)
    $redirect = $_SERVER['HTTP_REFERER'] ?? 'analytics.php';
    header("Location: $redirect");
    ob_end_clean(); // Clear buffer before redirect
    exit;
}

// Clear output buffer and send JSON response
ob_end_clean();
header('Content-Type: application/json');
echo json_encode($response);
exit;
