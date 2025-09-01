<?php
require_once '../config/database.php';

try {
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    $query = 'SELECT * FROM orders';
    if ($search) {
        $query .= ' WHERE customer_name LIKE :search OR phone_number LIKE :search';
    }
    $query .= ' ORDER BY id DESC';

    $stmt = $pdo->prepare($query);
    if ($search) {
        $searchParam = "%$search%";
        $stmt->bindParam(':search', $searchParam, PDO::PARAM_STR);
    }
    $stmt->execute();
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Format boolean fields for readability
    foreach ($orders as &$order) {
        $order['men_set'] = $order['men_set'] ? 'Yes' : 'No';
        $order['women_set'] = $order['women_set'] ? 'Yes' : 'No';
    }
} catch (PDOException $e) {
    $error = 'Database error: ' . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en" class="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agile Jewelries - Orders</title>
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
    <script src="js/scripts.js" defer></script>
</head>

<body class="bg-gray-100 font-sans antialiased dark:bg-gray-900 dark:text-gray-200 transition-colors duration-200">
    <div class="flex h-screen overflow-hidden">
        <?php include 'includes/sidebar.php'; ?>
        <div class="flex-1 flex flex-col">
            <?php include 'includes/header.php'; ?>
            <main class="flex-1 p-6 overflow-y-auto" role="main">
                <div class="page-content space-y-6">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        <div class="flex flex-col sm:flex-row justify-between items-center mb-4 gap-4">
                            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Orders</h2>
                            <div class="flex gap-4">
                                <form method="GET" action="" class="flex gap-4">
                                    <input type="text" id="order-search" name="search" placeholder="Search orders..." value="<?php echo htmlspecialchars($search); ?>" class="px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-primary dark:bg-gray-700 dark:border-gray-600">
                                    <button type="submit" class="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary/80 transition flex items-center gap-2">
                                        <i data-lucide="search" class="w-4 h-4"></i> Search
                                    </button>
                                </form>
                                <button id="export-csv" class="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary/80 transition flex items-center gap-2">
                                    <i data-lucide="download" class="w-4 h-4"></i> Export CSV
                                </button>
                                <button id="export-pdf" class="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary/80 transition flex items-center gap-2">
                                    <i data-lucide="file-text" class="w-4 h-4"></i> Export PDF
                                </button>
                            </div>
                        </div>
                        <?php if (isset($error)): ?>
                            <div class="text-danger text-sm"><?php echo htmlspecialchars($error); ?></div>
                        <?php else: ?>
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Customer Name</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Phone</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Address</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Men's Set</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Women's Set</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="orders-table-body" class="divide-y divide-gray-200 dark:divide-gray-600">
                                        <?php foreach ($orders as $order): ?>
                                            <?php
                                                $status = $order['created_at'] === '0000-00-00 00:00:00' ? 'Pending' : 'Shipped';
                                                $statusClass = $status === 'Pending' ? 'bg-warning' : 'bg-success';
                                            ?>
                                            <tr>
                                                <td class="py-2 px-4  whitespace-nowrap text-sm"><?php echo htmlspecialchars($order['id']); ?></td>
                                                <td class="py-2 px-4  whitespace-nowrap text-sm"><?php echo htmlspecialchars($order['customer_name']); ?></td>
                                                <td class="py-2 px-4  whitespace-nowrap text-sm"><?php echo htmlspecialchars($order['phone_number']); ?></td>
                                                <td class="py-2 px-4  text-sm"><?php echo htmlspecialchars($order['address'] . ', ' . $order['barangay'] . ', ' . $order['city'] . ', ' . $order['province']); ?></td>
                                                <td class="py-2 px-4  whitespace-nowrap text-sm"><?php echo htmlspecialchars($order['men_set']); ?></td>
                                                <td class="py-2 px-4  whitespace-nowrap text-sm"><?php echo htmlspecialchars($order['women_set']); ?></td>
                                                <td class="py-2 px-4  whitespace-nowrap text-sm">
                                                    <span class="px-2 py-1 text-xs font-semibold rounded-full <?php echo $statusClass; ?> text-white"><?php echo htmlspecialchars($status); ?></span>
                                                </td>
                                                <td class="whitespace-nowrap text-sm">
                                                    <div class="relative inline-block">
                                                        <button class="action-btn px-3 py-1 bg-primary text-white rounded-md hover:bg-primary/80 transition view-order" data-order-id="<?php echo htmlspecialchars($order['id']); ?>" aria-haspopup="true">Actions</button>
                                                        <div class="action-menu hidden absolute z-10 mt-1 w-32 bg-white dark:bg-gray-800 rounded-md shadow-lg">
                                                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 view-order" data-order-id="<?php echo htmlspecialchars($order['id']); ?>">View</a>
                                                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">Edit</a>
                                                            <a href="#" class="block px-4 py-2 text-sm text-danger hover:bg-gray-100 dark:hover:bg-gray-700">Delete</a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                    <!-- Order Modal -->
                    <div id="order-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-full max-w-lg">
                            <div class="flex justify-between items-center mb-4">
                                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Order Details</h2>
                                <button id="close-modal" class="text-gray-600 dark:text-gray-200 hover:text-primary" aria-label="Close modal">✕</button>
                            </div>
                            <div id="order-details" class="space-y-4">
                                <!-- Order details populated via JavaScript -->
                            </div>
                        </div>
                    </div>
                    <!-- Hidden script tag to pass orders data to JavaScript -->
                    <script id="orders-data" type="application/json">
                        <?php echo json_encode($orders); ?>
                    </script>
                </div>
            </main>
        </div>
    </div>
</body>

</html>