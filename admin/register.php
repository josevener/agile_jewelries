<?php
session_start();
require_once __DIR__ . "../../config/database.php";

// Example: only allow POST requests, block direct visits
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(403); // Forbidden
    
    // Show a message before redirect
    echo "403 — Forbidden page. Redirecting to login page...";
    
    // Redirect after 2 seconds
    header("refresh:2;url=login.php");
    exit();
}

// If already logged in, redirect back
if (!empty($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true) {
  $redirect = $_SESSION['redirect_to'] ?? 'analytics.php';
  unset($_SESSION['redirect_to']);
  header("Location: {$redirect}");
  exit;
}

// Preserve inputs
$first_name = $_POST['first_name'] ?? '';
$middle_name = $_POST['middle_name'] ?? '';
$last_name = $_POST['last_name'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone_number'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $password = $_POST['password'] ?? '';
  $confirm_password = $_POST['confirm_password'] ?? '';

  // Validation
  if (empty($first_name) || empty($last_name) || empty($email) || empty($password) || empty($confirm_password)) {
    $_SESSION['error'] = "Please fill in all required fields.";
    header("Location: register.php");
    exit;
  }

  // Confirm password
  if ($password !== $confirm_password) {
    $_SESSION['error'] = "Passwords do not match.";
    header("Location: register.php");
    exit;
  }

  // Check duplicate email
  $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
  $stmt->execute([':email' => $email]);
  if ($stmt->fetch()) {
    $_SESSION['error'] = "Email already exists.";
    header("Location: register.php");
    exit;
  }

  // Hash password
  $password_hash = password_hash($password, PASSWORD_BCRYPT);

  // Insert user
  $stmt = $pdo->prepare("
        INSERT INTO users (first_name, middle_name, last_name, email, phone_number, password_hash, role, status) 
        VALUES (:first_name, :middle_name, :last_name, :email, :phone, :password_hash, 'customer', 'active')
    ");

  try {
    $stmt->execute([
      ':first_name'   => $first_name,
      ':middle_name'  => $middle_name,
      ':last_name'    => $last_name,
      ':email'        => $email,
      ':phone'        => $phone,
      ':password_hash' => $password_hash
    ]);

    $_SESSION['success'] = "Registration successful. Please log in.";
    header("Location: login.php");
    exit;
  } catch (PDOException $e) {
    $_SESSION['error'] = "Registration failed. Please try again.";
    header("Location: register.php");
    exit;
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen flex items-center justify-center p-4 bg-cover bg-center"
  style="background-image: url('../assets/agile_bg.png');">

  <div class="bg-white shadow-2xl rounded-2xl w-full max-w-2xl p-8">
    <h2 class="text-3xl font-bold text-center text-gray-800 mb-6">Create an Account</h2>

    <!-- Session Alerts -->
    <?php if (!empty($_SESSION['error'])): ?>
      <div class="mb-4 p-3 text-sm text-red-700 bg-red-100 rounded-lg border border-red-300">
        <?= $_SESSION['error'];
        unset($_SESSION['error']); ?>
      </div>
    <?php endif; ?>

    <?php if (!empty($_SESSION['success'])): ?>
      <div class="mb-4 p-3 text-sm text-green-700 bg-green-100 rounded-lg border border-green-300">
        <?= $_SESSION['success'];
        unset($_SESSION['success']); ?>
      </div>
    <?php endif; ?>

    <form class="space-y-5" method="POST" action="register.php">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        <div>
          <label class="block text-gray-600 text-sm mb-2">First Name</label>
          <input type="text" name="first_name" placeholder="First name"
            value="<?= htmlspecialchars($first_name) ?>"
            class="w-full px-4 py-2 border border-gray-600 rounded-lg focus:ring-2 focus:ring-cyan-400 outline-none" required>
        </div>
        <div>
          <label class="block text-gray-600 text-sm mb-2">Last Name</label>
          <input type="text" name="last_name" placeholder="Last name"
            value="<?= htmlspecialchars($last_name) ?>"
            class="w-full px-4 py-2 border border-gray-600 rounded-lg focus:ring-2 focus:ring-cyan-400 outline-none" required>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        <div>
          <label class="block text-gray-600 text-sm mb-2">Email</label>
          <input type="email" name="email" placeholder="Enter your email"
            value="<?= htmlspecialchars($email) ?>"
            class="w-full px-4 py-2 border border-gray-600 rounded-lg focus:ring-2 focus:ring-cyan-400 outline-none" required>
        </div>
        <div>
          <label class="block text-gray-600 text-sm mb-2">Phone Number</label>
          <input type="text" name="phone_number" placeholder="Phone number"
            value="<?= htmlspecialchars($phone) ?>"
            class="w-full px-4 py-2 border border-gray-600 rounded-lg focus:ring-2 focus:ring-cyan-400 outline-none">
        </div>
      </div>

      <!-- Password + Confirm Password with unified toggle -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        <div>
          <label class="block text-gray-600 text-sm mb-2">Password</label>
          <div class="relative">
            <input type="password" id="password" name="password" placeholder="Create a password"
              class="w-full px-4 py-2 border border-gray-600 rounded-lg focus:ring-2 focus:ring-cyan-400 outline-none pr-10" required>
          </div>
        </div>
        <div>
          <label class="block text-gray-600 text-sm mb-2">Confirm Password</label>
          <div class="relative">
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm password"
              class="w-full px-4 py-2 border border-gray-600 rounded-lg focus:ring-2 focus:ring-cyan-400 outline-none pr-10" required>
            <!-- Shared Toggle -->
            <button type="button" onclick="togglePasswords(this)"
              class="absolute inset-y-0 right-2 flex items-center text-gray-500">Show</button>
          </div>
        </div>
      </div>

      <button type="submit"
        class="w-full py-2 px-4 bg-teal-600 hover:from-teal-600 hover:to-blue-700 text-white font-semibold rounded-lg shadow-lg transition">
        Register
      </button>
      <p class="text-sm text-center text-gray-600 mt-4">Already have an account?
        <a href="login.php" class="text-teal-600 hover:underline">Login</a>
      </p>
    </form>
  </div>

  <script>
    function togglePasswords(btn) {
      const pwd = document.getElementById("password");
      const confirm = document.getElementById("confirm_password");
      if (pwd.type === "password" || confirm.type === "password") {
        pwd.type = "text";
        confirm.type = "text";
        btn.textContent = "Hide";
      } else {
        pwd.type = "password";
        confirm.type = "password";
        btn.textContent = "Show";
      }
    }
  </script>
</body>

</html>