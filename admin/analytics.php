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


  // Generate all months for the current year
  $months = [
    'Jan',
    'Feb',
    'Mar',
    'Apr',
    'May',
    'Jun',
    'Jul',
    'Aug',
    'Sep',
    'Oct',
    'Nov',
    'Dec'
  ];
  $current_month = date('n');
  $current_year = date('Y');

  // Monthly sales query with data up to current month
  $salesStmt = $pdo->prepare("
    SELECT 
        m.month_name AS month,
        COALESCE(SUM(
            CASE 
                WHEN o.order_status = 'completed' 
                THEN (CASE WHEN o.men_set = 1 OR o.women_set = 1 THEN o.amount ELSE 0 END) 
                ELSE 0 
            END
        ), 0) AS monthly_revenue
    FROM (
        SELECT n AS month_num, DATE_FORMAT(STR_TO_DATE(n, '%m'), '%b') AS month_name
        FROM (
            SELECT a.N + b.N * 10 + 1 AS n
            FROM 
                (SELECT 0 AS N UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 
                UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) a,
                (SELECT 0 AS N UNION SELECT 1) b
        ) months
        WHERE n <= ?
    ) m
    LEFT JOIN orders o
        ON MONTH(o.created_at) = m.month_num
      AND YEAR(o.created_at) = ?
    GROUP BY m.month_num, m.month_name
    ORDER BY m.month_num;
  ");
  $salesStmt->execute([$current_month, $current_year]);

  $salesData = $salesStmt->fetchAll(PDO::FETCH_ASSOC);

  // Prepare labels and revenues for all 12 months
  $labels = $months; // Use all months for chart labels
  $revenues = array_fill(0, $current_month, null); // Initialize with null up to current month

  // Fill revenues for months up to current month
  foreach ($salesData as $data) {
    $month_index = array_search($data['month'], $months);
    if ($month_index !== false && $month_index < $current_month) {
      $revenues[$month_index] = $data['monthly_revenue'];
    }
  }
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

    <main class="flex-1 bg-gray-200 rounded-t-xl overflow-auto">
      <div id="work-area" class="p-4" style="height: calc(100vh - 84px);">
        <!-- Title Bar -->
        <div class="mb-4">
          <h2 class="text-2xl font-bold text-gray-800">Analytics Overview</h2>
          <p class="text-sm text-gray-600">
            View key insights and performance metrics to monitor growth, track orders,
            and analyze sales performance effectively.
          </p>
        </div>
        <!-- Search Bar -->
        <div class="bg-white rounded-t-lg p-2 flex flex-row gap-2">

        </div>

        <!-- Employee Grid -->
        <div class="bg-white rounded-b-lg shadow-md flex flex-col" style="height: calc(100vh - 190px);">
          <div class="flex-1 overflow-auto ">
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
              <canvas id="sales-chart" class="w-full"></canvas>
            </div>
          </div>
        </div>
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