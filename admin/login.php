<?php
session_start();
require_once __DIR__ . "/../config/database.php";

// If already logged in, redirect back
if (!empty($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true) {
  $redirect = $_SESSION['redirect_to'] ?? 'analytics.php';
  unset($_SESSION['redirect_to']);
  header("Location: {$redirect}");
  exit;
}

// Handle POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
  $input = json_decode(file_get_contents('php://input'), true);
  $email = trim($input['email'] ?? '');
  $password = $input['password'] ?? '';

  $response = ['success' => false, 'errors' => [], 'email' => $email];

  if (empty($email)) {
    $response['errors'][] = 'Email is required.';
  }
  if (empty($password)) {
    $response['errors'][] = 'Password is required.';
  }

  if (empty($response['errors'])) {
    try {
      // Fetch user
      $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND STATUS = 'active' LIMIT 1");
      $stmt->execute([$email]);
      $user = $stmt->fetch();

      if ($user && password_verify($password, $user['password_hash'])) {
        // Update last login
        $update = $pdo->prepare("UPDATE users SET last_login = NOW() WHERE id = :id");
        $update->execute([':id' => $user['id']]);

        // Store session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['full_name'] = $user['first_name'] . ' ' . $user['last_name'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['is_logged_in'] = true;
        $_SESSION['logout_csrf_token'] = bin2hex(random_bytes(32)); // Generate CSRF token
        error_log("CSRF token set in login.php: " . $_SESSION['logout_csrf_token']);

        $response['success'] = true;
        $response['message'] = 'Login successful.';
      } else {
        $response['errors'][] = 'Invalid email or password.';
      }
    } catch (PDOException $e) {
      $response['errors'][] = 'Database error: ' . $e->getMessage();
    }
  }

  header('Content-Type: application/json');
  echo json_encode($response);
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Agile Jewelries — Login</title>
  <link rel="stylesheet" href="../css/output.css">
  <link rel="stylesheet" href="../assets/fontawesome/css/all.min.css">
  <style>
    body {
      background-color: #f9fafb;
      background-image: url('../assets/agile_bg.webp');
    }
  </style>
</head>

<body class="min-h-screen flex items-center justify-center p-4 bg-cover bg-center">
  <div class="bg-white shadow-2xl rounded-2xl w-full max-w-md p-8">
    <h2 class="text-3xl font-bold text-center text-gray-800 mb-6">Login</h2>
    <div id="error-messages" class="hidden mb-4 p-3 text-sm text-red-700 bg-red-100 rounded-lg"></div>
    <div class="space-y-5">
      <div>
        <label class="block text-gray-600 text-sm mb-2" for="email">Email</label>
        <input id="email" type="text" name="email" placeholder="Enter your email"
          class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-teal-400 outline-none" required>
      </div>
      <div>
        <label class="block text-gray-600 text-sm mb-2" for="password">Password</label>
        <div class="relative">
          <input id="password" type="password" name="password" placeholder="Enter your password"
            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-teal-400 outline-none" required>
          <button type="button" id="toggle-password" class="absolute inset-y-0 right-2 flex items-center text-gray-600">
            <i class="fa-solid fa-eye"></i>
          </button>
        </div>
      </div>
      <button id="login-btn" type="button"
        class="w-full py-2 px-4 bg-teal-600 hover:bg-teal-700 text-white rounded-lg shadow-md transition">
        Login
      </button>
    </div>
  </div>

  <!-- Loading Modal -->
  <div id="loading-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded-lg w-full max-w-sm text-center">
      <div class="flex items-center justify-center mb-4">
        <i class="fa-solid fa-spinner animate-spin text-teal-600 mr-2"></i>
        <p class="text-sm text-teal-700">Logging in...</p>
      </div>
    </div>
  </div>

  <script>
    (function() {
      function showLoadingModal() {
        const loadingModal = document.getElementById("loading-modal");
        if (loadingModal) {
          loadingModal.classList.remove("hidden");
        }
      }

      function hideLoadingModal() {
        const loadingModal = document.getElementById("loading-modal");
        if (loadingModal) {
          loadingModal.classList.add("hidden");
        }
      }

      function showErrors(errors) {
        const errorDiv = document.getElementById("error-messages");
        if (errorDiv) {
          errorDiv.innerHTML = "<ul class='list-disc pl-5'>" + errors.map(err => `<li>${err}</li>`).join("") + "</ul>";
          errorDiv.classList.remove("hidden");
        }
      }

      function clearErrors() {
        const errorDiv = document.getElementById("error-messages");
        if (errorDiv) {
          errorDiv.innerHTML = "";
          errorDiv.classList.add("hidden");
        }
      }

      const loginBtn = document.getElementById('login-btn');
      const emailInput = document.getElementById('email');
      const passwordInput = document.getElementById('password');
      const togglePassword = document.getElementById('toggle-password');

      if (togglePassword && passwordInput) {
        togglePassword.addEventListener('click', () => {
          const isPassword = passwordInput.type === 'password';
          passwordInput.type = isPassword ? 'text' : 'password';
          togglePassword.innerHTML = isPassword ? '<i class="fa-solid fa-eye-slash"></i>' : '<i class="fa-solid fa-eye"></i>';
        });
      }

      async function handleLogin() {
        const email = emailInput.value.trim();
        const password = passwordInput.value.trim();

        const errors = [];
        if (!email) errors.push("Email is required.");
        if (!password) errors.push("Password is required.");

        if (errors.length > 0) {
          showErrors(errors);
          return;
        }

        clearErrors();
        loginBtn.disabled = true;
        loginBtn.innerHTML = '<i class="fa-solid fa-spinner animate-spin mr-2"></i> Logging in...';
        showLoadingModal();
        try {
          const response = await fetch('login.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({
              email,
              password
            })
          });

          const data = await response.json();
          if (data.success) {
            window.location.href = 'analytics.php';
          } else {
            passwordInput.value = ''; // Clear password only
            emailInput.value = data.email || email; // Preserve email
            showErrors(data.errors);
          }
        } catch (err) {
          passwordInput.value = ''; // Clear password only
          emailInput.value = email; // Preserve email
          showErrors(["Network error: Could not connect to the server. Please try again later."]);
        } finally {
          hideLoadingModal();
          loginBtn.disabled = false;
          loginBtn.innerHTML = 'Login';
        }
      }

      if (loginBtn && emailInput && passwordInput) {
        loginBtn.addEventListener('click', handleLogin);
        emailInput.addEventListener('keypress', (e) => {
          if (e.key === 'Enter') {
            handleLogin();
          }
        });
        passwordInput.addEventListener('keypress', (e) => {
          if (e.key === 'Enter') {
            handleLogin();
          }
        });
      }
    })();
  </script>
</body>

</html>