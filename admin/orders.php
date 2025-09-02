<?php
require_once '../config/database.php';
require_once 'auth.php';
checkAuth();

try {
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';

    $query = 'SELECT * FROM orders';
    if ($search !== '') {
        $query .= ' WHERE customer_name LIKE ? 
                OR phone_number LIKE ? 
                OR address LIKE ? 
                OR order_status LIKE ? 
                OR barangay LIKE ? 
                OR city LIKE ? 
                OR province LIKE ?';
    }
    $query .= ' ORDER BY id DESC';

    $stmt = $pdo->prepare($query);

    if ($search !== '') {
        $searchParam = "%$search%";
        $values = array_fill(0, 7, $searchParam); // Fixed: 7 parameters instead of 6
        $stmt->execute($values);
    } else {
        $stmt->execute();
    }

    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $orders = array_map(function ($o) {
        $o['men_set'] = $o['men_set'] ? 'Yes' : 'No';
        $o['women_set'] = $o['women_set'] ? 'Yes' : 'No';
        return $o;
    }, $orders);
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
                                <form id="search-form" class="flex gap-4">
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
                            <div class="overflow-x-auto max-h-[600px] overflow-y-auto">
                                <table class="min-w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"></th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Customer Name</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Phone</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Address</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Men's Set</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Women's Set</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Order Date</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="orders-table-body" class="divide-y divide-gray-200 dark:divide-gray-600 max-w-[400px] overflow-x-auto">
                                        <?php foreach ($orders as $key => $order): ?>
                                            <?php
                                            $status = $order['order_status'];
                                            $statusClass = match ($status) {
                                                'pending' => 'bg-warning',
                                                'processed' => 'bg-blue-500',
                                                'completed' => 'bg-success',
                                                'cancelled' => 'bg-danger',
                                                default => 'bg-gray-500'
                                            };
                                            ?>
                                            <tr>
                                                <td class="py-2 px-4 whitespace-wrap text-sm">
                                                    <!-- <?php echo htmlspecialchars(++$key); ?> -->
                                                    <?php
                                                    if (!empty($order['created_at']) && $order['created_at'] !== '0000-00-00 00:00:00') {
                                                        $orderTime = new DateTime($order['created_at']);
                                                        $now = new DateTime();
                                                        $diffHours = ($now->getTimestamp() - $orderTime->getTimestamp()) / 3600;
                                                        if ($diffHours <= 12) {
                                                            echo '<span class="bg-green-600 px-1 rounded-full text-white text-xs font-medium animate-pulse">New</span>';
                                                        }
                                                    }
                                                    ?>
                                                </td>
                                                <td class="py-2 px-4 whitespace-nowrap text-sm">
                                                    <?php echo htmlspecialchars($order['customer_name']); ?>
                                                </td>
                                                <td class="py-2 px-4 whitespace-nowrap text-sm"><?php echo htmlspecialchars($order['phone_number']); ?></td>
                                                <td class="py-2 px-4 text-sm max-w-[150px] truncate hover:cursor-pointer"
                                                    title="<?php echo htmlspecialchars($order['address'] . ', ' . $order['barangay'] . ', ' . $order['city'] . ', ' . $order['province']); ?>">
                                                    <?php echo htmlspecialchars($order['address'] . ', ' . $order['barangay'] . ', ' . $order['city'] . ', ' . $order['province']); ?>
                                                </td>
                                                <td class="py-2 px-4 whitespace-wrap text-sm"><?php echo htmlspecialchars($order['men_set']); ?></td>
                                                <td class="py-2 px-4 whitespace-wrap text-sm"><?php echo htmlspecialchars($order['women_set']); ?></td>
                                                <td class="py-2 px-4 whitespace-wrap text-sm">
                                                    <?php
                                                    if (!empty($order['created_at']) && $order['created_at'] !== '0000-00-00 00:00:00') {
                                                        echo (new DateTime($order['created_at']))->format('F d, Y');
                                                    } else {
                                                        echo 'N/A';
                                                    }
                                                    ?>
                                                </td>
                                                <td class="py-2 px-4 whitespace-nowrap text-sm">
                                                    <span class="px-2 py-1 text-xs font-semibold rounded-full <?php echo $statusClass; ?> text-white">
                                                        <?php echo htmlspecialchars(ucfirst($status)); ?>
                                                    </span>
                                                </td>
                                                <td class="whitespace-nowrap text-sm">
                                                    <div class="relative inline-block">
                                                        <button
                                                            class="action-btn px-3 py-1 bg-primary text-white rounded-md hover:bg-primary/80 transition"
                                                            data-order-id="<?php echo htmlspecialchars($order['id']); ?>"
                                                            aria-haspopup="true">
                                                            Actions
                                                        </button>
                                                        <div
                                                            class="action-menu hidden absolute right-0 bottom-full mb-1 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg">
                                                            <?php if ($order['order_status'] !== 'cancelled'): ?>
                                                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 update-status"
                                                                    data-order-id="<?php echo htmlspecialchars($order['id']); ?>" data-status="processed">
                                                                    Mark as Processed
                                                                </a>
                                                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 update-status"
                                                                    data-order-id="<?php echo htmlspecialchars($order['id']); ?>" data-status="completed">
                                                                    Mark as Completed
                                                                </a>
                                                                <a href="#" class="block px-4 py-2 text-sm text-danger hover:bg-gray-100 dark:hover:bg-gray-700 update-status"
                                                                    data-order-id="<?php echo htmlspecialchars($order['id']); ?>" data-status="cancelled">
                                                                    Cancel Order
                                                                </a>
                                                            <?php endif; ?>
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
                            <div id="order-details" class="space-y-4"></div>
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
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Initialize Lucide icons
            lucide.createIcons();

            const ordersData = JSON.parse(document.getElementById('orders-data').textContent);
            const orderModal = document.getElementById('order-modal');
            const orderDetails = document.getElementById('order-details');
            const closeModal = document.getElementById('close-modal');
            const searchForm = document.getElementById('search-form');
            const ordersTableBody = document.getElementById('orders-table-body');

            // Debug: Log orders data
            console.log('Orders data:', ordersData);

            // Toggle action menu
            document.querySelectorAll('.action-btn').forEach(button => {
                button.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    const menu = button.nextElementSibling;
                    console.log('Action button clicked, toggling menu:', menu);
                    document.querySelectorAll('.action-menu').forEach(m => {
                        if (m !== menu) m.classList.add('hidden');
                    });
                    menu.classList.toggle('hidden');
                });
            });

            // Close action menu when clicking outside
            document.addEventListener('click', (e) => {
                if (!e.target.closest('.action-btn') && !e.target.closest('.action-menu')) {
                    console.log('Clicked outside, closing all menus');
                    document.querySelectorAll('.action-menu').forEach(menu => {
                        menu.classList.add('hidden');
                    });
                }
            });

            // View order details
            document.querySelectorAll('.view-order').forEach(button => {
                button.addEventListener('click', (e) => {
                    e.preventDefault();
                    const orderId = button.dataset.orderId;
                    console.log('View order clicked, orderId:', orderId);
                    const order = ordersData.find(o => o.id === orderId);
                    if (order) {
                        const statusClass = {
                            'pending': 'bg-warning',
                            'processed': 'bg-blue-500',
                            'completed': 'bg-success',
                            'cancelled': 'bg-danger'
                        } [order.order_status] || 'bg-gray-500';

                        orderDetails.innerHTML = `
                            <p><strong>ID:</strong> ${order.id}</p>
                            <p><strong>Customer Name:</strong> ${order.customer_name}</p>
                            <p><strong>Phone:</strong> ${order.phone_number}</p>
                            <p><strong>Address:</strong> ${order.address}, ${order.barangay}, ${order.city}, ${order.province}</p>
                            <p><strong>Men's Set:</strong> ${order.men_set}</p>
                            <p><strong>Women's Set:</strong> ${order.women_set}</p>
                            <p><strong>Order Date:</strong> ${order.created_at && order.created_at !== '0000-00-00 00:00:00' ? new Date(order.created_at).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) : 'N/A'}</p>
                            <p><strong>Status:</strong> <span class="px-2 py-1 text-xs font-semibold rounded-full ${statusClass} text-white">${order.order_status.charAt(0).toUpperCase() + order.order_status.slice(1)}</span></p>
                        `;
                        orderModal.classList.remove('hidden');
                    } else {
                        console.error('Order not found for ID:', orderId);
                    }
                });
            });

            // Update order status
            document.querySelectorAll('.update-status').forEach(button => {
                button.addEventListener('click', async (e) => {
                    e.preventDefault();
                    const orderId = button.dataset.orderId;
                    const newStatus = button.dataset.status;
                    console.log('Updating status for orderId:', orderId, 'to:', newStatus);

                    try {
                        const response = await fetch('../admin/update_order_status.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: `order_id=${encodeURIComponent(orderId)}&status=${encodeURIComponent(newStatus)}`
                        });

                        const result = await response.json();
                        if (response.ok && result.success) {
                            console.log('Status updated successfully');
                            location.reload();
                        } else {
                            console.error('Failed to update status:', result.error);
                            alert('Failed to update status: ' + (result.error || 'Unknown error'));
                        }
                    } catch (error) {
                        console.error('Error updating status:', error);
                        alert('Error updating status: ' + error.message);
                    }
                });
            });

            // Close modal
            closeModal.addEventListener('click', () => {
                console.log('Closing modal');
                orderModal.classList.add('hidden');
            });

            // AJAX search
            searchForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                const searchValue = document.getElementById('order-search').value;
                console.log('Search submitted with value:', searchValue);

                try {
                    const response = await fetch(`../admin/search_orders.php?search=${encodeURIComponent(searchValue)}`, {
                        headers: {
                            'Accept': 'application/json'
                        }
                    });

                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }

                    const orders = await response.json();
                    console.log('Search results:', orders);

                    ordersTableBody.innerHTML = orders.map((order, index) => {
                        const statusClass = {
                            'pending': 'bg-warning',
                            'processed': 'bg-blue-500',
                            'completed': 'bg-success',
                            'cancelled': 'bg-danger'
                        } [order.order_status] || 'bg-gray-500';

                        const isNew = order.created_at && order.created_at !== '0000-00-00 00:00:00' ?
                            (new Date().getTime() - new Date(order.created_at).getTime()) / 3600 <= 12 : false;

                        return `
                            <tr>
                                <td class="py-2 px-4 whitespace-nowrap text-sm">${index + 1}</td>
                                <td class="py-2 px-4 whitespace-nowrap text-sm">
                                    ${order.customer_name}
                                    ${isNew ? '<span class="ml-2 bg-green-600 px-2 rounded-full text-white text-xs font-medium animate-pulse">New</span>' : ''}
                                </td>
                                <td class="py-2 px-4 whitespace-nowrap text-sm">${order.phone_number}</td>
                                <td class="py-2 px-4 text-sm max-w-[150px] truncate hover:cursor-pointer" 
                                    title="${order.address}, ${order.barangay}, ${order.city}, ${order.province}">
                                    ${order.address}, ${order.barangay}, ${order.city}, ${order.province}
                                </td>
                                <td class="py-2 px-4 whitespace-nowrap text-sm">${order.men_set}</td>
                                <td class="py-2 px-4 whitespace-nowrap text-sm">${order.women_set}</td>
                                <td class="py-2 px-4 whitespace-nowrap text-sm">
                                    ${order.created_at && order.created_at !== '0000-00-00 00:00:00' ? 
                                        new Date(order.created_at).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) : 'N/A'}
                                </td>
                                <td class="py-2 px-4 whitespace-nowrap text-sm">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full ${statusClass} text-white">
                                        ${order.order_status.charAt(0).toUpperCase() + order.order_status.slice(1)}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap text-sm">
                                    <div class="relative inline-block">
                                        <button class="action-btn px-3 py-1 bg-primary text-white rounded-md hover:bg-primary/80 transition" data-order-id="${order.id}" aria-haspopup="true">Actions</button>
                                        <div class="action-menu hidden absolute right-0 z-10 mt-1 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg">
                                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 view-order" data-order-id="${order.id}">View</a>
                                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 edit-order" data-order-id="${order.id}">Edit</a>
                                            ${order.order_status !== 'cancelled' ? `
                                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 update-status" data-order-id="${order.id}" data-status="processed">Mark as Processed</a>
                                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 update-status" data-order-id="${order.id}" data-status="completed">Mark as Completed</a>
                                                <a href="#" class="block px-4 py-2 text-sm text-danger hover:bg-gray-100 dark:hover:bg-gray-700 update-status" data-order-id="${order.id}" data-status="cancelled">Cancel Order</a>
                                            ` : ''}
                                            <a href="#" class="block px-4 py-2 text-sm text-danger hover:bg-gray-100 dark:hover:bg-gray-700 delete-order" data-order-id="${order.id}">Delete</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        `;
                    }).join('');
                } catch (error) {
                    console.error('Error searching orders:', error);
                    alert('Error searching orders: ' + error.message);
                }
            });
        });
    </script>
</body>

</html>