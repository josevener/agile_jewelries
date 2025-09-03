<div class="flex flex-col h-full p-4 overflow-auto">
    <!-- User Profile -->
    <div class="flex flex-col items-center border-b border-gray-200 pb-4">
        <img src="../assets/default_profile.png" alt="User Photo" class="w-16 h-16 rounded-full border mb-2">
        <h3 class="text-base font-bold text-teal-700">
            <?php echo htmlspecialchars($_SESSION['full_name'] ?? 'Admin'); ?>
        </h3>
        <p class="text-sm text-gray-600">
            <?php echo htmlspecialchars($_SESSION['email'] ?? '' ); ?>
        </p>
    </div>
    <!-- Navigation -->
    <nav class="flex-1 mt-4">
        <div>
            <h4 class="text-md font-semibold text-gray-700">Menu</h4>
            <ul>
                <!-- <li>
                    <button class="w-full flex justify-between items-center py-2 px-4 text-sm text-gray-600 hover:text-teal-700 hover:bg-gray-100" onclick="toggleNav(this)">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            Dashboard
                        </div>
                        <svg class="w-4 h-4 expand-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <ul class="hidden ml-6 border-l border-gray-200">
                        <li>
                            <a href="/client/app/overview" class="flex items-center py-2 px-4 text-sm text-gray-600 hover:text-teal-700 hover:bg-gray-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-gray-600 mr-2"></span>
                                Overview
                            </a>
                        </li>
                        <li>
                            <a href="/client/app/stats" class="flex items-center py-2 px-4 text-sm text-gray-600 hover:text-teal-700 hover:bg-gray-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-gray-600 mr-2"></span>
                                Stats
                            </a>
                        </li>
                    </ul>
                </li> -->
                <li>
                    <a href="analytics.php" class="flex items-center py-2 px-4 text-sm text-gray-600 hover:text-teal-700 hover:bg-gray-100">
                        <i class="fa-solid fa-chart-line mr-2"></i>
                        Analytics
                    </a>
                </li>
                <li>
                    <a href="orders_overview.php" class="flex items-center py-2 px-4 text-sm text-gray-600 hover:text-teal-700 hover:bg-gray-100">
                        <i class="fa-solid fa-cart-shopping mr-2"></i>
                        Orders Overview
                    </a>
                </li>
            </ul>
            
            <!-- <div class="my-2 border-t border-gray-200"></div>

            <h4 class="text-md font-semibold text-gray-700">Reports</h4>
            <ul>
                <li>
                    <a href="/client/app/reports" class="flex items-center py-2 px-4 text-sm text-gray-600 hover:text-teal-700 hover:bg-gray-100">
                        <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Reports
                    </a>
                </li>
                <li>
                    <a href="/client/app/reports/employee_productivity_dashboard" class="flex items-center py-2 px-4 text-sm text-gray-600 hover:text-teal-700 hover:bg-gray-100">
                        <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        Employee View
                    </a>
                </li>
            </ul> -->
        </div>
    </nav>
    <!-- Footer -->
    <div class="mt-auto text-center">
        <a href="https://zentrix-solutions.vercel.app" target="_blank" class="text-xs text-gray-500">
            <i>Powered by Zentrix Solutions</i>
        </a>
    </div>
</div>