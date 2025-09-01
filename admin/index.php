<!DOCTYPE html>
<html lang="en" class="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agile Jewelries - Admin Panel</title>
    <!-- Tailwind CSS -->
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
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- Chart.js for Dashboard Chart -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="bg-gray-100 font-sans antialiased dark:bg-gray-900 dark:text-gray-200 transition-colors duration-200">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <?php include 'includes/sidebar.php'; ?>

        <!-- Main Wrapper -->
        <div class="flex-1 flex flex-col">
            <!-- Header -->
            <?php include 'includes/header.php'; ?>

            <!-- Main Content -->
            <main id="main-content" class="flex-1 p-6 overflow-y-auto" role="main">
                <!-- Dashboard -->
                <div id="dashboard" class="page-content space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md flex items-center gap-4">
                            <i data-lucide="shopping-cart" class="w-8 h-8 text-primary"></i>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Total Orders</h3>
                                <p class="text-3xl font-bold text-primary">150</p>
                                <p class="text-sm text-success">+5% from last month</p>
                            </div>
                        </div>
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md flex items-center gap-4">
                            <i data-lucide="users" class="w-8 h-8 text-primary"></i>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Total Users</h3>
                                <p class="text-3xl font-bold text-primary">320</p>
                                <p class="text-sm text-success">+10% from last month</p>
                            </div>
                        </div>
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md flex items-center gap-4">
                            <i data-lucide="package" class="w-8 h-8 text-primary"></i>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Total Products</h3>
                                <p class="text-3xl font-bold text-primary">45</p>
                                <p class="text-sm text-danger">-2% from last month</p>
                            </div>
                        </div>
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md flex items-center gap-4">
                            <i data-lucide="dollar-sign" class="w-8 h-8 text-primary"></i>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Revenue</h3>
                                <p class="text-3xl font-bold text-primary">$12,500</p>
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
                            <li class="flex items-center gap-3">
                                <i data-lucide="user-plus" class="w-5 h-5 text-primary"></i>
                                <span>New user registered: <strong>Emily Davis</strong></span>
                                <span class="text-sm text-gray-500 dark:text-gray-400">2 hours ago</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <i data-lucide="shopping-cart" class="w-5 h-5 text-primary"></i>
                                <span>New order placed: <strong>Order #1234</strong></span>
                                <span class="text-sm text-gray-500 dark:text-gray-400">1 hour ago</span>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- Orders -->
                 <?php include 'pages/orders.php'; ?>
                <!-- Users -->
                <div id="users" class="page-content hidden space-y-6">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        <div class="flex flex-col sm:flex-row justify-between items-center mb-4 gap-4">
                            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Users</h2>
                            <div class="flex gap-4">
                                <select id="role-filter" class="px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-primary dark:bg-gray-700 dark:border-gray-600">
                                    <option value="">All Roles</option>
                                    <option value="Admin">Admin</option>
                                    <option value="User">User</option>
                                </select>
                                <input type="text" id="user-search" placeholder="Search users..." class="px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-primary dark:bg-gray-700 dark:border-gray-600">
                            </div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Email</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Role</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">1</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">John Doe</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">john@example.com</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">Admin</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <div class="relative inline-block">
                                                <button class="action-btn px-3 py-1 bg-primary text-white rounded-md hover:bg-primary/80 transition" aria-haspopup="true">Actions</button>
                                                <div class="action-menu hidden absolute z-10 mt-1 w-32 bg-white dark:bg-gray-800 rounded-md shadow-lg">
                                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">View</a>
                                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">Edit</a>
                                                    <a href="#" class="block px-4 py-2 text-sm text-danger hover:bg-gray-100 dark:hover:bg-gray-700">Delete</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">2</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">Jane Smith</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">jane@example.com</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">User</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <div class="relative inline-block">
                                                <button class="action-btn px-3 py-1 bg-primary text-white rounded-md hover:bg-primary/80 transition" aria-haspopup="true">Actions</button>
                                                <div class="action-menu hidden absolute z-10 mt-1 w-32 bg-white dark:bg-gray-800 rounded-md shadow-lg">
                                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">View</a>
                                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">Edit</a>
                                                    <a href="#" class="block px-4 py-2 text-sm text-danger hover:bg-gray-100 dark:hover:bg-gray-700">Delete</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4 flex justify-between items-center">
                            <div class="text-sm text-gray-600 dark:text-gray-400">Showing 1 to 2 of 2 entries</div>
                            <div class="flex gap-2">
                                <button class="px-3 py-1 bg-primary text-white rounded-md hover:bg-primary/80 transition disabled:opacity-50" disabled>Previous</button>
                                <button class="px-3 py-1 bg-primary text-white rounded-md hover:bg-primary/80 transition">Next</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Products -->
                <div id="products" class="page-content hidden space-y-6">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        <div class="flex flex-col sm:flex-row justify-between items-center mb-4 gap-4">
                            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Products</h2>
                            <div class="flex gap-4">
                                <select id="category-filter" class="px-3 py- Facet: 2
                                    <option value="">All Categories</option>
                                    <option value=" Necklace">Necklace</option>
                                    <option value="Ring">Ring</option>
                                </select>
                                <button id="view-toggle" class="px-3 py-1 bg-primary text-white rounded-md hover:bg-primary/80 transition flex items-center gap-2">
                                    <i data-lucide="grid" class="w-4 h-4"></i> Toggle View
                                </button>
                                <button class="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary/80 transition">Add Product</button>
                            </div>
                        </div>
                        <div id="products-table" class="overflow-x-auto">
                            <table class="min-w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Image</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Price</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Stock</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Category</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <img src="https://via.placeholder.com/40" alt="Gold Necklace" class="w-10 h-10 rounded">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">1</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">Gold Necklace</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">$200</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-success text-white">In Stock</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">Necklace</td>
                                        <td class="px_6 py-4 whitespace-nowrap text-sm">
                                            <div class="relative inline-block">
                                                <button class="action-btn px-3 py-1 bg-primary text-white rounded-md hover:bg-primary/80 transition" aria-haspopup="true">Actions</button>
                                                <div class="action-menu hidden absolute z-10 mt-1 w-32 bg-white dark:bg-gray-800 rounded-md shadow-lg">
                                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">View</a>
                                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">Edit</a>
                                                    <a href="#" class="block px-4 py-2 text-sm text-danger hover:bg-gray-100 dark:hover:bg-gray-700">Delete</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <img src="https://via.placeholder.com/40" alt="Silver Ring" class="w-10 h-10 rounded">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">2</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">Silver Ring</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">$50</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-danger text-white">Low Stock</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">Ring</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <div class="relative inline-block">
                                                <button class="action-btn px-3 py-1 bg-primary text-white rounded-md hover:bg-primary/80 transition" aria-haspopup="true">Actions</button>
                                                <div class="action-menu hidden absolute z-10 mt-1 w-32 bg-white dark:bg-gray-800 rounded-md shadow-lg">
                                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">View</a>
                                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">Edit</a>
                                                    <a href="#" class="block px-4 py-2 text-sm text-danger hover:bg-gray-100 dark:hover:bg-gray-700">Delete</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div id="products-grid" class="hidden grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                                <img src="https://via.placeholder.com/150" alt="Gold Necklace" class="w-full h-40 object-cover rounded mb-4">
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Gold Necklace</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Price: $200</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Stock: <span class="px-2 py-1 text-xs font-semibold rounded-full bg-success text-white">In Stock</span></p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Category: Necklace</p>
                                <div class="mt-4">
                                    <button class="action-btn px-3 py-1 bg-primary text-white rounded-md hover:bg-primary/80 transition">Actions</button>
                                    <div class="action-menu hidden absolute z-10 mt-1 w-32 bg-white dark:bg-gray-800 rounded-md shadow-lg">
                                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">View</a>
                                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">Edit</a>
                                        <a href="#" class="block px-4 py-2 text-sm text-danger hover:bg-gray-100 dark:hover:bg-gray-700">Delete</a>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                                <img src="https://via.placeholder.com/150" alt="Silver Ring" class="w-full h-40 object-cover rounded mb-4">
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Silver Ring</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Price: $50</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Stock: <span class="px-2 py-1 text-xs font-semibold rounded-full bg-danger text-white">Low Stock</span></p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Category: Ring</p>
                                <div class="mt-4">
                                    <button class="action-btn px-3 py-1 bg-primary text-white rounded-md hover:bg-primary/80 transition">Actions</button>
                                    <div class="action-menu hidden absolute z-10 mt-1 w-32 bg-white dark:bg-gray-800 rounded-md shadow-lg">
                                        <a —

                                            System: href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">View</a>
                                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">Edit</a>
                                        <a href="#" class="block px-4 py-2 text-sm text-danger hover:bg-gray-100 dark:hover:bg-gray-700">Delete</a>
                                    </div>
                                </div>
                            </div>
                            </tr>
                            </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- Settings -->
                <div id="settings" class="page-content hidden space-y-6">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4">Settings</h2>
                        <div class="space-y-6">
                            <!-- General Settings -->
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">General</h3>
                                <div class="space-y-4">
                                    <div>
                                        <label for="site-name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Site Name</label>
                                        <input type="text" id="site-name" value="Agile Jewelries" class="mt-1 block w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-primary dark:bg-gray-700 dark:border-gray-600">
                                    </div>
                                    <div>
                                        <label for="profile-picture" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Profile Picture</label>
                                        <input type="file" id="profile-picture" accept="image/*" class="mt-1 block w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-primary dark:bg-gray-700 dark:border-gray-600">
                                        <img id="profile-preview" src="https://via.placeholder.com/100" alt="Profile preview" class="mt-2 w-24 h-24 rounded-full">
                                    </div>
                                </div>
                            </div>
                            <!-- Security Settings -->
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">Security</h3>
                                <div class="space-y-4">
                                    <div>
                                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Admin Email</label>
                                        <input type="email" id="email" value="admin@example.com" class="mt-1 block w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-primary dark:bg-gray-700 dark:border-gray-600">
                                    </div>
                                    <div>
                                        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">New Password</label>
                                        <input type="password" id="password" class="mt-1 block w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-primary dark:bg-gray-700 dark:border-gray-600">
                                    </div>
                                </div>
                            </div>
                            <!-- Preferences -->
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">Preferences</h3>
                                <div class="space-y-4">
                                    <div class="flex items-center gap-4">
                                        <label for="notifications" class="text-sm font-medium text-gray-700 dark:text-gray-300">Email Notifications</label>
                                        <input type="checkbox" id="notifications" class="toggle-switch h-6 w-12 rounded-full bg-gray-300 focus:outline-none focus:ring-2 focus:ring-primary" checked>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        <label for="cod" class="text-sm font-medium text-gray-700 dark:text-gray-300">Cash on Delivery</label>
                                        <input type="checkbox" id="cod" class="toggle-switch h-6 w-12 rounded-full bg-gray-300 focus:outline-none focus:ring-2 focus:ring-primary">
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary/80 transition">Save Changes</button>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Initialize Lucide icons
        lucide.createIcons();

        // DOM elements
        const sidebar = document.getElementById('sidebar');
        const openBtn = document.getElementById('open-sidebar');
        const closeBtn = document.getElementById('close-sidebar');
        const avatarBtn = document.getElementById('avatar-btn');
        const avatarDropdown = document.getElementById('avatar-dropdown');
        const pageTitle = document.getElementById('page-title');
        const sidebarLinks = document.querySelectorAll('.sidebar-link');
        const pageContents = document.querySelectorAll('.page-content');
        const themeToggle = document.getElementById('theme-toggle');
        const orderModal = document.getElementById('order-modal');
        const closeModalBtn = document.getElementById('close-modal');
        const viewOrderBtns = document.querySelectorAll('.view-order');
        const productsTable = document.getElementById('products-table');
        const productsGrid = document.getElementById('products-grid');
        const viewToggle = document.getElementById('view-toggle');

        // Sidebar toggle
        openBtn.addEventListener('click', () => {
            sidebar.classList.remove('-translate-x-full');
            openBtn.setAttribute('aria-expanded', 'true');
        });
        closeBtn.addEventListener('click', () => {
            sidebar.classList.add('-translate-x-full');
            openBtn.setAttribute('aria-expanded', 'false');
        });

        // Avatar dropdown
        avatarBtn.addEventListener('click', () => {
            avatarDropdown.classList.toggle('hidden');
            const isOpen = !avatarDropdown.classList.contains('hidden');
            avatarBtn.setAttribute('aria-expanded', isOpen);
        });
        document.addEventListener('click', (e) => {
            if (!avatarBtn.contains(e.target) && !avatarDropdown.contains(e.target)) {
                avatarDropdown.classList.add('hidden');
                avatarBtn.setAttribute('aria-expanded', 'false');
            }
        });

        // Action dropdowns
        document.querySelectorAll('.action-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const menu = btn.nextElementSibling;
                menu.classList.toggle('hidden');
                btn.setAttribute('aria-expanded', !menu.classList.contains('hidden'));
            });
        });
        document.addEventListener('click', (e) => {
            document.querySelectorAll('.action-menu').forEach(menu => {
                if (!menu.previousElementSibling.contains(e.target) && !menu.contains(e.target)) {
                    menu.classList.add('hidden');
                    menu.previousElementSibling.setAttribute('aria-expanded', 'false');
                }
            });
        });

        // Handle sidebar link clicks
        sidebarLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const pageId = link.getAttribute('data-page');

                // Update page title
                pageTitle.textContent = link.querySelector('span').textContent;

                // Show selected page content, hide others
                pageContents.forEach(content => {
                    content.classList.toggle('hidden', content.id !== pageId);
                });

                // Highlight active link
                sidebarLinks.forEach(l => {
                    l.classList.toggle('bg-primary', l === link);
                    l.classList.toggle('text-white', l === link);
                    l.setAttribute('aria-current', l === link ? 'page' : 'false');
                });

                // Close sidebar on mobile
                sidebar.classList.add('-translate-x-full');
                openBtn.setAttribute('aria-expanded', 'false');
            });
        });

        // Dark mode toggle
        themeToggle.addEventListener('click', () => {
            document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light');
        });

        // Load saved theme
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.classList.add('dark');
        }

        // Order modal
        viewOrderBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                orderModal.classList.remove('hidden');
            });
        });
        closeModalBtn.addEventListener('click', () => {
            orderModal.classList.add('hidden');
        });
        document.addEventListener('click', (e) => {
            if (e.target === orderModal) {
                orderModal.classList.add('hidden');
            }
        });

        // Static search for.orders
        const orderSearch = document.getElementById('order-search');
        if (orderSearch) {
            orderSearch.addEventListener('input', function() {
                const search = this.value.toLowerCase();
                const rows = document.querySelectorAll('#orders-table-body tr');
                rows.forEach(row => {
                    const customerName = row.cells[1].textContent.toLowerCase();
                    const phone = row.cells[2].textContent.toLowerCase();
                    row.style.display = (customerName.includes(search) || phone.includes(search)) ? '' : 'none';
                });
            });
        }

        // Static search and filter for users
        const userSearch = document.getElementById('user-search');
        const roleFilter = document.getElementById('role-filter');
        if (userSearch && roleFilter) {
            const filterUsers = () => {
                const search = userSearch.value.toLowerCase();
                const role = roleFilter.value;
                const rows = document.querySelectorAll('#users tbody tr');
                rows.forEach(row => {
                    const name = row.cells[1].textContent.toLowerCase();
                    const email = row.cells[2].textContent.toLowerCase();
                    const userRole = row.cells[3].textContent;
                    const matchesSearch = name.includes(search) || email.includes(search);
                    const matchesRole = !role || userRole === role;
                    row.style.display = matchesSearch && matchesRole ? '' : 'none';
                });
            };
            userSearch.addEventListener('input', filterUsers);
            roleFilter.addEventListener('change', filterUsers);
        }

        // Product view toggle
        if (viewToggle) {
            viewToggle.addEventListener('click', () => {
                productsTable.classList.toggle('hidden');
                productsGrid.classList.toggle('hidden');
                const icon = viewToggle.querySelector('i');
                icon.setAttribute('data-lucide', productsTable.classList.contains('hidden') ? 'table' : 'grid');
                lucide.createIcons();
            });
        }

        // Product category filter
        const categoryFilter = document.getElementById('category-filter');
        if (categoryFilter) {
            categoryFilter.addEventListener('change', () => {
                const category = categoryFilter.value;
                const tableRows = document.querySelectorAll('#products-table tbody tr');
                const gridCards = document.querySelectorAll('#products-grid > div');
                tableRows.forEach((row, index) => {
                    const rowCategory = row.cells[5].textContent;
                    const matchesCategory = !category || rowCategory === category;
                    row.style.display = matchesCategory ? '' : 'none';
                    gridCards[index].style.display = matchesCategory ? '' : 'none';
                });
            });
        }

        // Profile picture preview
        const profilePictureInput = document.getElementById('profile-picture');
        const profilePreview = document.getElementById('profile-preview');
        profilePictureInput.addEventListener('change', (event) => {
            const file = event.target.files[0];
            if (file) {
                profilePreview.src = URL.createObjectURL(file);
            }
        });

        // Static form submit (alert in static version)
        const settingsForm = document.querySelector('#settings form');
        if (settingsForm) {
            settingsForm.addEventListener('submit', (e) => {
                e.preventDefault();
                alert('Settings saved (static demo)');
            });
        }

        // Export buttons (no-op in static version)
        document.querySelectorAll('#orders button').forEach(btn => {
            btn.addEventListener('click', () => alert('Export functionality available in dynamic version.'));
        });

        // Refresh button (no-op in static version)
        const refreshOrders = document.getElementById('refresh-orders');
        if (refreshOrders) {
            refreshOrders.addEventListener('click', () => alert('Refresh functionality available in dynamic version.'));
        }

        // Sales chart
        const ctx = document.getElementById('sales-chart')?.getContext('2d');
        if (ctx) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    datasets: [{
                        label: 'Revenue',
                        data: [5000, 6000, 5500, 7000, 6500, 7500],
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.2)',
                        fill: true,
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