<?php

require_once '../config/database.php';
require_once 'auth.php';
checkAuth();

// Fetch dynamic dashboard metric (total_orders)
try {
    $stmt = $pdo->query("
        SELECT 
            COUNT(*) AS total_orders,
            SUM(CASE WHEN men_set = 1 OR women_set = 1 THEN 1 ELSE 0 END) AS sets_count,
            SUM(CASE WHEN men_set = 1 OR women_set = 1 THEN 1 ELSE 0 END) * 999 AS total_amount
        FROM orders
    ");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    $total_orders = $result['total_orders'];
    $sets_count   = $result['sets_count'];
    $revenue      = $result['total_amount'];
} catch (PDOException $e) {
    $error = 'Database error: ' . $e->getMessage();
}
// Static revenue since total_amount column is missing
$total_users = 320;
$total_products = 45;
$recent_activities = [
    [
        'type' => 'user',
        'description' => 'New user registered: <strong>Emily Davis</strong>',
        'timestamp' => '2025-09-01 14:00:00'
    ],
    [
        'type' => 'order',
        'description' => 'New order placed: <strong>Order #1234</strong>',
        'timestamp' => '2025-09-01 13:00:00'
    ]
];
?>

<!DOCTYPE html>
<html lang="en" class="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agile Jewelries - Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3b82f6',
                        secondary: '#1f2937',
                        danger: '#ef4444',
                        success: '#10b981',
                        warning: '#f59e0b',
                    }
                }
            }
        }
    </script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="js/scripts.js" defer></script>
</head>

<body class="bg-gray-100 font-sans antialiased dark:bg-gray-900 dark:text-gray-200 transition-colors duration-200">
    <div class="flex h-screen overflow-hidden">
        <?php include 'includes/sidebar.php'; ?>
        <div class="flex-1 flex flex-col">
            <?php include 'includes/header.php'; ?>
            <main class="flex-1 p-6 overflow-y-auto" role="main">
                <div id="dashboard" class="page-content space-y-6">
                    <?php if (isset($error)): ?>
                        <div class="text-danger text-sm"><?php echo htmlspecialchars($error); ?></div>
                    <?php else: ?>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md flex items-center gap-4">
                                <i data-lucide="shopping-cart" class="w-8 h-8 text-primary"></i>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Total Orders</h3>
                                    <p class="text-3xl font-bold text-primary"><?php echo htmlspecialchars($total_orders); ?></p>
                                    <p class="text-sm text-success">+5% from last month</p>
                                </div>
                            </div>
                            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md flex items-center gap-4">
                                <i data-lucide="users" class="w-8 h-8 text-primary"></i>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Total Users</h3>
                                    <p class="text-3xl font-bold text-primary"><?php echo htmlspecialchars($total_users); ?></p>
                                    <p class="text-sm text-success">+10% from last month</p>
                                </div>
                            </div>
                            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md flex items-center gap-4">
                                <i data-lucide="package" class="w-8 h-8 text-primary"></i>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Total Products</h3>
                                    <p class="text-3xl font-bold text-primary"><?php echo htmlspecialchars($total_products); ?></p>
                                    <p class="text-sm text-danger">-2% from last month</p>
                                </div>
                            </div>
                            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md flex items-center gap-4">
                                <i data-lucide="banknote" class="w-8 h-8 text-primary"></i>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Revenue</h3>
                                    <p class="text-3xl font-bold text-primary"><?php echo number_format($revenue, 2); ?></p>
                                    <p class="text-sm text-success">+8% from last month</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4">Sales Overview</h2>
                            <canvas id="sales-chart" class="w-full h-64"></canvas>
                        </div>
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4">Recent Activity</h2>
                            <ul class="space-y-4">
                                <?php foreach ($recent_activities as $activity): ?>
                                    <li class="flex items-center gap-3">
                                        <i data-lucide="<?php echo $activity['type'] === 'user' ? 'user-plus' : 'shopping-cart'; ?>" class="w-5 h-5 text-primary"></i>
                                        <span><?php echo $activity['description']; ?></span>
                                        <span class="text-sm text-gray-500 dark:text-gray-400"><?php echo htmlspecialchars($activity['timestamp']); ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>
            </main>
        </div>
    </div>
</body>

</html>