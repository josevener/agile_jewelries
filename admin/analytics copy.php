<?php
require_once '../config/database.php';
require_once 'auth.php';
checkAuth();

try {
  $stmt = $pdo->query("
        SELECT 
            COUNT(*) AS total_orders,
            SUM(amount) AS total_amount,
            SUM(CASE WHEN men_set = 1 OR women_set = 1 THEN 1 ELSE 0 END) AS sets_count,
            SUM(CASE WHEN (order_status = 'completed') AND (men_set = 1 OR women_set = 1) THEN amount ELSE 0 END) AS revenue,
            SUM(CASE WHEN order_status = 'completed' THEN 1 ELSE 0 END) AS completed_orders,
            SUM(CASE WHEN order_status = 'pending' THEN 1 ELSE 0 END) AS pending_orders
        FROM orders
    ");
  $result = $stmt->fetch(PDO::FETCH_ASSOC);

  $total_orders = $result['total_orders'];
  $sets_count   = $result['sets_count'];
  $revenue      = $result['revenue'];
  $completed_orders = $result['completed_orders'];
  $pending_orders = $result['pending_orders'];

  // Monthly sales for chart
  $salesStmt = $pdo->query("
      SELECT 
          DATE_FORMAT(created_at, '%b') AS month,
          SUM(CASE WHEN order_status = 'completed' 
                   THEN (CASE WHEN men_set = 1 OR women_set = 1 THEN 999 ELSE 0 END) 
                   ELSE 0 END) AS monthly_revenue
      FROM orders
      WHERE YEAR(created_at) = YEAR(CURDATE())
      GROUP BY MONTH(created_at)
      ORDER BY MONTH(created_at)
  ");

  $salesData = $salesStmt->fetchAll(PDO::FETCH_ASSOC);

  $labels   = array_column($salesData, 'month');
  $revenues = array_column($salesData, 'monthly_revenue');
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
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Agile Jewelries</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/@heroicons/react/24/outline/index.js"></script>
  <link rel="stylesheet" href="../assets/fontawesome/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body class="bg-white font-sans antialiased h-screen overflow-hidden">
  <!-- Top Bar -->
  <header class="bg-white h-16 flex items-center fixed top-0 left-0 right-0 z-20">
    <?php include 'components/TopBar.php'; ?>
  </header>

  <!-- Main Content -->
  <div class="flex min-h-[calc(100vh-4rem)] mt-16">
    <!-- Sidebar -->
    <aside id="sidebar" class="bg-white w-64 lg:w-64 fixed lg:static top-16 bottom-0 left-0 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out z-10">
      <?php include 'components/SideBar.php'; ?>
    </aside>

    <!-- Work Area -->
    <main class="flex-1 bg-gray-200 rounded-t-xl overflow-auto">
      <div id="work-area" class="p-4" style="height: calc(100vh - 84px);">
        <!-- Title Bar -->
        <div class="mb-4">
          <h2 class="text-2xl font-bold text-teal-900">Analytics Overview</h2>
          <p class="text-sm text-teal-800">
            View key insights and performance metrics to monitor growth, track orders,
            and analyze sales performance effectively.
          </p>
        </div>

        <?php if (isset($error)): ?>
          <div class="text-red-600 text-sm"><?= htmlspecialchars($error) ?></div>
        <?php else: ?>

          <!-- Employee Grid -->
          <!-- <div class="bg-white rounded-b-lg shadow-md flex flex-col" style="height: calc(100vh - 220px);"></div> -->

          <div id="employee-dashboard-content" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 cursor-pointer">
            <!-- Total Orders -->
            <div class="bg-gradient-to-br from-white to-teal-50 text-teal-800 rounded-2xl shadow-lg p-5 h-48 flex flex-col justify-between transition hover:shadow-xl hover:scale-[1.02]">
              <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold">Total Orders</h3>
              </div>
              <div class="flex items-center justify-center flex-1">
                <div class="flex items-center space-x-3">
                  <div class="bg-teal-100 p-3 rounded-full shadow-sm">
                    <i class="fas fa-shopping-cart text-2xl text-teal-600"></i>
                  </div>
                  <div class="flex flex-col">
                    <p class="text-2xl font-bold">
                      <?= htmlspecialchars($total_orders) ?>
                    </p>
                    <span class="text-sm text-teal-500">All-time</span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Completed Orders -->
            <div class="bg-gradient-to-br from-white to-green-50 text-green-800 rounded-2xl shadow-lg p-5 h-48 flex flex-col justify-between transition hover:shadow-xl hover:scale-[1.02]">
              <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold">Completed Orders</h3>
              </div>
              <div class="flex items-center justify-center flex-1">
                <div class="flex items-center space-x-3">
                  <div class="bg-green-100 p-3 rounded-full shadow-sm">
                    <i class="fas fa-check-circle text-2xl text-green-600"></i>
                  </div>
                  <div class="flex flex-col">
                    <p class="text-2xl font-bold"><?= htmlspecialchars($completed_orders) ?></p>
                    <span class="text-sm text-green-500">Successful</span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Pending Orders -->
            <div class="bg-gradient-to-br from-white to-yellow-50 text-yellow-800 rounded-2xl shadow-lg p-5 h-48 flex flex-col justify-between transition hover:shadow-xl hover:scale-[1.02]">
              <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold">Pending Orders</h3>
              </div>
              <div class="flex items-center justify-center flex-1">
                <div class="flex items-center space-x-3">
                  <div class="bg-yellow-100 p-3 rounded-full shadow-sm">
                    <i class="fas fa-hourglass-half text-2xl text-yellow-600"></i>
                  </div>
                  <div class="flex flex-col">
                    <p class="text-2xl font-bold"><?= htmlspecialchars($pending_orders) ?></p>
                    <span class="text-sm text-yellow-500">Awaiting Action</span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Revenue -->
            <div class="bg-gradient-to-br from-white to-indigo-50 text-indigo-800 rounded-2xl shadow-lg p-5 h-48 flex flex-col justify-between transition hover:shadow-xl hover:scale-[1.02]">
              <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold">Revenue</h3>
              </div>
              <div class="flex items-center justify-center flex-1">
                <div class="flex items-center space-x-3">
                  <div class="bg-indigo-100 p-3 rounded-full shadow-sm">
                    <i class="fas fa-dollar-sign text-2xl text-indigo-600"></i>
                  </div>
                  <div class="flex flex-col">
                    <p class="text-2xl font-bold">₱<?= number_format($revenue, 2) ?></p>
                    <span class="text-sm text-indigo-500">This Month</span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mt-6">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4">Sales Overview</h2>
            <canvas id="sales-chart" class="w-full h-64"></canvas>
          </div>
        <?php endif; ?>
      </div>
    </main>
  </div>

  <script>
    // Sales chart for dashboard
    const labels = <?= json_encode($labels) ?>;
    const revenues = <?= json_encode($revenues) ?>;

    console.log(`labels: ${labels}, revenue: ${revenues}`);
    const ctx = document.getElementById('sales-chart')?.getContext('2d');
    if (ctx) {
      new Chart(ctx, {
        type: 'line',
        data: {
          labels: labels.length ? labels : ["No Data"],
          datasets: [{
            label: 'Revenue (₱)',
            data: revenues.length ? revenues : [0],
            borderColor: '#3b82f6',
            backgroundColor: 'rgba(59, 130, 246, 0.2)',
            fill: true,
            tension: 0.4,
            borderWidth: 2
          }]
        },
        options: {
          scales: {
            y: {
              beginAtZero: true
            }
          }
        }
      });
    }
  </script>
</body>

</html>