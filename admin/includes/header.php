<header class="bg-white dark:bg-gray-800 shadow-md px-6 py-3 flex justify-between items-center sticky top-0 z-10">
    <div class="flex items-center space-x-4">
        <button id="open-sidebar" class="md:hidden text-gray-600 dark:text-gray-200 hover:text-primary" aria-label="Open sidebar">
            ☰
        </button>
    </div>
    <div class="flex items-center space-x-6">
        <!-- <button id="theme-toggle" class="text-gray-600 dark:text-gray-200 hover:text-primary" aria-label="Toggle dark mode">
            <i data-lucide="moon" class="w-6 h-6 hidden dark:block"></i>
            <i data-lucide="sun" class="w-6 h-6 block dark:hidden"></i>
        </button> -->
        <button class="relative text-gray-600 dark:text-gray-200 hover:text-primary" aria-label="Notifications">
            <i data-lucide="bell" class="w-6 h-6"></i>
            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full px-1">3</span>
        </button>
        <div class="relative">
            <button id="avatar-btn" class="flex items-center text-gray-600 dark:text-gray-200 hover:text-primary focus:outline-none" aria-haspopup="true" aria-expanded="false">
                <img src="../assets/default_profile.png" alt="Admin avatar" class="w-8 h-8 rounded-full border-2 border-gray-300 dark:border-gray-600 mr-2">
                <span>
                    <?= htmlspecialchars($_SESSION['full_name'] ?? 'Admin'); ?>
                </span>
                <i data-lucide="chevron-down" class="w-4 h-4 ml-1"></i>
            </button>
            <div id="avatar-dropdown" class="hidden absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg py-1 z-10" role="menu">
                <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">
                    Change Password
                </a>
                <a href="logout.php?confirm=true" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">
                    Logout
                </a>
            </div>
        </div>
    </div>
</header>