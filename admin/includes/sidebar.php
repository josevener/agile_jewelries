<?php
// Determine the current page based on the PHP script name
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
?>

<aside id="sidebar" class="fixed md:static inset-y-0 left-0 w-64 bg-secondary text-white flex flex-col transform -translate-x-full md:translate-x-0 transition-transform duration-200 z-20 sticky top-0 h-screen" aria-label="Main navigation">
    <div class="p-4 text-xl font-bold border-b border-gray-700 flex justify-between items-center">
        <span>Agile Jewelries</span>
        <button id="close-sidebar" class="md:hidden text-white" aria-label="Close sidebar">✕</button>
    </div>
    <nav class="flex-1 p-4">
        <ul class="space-y-2">
            <li>
                <a href="dashboard.php" class="sidebar-link flex items-center gap-3 p-2 rounded hover:bg-primary/70 active:bg-primary/90 transition <?php echo $currentPage === 'dashboard' ? 'bg-primary text-white' : ''; ?>" aria-current="<?php echo $currentPage === 'dashboard' ? 'page' : 'false'; ?>">
                    <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="orders.php" class="sidebar-link flex items-center gap-3 p-2 rounded hover:bg-primary/70 active:bg-primary/90 transition <?php echo $currentPage === 'orders' ? 'bg-primary text-white' : ''; ?>" aria-current="<?php echo $currentPage === 'orders' ? 'page' : 'false'; ?>">
                    <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                    <span>Orders</span>
                </a>
            </li>
            <li>
                <a href="users.php" class="sidebar-link flex items-center gap-3 p-2 rounded hover:bg-primary/70 active:bg-primary/90 transition <?php echo $currentPage === 'users' ? 'bg-primary text-white' : ''; ?>" aria-current="<?php echo $currentPage === 'users' ? 'page' : 'false'; ?>">
                    <i data-lucide="users" class="w-5 h-5"></i>
                    <span>Users</span>
                </a>
            </li>
            <li>
                <a href="products.php" class="sidebar-link flex items-center gap-3 p-2 rounded hover:bg-primary/70 active:bg-primary/90 transition <?php echo $currentPage === 'products' ? 'bg-primary text-white' : ''; ?>" aria-current="<?php echo $currentPage === 'products' ? 'page' : 'false'; ?>">
                    <i data-lucide="package" class="w-5 h-5"></i>
                    <span>Products</span>
                </a>
            </li>
            <li>
                <a href="settings.php" class="sidebar-link flex items-center gap-3 p-2 rounded hover:bg-primary/70 active:bg-primary/90 transition <?php echo $currentPage === 'settings' ? 'bg-primary text-white' : ''; ?>" aria-current="<?php echo $currentPage === 'settings' ? 'page' : 'false'; ?>">
                    <i data-lucide="settings" class="w-5 h-5"></i>
                    <span>Settings</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>

<script>
    // Initialize Lucide icons
    lucide.createIcons();

    // Sidebar toggle for mobile
    const sidebar = document.getElementById('sidebar');
    const openBtn = document.getElementById('open-sidebar');
    const closeBtn = document.getElementById('close-sidebar');

    openBtn.addEventListener('click', () => {
        sidebar.classList.remove('-translate-x-full');
        openBtn.setAttribute('aria-expanded', 'true');
    });

    closeBtn.addEventListener('click', () => {
        sidebar.classList.add('-translate-x-full');
        openBtn.setAttribute('aria-expanded', 'false');
    });
</script>