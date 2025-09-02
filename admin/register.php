<?php
session_start();
require_once __DIR__ . "../../config/database.php";

// Handle POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name  = trim($_POST['first_name'] ?? '');
    $middle_name = trim($_POST['middle_name'] ?? '');
    $last_name   = trim($_POST['last_name'] ?? '');
    $email       = trim($_POST['email'] ?? '');
    $phone       = trim($_POST['phone_number'] ?? '');
    $username    = trim($_POST['username'] ?? '');
    $password    = $_POST['password'] ?? '';

    // Validation
    if (empty($first_name) || empty($last_name) || empty($email) || empty($username) || empty($password)) {
        $_SESSION['error'] = "⚠️ Please fill in all required fields.";
        header("Location: register.php");
        exit;
    }

    // Check duplicate username/email
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email OR username = :username");
    $stmt->execute([':email' => $email, ':username' => $username]);
    if ($stmt->fetch()) {
        $_SESSION['error'] = "⚠️ Username or Email already exists.";
        header("Location: register.php");
        exit;
    }

    // Hash password
    $password_hash = password_hash($password, PASSWORD_BCRYPT);

    // Insert user (default role = customer, status = active)
    $stmt = $pdo->prepare("
        INSERT INTO users (first_name, middle_name, last_name, email, phone_number, username, password_hash, role, status) 
        VALUES (:first_name, :middle_name, :last_name, :email, :phone, :username, :password_hash, 'customer', 'active')
    ");

    try {
        $stmt->execute([
            ':first_name'   => $first_name,
            ':middle_name'  => $middle_name,
            ':last_name'    => $last_name,
            ':email'        => $email,
            ':phone'        => $phone,
            ':username'     => $username,
            ':password_hash'=> $password_hash
        ]);

        $_SESSION['success'] = "✅ Registration successful. Please log in.";
        header("Location: login.php");
        exit;

    } catch (PDOException $e) {
        $_SESSION['error'] = "❌ Registration failed. Please try again.";
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

<body class="min-h-screen bg-gradient-to-br from-cyan-500 via-blue-600 to-indigo-700 flex items-center justify-center p-4">

  <div class="bg-white shadow-2xl rounded-2xl w-full max-w-lg p-8">
    <h2 class="text-3xl font-bold text-center text-gray-800 mb-6">Create an Account</h2>

    <!-- Session Alerts -->
    <?php if (!empty($_SESSION['error'])): ?>
      <div class="mb-4 p-3 text-sm text-red-700 bg-red-100 rounded-lg border border-red-300">
        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
      </div>
    <?php endif; ?>

    <?php if (!empty($_SESSION['success'])): ?>
      <div class="mb-4 p-3 text-sm text-green-700 bg-green-100 rounded-lg border border-green-300">
        <?= $_SESSION['success']; unset($_SESSION['success']); ?>
      </div>
    <?php endif; ?>

    <form class="space-y-5" method="POST" action="register.php">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
        <div>
          <label class="block text-gray-600 text-sm mb-2">First Name</label>
          <input type="text" name="first_name" placeholder="First name"
            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-cyan-400 outline-none" required>
        </div>
        <div>
          <label class="block text-gray-600 text-sm mb-2">Middle Name</label>
          <input type="text" name="middle_name" placeholder="Middle name"
            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-cyan-400 outline-none">
        </div>
        <div>
          <label class="block text-gray-600 text-sm mb-2">Last Name</label>
          <input type="text" name="last_name" placeholder="Last name"
            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-cyan-400 outline-none" required>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        <div>
          <label class="block text-gray-600 text-sm mb-2">Email</label>
          <input type="email" name="email" placeholder="Enter your email"
            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-cyan-400 outline-none" required>
        </div>
        <div>
          <label class="block text-gray-600 text-sm mb-2">Phone Number</label>
          <input type="text" name="phone_number" placeholder="Phone number"
            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-cyan-400 outline-none">
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        <div>
          <label class="block text-gray-600 text-sm mb-2">Username</label>
          <input type="text" name="username" placeholder="Choose a username"
            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-cyan-400 outline-none" required>
        </div>
        <div>
          <label class="block text-gray-600 text-sm mb-2">Password</label>
          <input type="password" name="password" placeholder="Create a password"
            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-cyan-400 outline-none" required>
        </div>
      </div>

      <button type="submit"
        class="w-full py-2 px-4 bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-600 hover:to-blue-700 text-white font-semibold rounded-lg shadow-lg transition">
        Register
      </button>
      <p class="text-sm text-center text-gray-600 mt-4">Already have an account?
        <a href="login.php" class="text-cyan-600 hover:underline">Login</a>
      </p>
    </form>
  </div>

</body>
</html>
