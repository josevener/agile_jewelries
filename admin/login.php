<?php
session_start();
require_once __DIR__ . "../../config/database.php";

// Handle POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usernameOrEmail = trim($_POST['username'] ?? '');
    $password        = $_POST['password'] ?? '';

    if (empty($usernameOrEmail) || empty($password)) {
        $_SESSION['error'] = "Please enter username/email and password.";
        header("Location: login.php");
        exit;
    }

    // Fetch user
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :user OR email = :user LIMIT 1");
    $stmt->execute([':user' => $usernameOrEmail]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        if ($user['status'] !== 'active') {
            $_SESSION['error'] = "Your account is {$user['status']}. Contact admin.";
            header("Location: login.php");
            exit;
        }

        // Update last login
        $update = $pdo->prepare("UPDATE users SET last_login = NOW() WHERE id = :id");
        $update->execute([':id' => $user['id']]);

        // Store session
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['username']  = $user['username'];
        $_SESSION['role']      = $user['role'];
        $_SESSION['logged_in'] = true;

        // Redirect by role
        if ($user['role'] === 'admin') {
            header("Location: admin/dashboard.php");
        } else {
            header("Location: index.php");
        }
        exit;
    } else {
        $_SESSION['error'] = "Invalid username/email or password.";
        header("Location: login.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-gradient-to-br from-teal-500 via-emerald-500 to-cyan-600 flex items-center justify-center p-4">

  <div class="bg-white shadow-2xl rounded-2xl w-full max-w-md p-8">
    <h2 class="text-3xl font-bold text-center text-gray-800 mb-6">Login</h2>

    <?php if (!empty($_SESSION['error'])): ?>
      <div class="mb-4 p-3 text-sm text-red-700 bg-red-100 rounded-lg">
        <?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
      </div>
    <?php endif; ?>

    <form class="space-y-5" method="POST" action="login.php">
      <div>
        <label class="block text-gray-600 text-sm mb-2">Username or Email</label>
        <input type="text" name="username" placeholder="Enter your username or email"
          class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-teal-400 outline-none" required>
      </div>
      <div>
        <label class="block text-gray-600 text-sm mb-2">Password</label>
        <input type="password" name="password" placeholder="Enter your password"
          class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-teal-400 outline-none" required>
      </div>
      <button type="submit"
        class="w-full py-2 px-4 bg-teal-600 hover:bg-teal-700 text-white rounded-lg shadow-md transition">Login</button>
      <p class="text-sm text-center text-gray-600">Don’t have an account?
        <a href="register.php" class="text-teal-600 hover:underline">Register</a>
      </p>
    </form>
  </div>

</body>
</html>
