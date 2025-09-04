<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Agile Jewelries</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.6/dist/chart.umd.min.js"></script>
</head>
<body class="bg-gray-100 font-sans h-screen overflow-hidden">
  <!-- Error Message Snackbar -->
  <div id="error-snackbar" class="hidden fixed top-4 left-1/2 transform -translate-x-1/2 bg-red-600 text-white px-4 py-2 rounded-md z-50">
    <span id="error-message"></span>
    <button onclick="closeErrorMessage()" class="ml-4">
      <i class="fas fa-times"></i>
    </button>
  </div>

  <!-- Top Bar -->
  <header class="bg-white shadow-md h-16 flex items-center fixed top-0 left-0 right-0 z-20">
    <div class="container mx-auto flex items-center justify-between px-4">
      <div class="flex items-center space-x-2">
        <img src="assets/agile_bg.png" alt="Logo" class="w-8 h-8 rounded-full border border-gray-300">
        <h1 class="text-xl font-bold text-teal-700">Agile Jewelries</h1>
      </div>
      <button id="menu-toggle" class="lg:hidden text-teal-600">
        <i class="fas fa-bars w-6 h-6"></i>
      </button>
      <div class="bg-green-700 text-white font-bold text-sm px-4 py-2 rounded-full ml-5">
        Admin Portal
      </div>
      <div class="flex-1"></div>
      <div class="flex items-center space-x-4">
        <button class="text-gray-600">
          <i class="fas fa-bell w-6 h-6"></i>
        </button>
        <button id="user-menu-toggle">
          <img src="assets/default_profile.png" alt="User Avatar" class="w-8 h-8 rounded-full border border-gray-300 cursor-pointer">
        </button>
      </div>
    </div>
    <div id="user-menu" class="hidden absolute top-16 right-4 bg-white shadow-lg rounded-md py-2 w-48 z-30">
      <button class="w-full text-left px-4 py-2 hover:bg-gray-100" onclick="showChangePasswordModal()">Change Password</button>
      <button class="w-full text-left px-4 py-2 hover:bg-gray-100" onclick="logout()">Logout</button>
    </div>
    <div id="change-password-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-40">
      <div class="bg-white p-6 rounded-lg w-full max-w-md">
        <h2 class="text-lg font-bold mb-4">Change Password</h2>
        <div>
          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Current Password</label>
            <input type="password" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
          </div>
          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">New Password</label>
            <input type="password" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
          </div>
          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Confirm New Password</label>
            <input type="password" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
          </div>
          <div class="flex justify-end space-x-2">
            <button type="button" class="px-4 py-2 bg-gray-200 rounded-md" onclick="closeChangePasswordModal()">Cancel</button>
            <button type="button" class="px-4 py-2 bg-teal-600 text-white rounded-md">Save</button>
          </div>
        </div>
      </div>
    </div>
  </header>

  <!-- Main Content -->
  <div class="flex min-h-[calc(100vh-4rem)] mt-16">
    <!-- Sidebar -->
    <aside id="sidebar" class="bg-white w-64 lg:w-64 fixed lg:static top-16 bottom-0 left-0 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out z-10">
      <div class="flex flex-col h-full p-4 overflow-auto">
        <div class="flex flex-col items-center border-b border-gray-200 pb-4">
          <img src="assets/default_profile.png" alt="User Photo" class="w-16 h-16 rounded-full border mb-2">
          <h3 class="text-base font-bold text-teal-700">John Doe</h3>
          <p class="text-sm text-gray-600">john.doe@example.com</p>
        </div>
        <nav class="flex-1 mt-4">
          <div>
            <h4 class="text-lg font-semibold text-gray-700">Menu</h4>
            <ul>
              <li>
                <button class="w-full flex justify-between items-center py-2 px-4 text-sm text-gray-600 hover:text-teal-700 hover:bg-gray-100" onclick="toggleNav(this)">
                  <div class="flex items-center">
                    <i class="fas fa-home w-5 h-5 mr-2"></i>
                    Dashboard
                  </div>
                  <i class="fas fa-chevron-down w-4 h-4 expand-icon"></i>
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
              </li>
              <li>
                <a href="orders_overview.html" class="flex items-center py-2 px-4 text-sm text-gray-600 hover:text-teal-700 hover:bg-gray-100">
                  <i class="fas fa-box w-5 h-5 mr-2"></i>
                  Orders Overview
                </a>
              </li>
              <li>
                <a href="/client/app/settings" class="flex items-center py-2 px-4 text-sm text-gray-600 hover:text-teal-700 hover:bg-gray-100">
                  <i class="fas fa-cog w-5 h-5 mr-2"></i>
                  Settings
                </a>
              </li>
            </ul>
            <div class="my-2 border-t border-gray-200"></div>
            <h4 class="text-lg font-semibold text-gray-700">Reports</h4>
            <ul>
              <li>
                <a href="/client/app/reports" class="flex items-center py-2 px-4 text-sm text-gray-600 hover:text-teal-700 hover:bg-gray-100">
                  <i class="fas fa-file-alt w-5 h-5 mr-2"></i>
                  Reports
                </a>
              </li>
              <li>
                <a href="/client/app/reports/employee_productivity_dashboard" class="flex items-center py-2 px-4 text-sm text-gray-600 hover:text-teal-700 hover:bg-gray-100">
                  <i class="fas fa-users w-5 h-5 mr-2"></i>
                  Employee Productivity Dashboard
                </a>
              </li>
            </ul>
          </div>
        </nav>
        <div class="mt-auto text-center">
          <p class="text-xs text-gray-500">JeonSoft Corporation</p>
        </div>
      </div>
    </aside>
    <!-- Work Area -->
    <main class="flex-1 bg-teal-50 rounded-t-lg p-4 overflow-auto">
      <div id="work-area" class="p-4" style="height: calc(100vh - 84px);">
        <div class="flex justify-between items-center mb-4">
          <div>
            <h2 class="text-2xl font-bold text-gray-800">Employee Productivity Dashboard - John Doe</h2>
            <p class="text-sm text-gray-600">Summary of John Doe's Productivity</p>
          </div>
          <button id="filter-button" class="text-gray-500 hover:text-gray-700" onclick="toggleFilterModal()">
            <i class="fas fa-filter w-5 h-5"></i>
          </button>
        </div>
        <!-- Filter Modal -->
        <div id="filter-modal" class="hidden fixed top-0 right-0 h-full bg-white p-4 shadow-lg w-80 z-50">
          <h3 class="text-lg font-bold mb-4">Filter Dashboard Data</h3>
          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">From Date</label>
            <input id="start-date" type="date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="2025-08-04">
          </div>
          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">End Date</label>
            <input id="end-date" type="date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="2025-09-04">
          </div>
          <div class="flex justify-between border-t pt-2">
            <button onclick="resetFilter()" class="px-4 py-2 bg-gray-200 rounded-md text-sm">Reset</button>
            <div class="flex space-x-2">
              <button onclick="toggleFilterModal()" class="px-4 py-2 bg-gray-200 rounded-md text-sm">Close</button>
              <button onclick="applyFilter()" class="px-4 py-2 bg-teal-600 text-white rounded-md text-sm">Apply</button>
            </div>
          </div>
        </div>
        <!-- Dashboard Content -->
        <div id="employee-dashboard-content" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
          <!-- Card Columns -->
          <div class="bg-white rounded-lg shadow-md p-4 h-48 flex flex-col">
            <h3 class="text-lg font-bold mb-2">Date</h3>
            <div id="date-card" class="flex items-center justify-center flex-1">
              <i class="fas fa-calendar w-12 h-12 text-gray-600 mr-2"></i>
              <div class="flex flex-col justify-center">
                <p id="date-text" class="text-lg font-bold">Aug 4, 2025 - Sep 4, 2025</p>
              </div>
            </div>
          </div>
          <div class="bg-white rounded-lg shadow-md p-4 h-48 flex flex-col">
            <h3 class="text-lg font-bold mb-2">Online Hours</h3>
            <div id="online-hours" class="flex items-center justify-center flex-1">
              <i class="fas fa-clock w-12 h-12 text-gray-600 mr-2"></i>
              <div class="flex flex-col justify-center">
                <p class="text-lg font-bold">120</p>
              </div>
            </div>
          </div>
          <div class="bg-white rounded-lg shadow-md p-4 h-48 flex flex-col">
            <h3 class="text-lg font-bold mb-2">Idle Hours</h3>
            <div id="idle-hours" class="flex items-center justify-center flex-1">
              <i class="fas fa-hourglass-half w-12 h-12 text-gray-600 mr-2"></i>
              <div class="flex flex-col justify-center">
                <p class="text-lg font-bold">10</p>
              </div>
            </div>
          </div>
          <!-- Screen Presence (Half Pie Chart) -->
          <div class="bg-white rounded-lg shadow-md p-4 h-48 flex flex-col">
            <h3 class="text-lg font-bold mb-2">Screen Presence</h3>
            <div id="screen-presence" class="flex-1">
              <canvas id="screen-presence-chart"></canvas>
            </div>
          </div>
          <!-- App Usage -->
          <div class="bg-white rounded-lg shadow-md p-4 h-[280px] flex flex-col col-span-1 lg:col-span-2">
            <h3 class="text-lg font-bold mb-2">App Usage</h3>
            <div class="flex-1 overflow-auto">
              <canvas id="app-usage-chart"></canvas>
            </div>
          </div>
          <!-- Web Usage -->
          <div class="bg-white rounded-lg shadow-md p-4 h-[280px] flex flex-col col-span-1 lg:col-span-2">
            <h3 class="text-lg font-bold mb-2">Web Usage</h3>
            <div class="flex-1 overflow-auto">
              <canvas id="web-usage-chart"></canvas>
            </div>
          </div>
          <!-- Daily Project Allocation -->
          <div class="bg-white rounded-lg shadow-md p-4 h-[356px] flex flex-col col-span-1 xl:col-span-2">
            <h3 class="text-lg font-bold mb-2">Daily Project Allocation</h3>
            <div class="flex-1 relative">
              <span class="absolute top-4 left-3 text-gray-500 text-sm">Hours</span>
              <canvas id="daily-project-chart"></canvas>
            </div>
          </div>
          <!-- Overall Usage -->
          <div class="bg-white rounded-lg shadow-md p-4 h-[356px] flex flex-col col-span-1 lg:col-span-2">
            <h3 class="text-lg font-bold mb-2">Overall Usage</h3>
            <div class="flex-1">
              <canvas id="overall-usage-chart"></canvas>
            </div>
            <!-- Legend for Overall Usage -->
            <div class="flex flex-wrap justify-center mt-2">
              <div class="flex items-center mr-4">
                <span class="w-3 h-3 rounded-full bg-[#376C8B] mr-1"></span>
                <span class="text-sm text-gray-600">Productive</span>
              </div>
              <div class="flex items-center mr-4">
                <span class="w-3 h-3 rounded-full bg-[#6B7280] mr-1"></span>
                <span class="text-sm text-gray-600">Neutral</span>
              </div>
              <div class="flex items-center">
                <span class="w-3 h-3 rounded-full bg-[#EF4444] mr-1"></span>
                <span class="text-sm text-gray-600">Unproductive</span>
              </div>
            </div>
          </div>
          <!-- Total Project Time -->
          <div class="bg-white rounded-lg shadow-md p-4 h-[356px] flex flex-col col-span-1 lg:col-span-2">
            <h3 class="text-lg font-bold mb-2">Total Project Time</h3>
            <div class="flex flex-col border-b border-gray-800">
              <div class="flex justify-around">
                <span class="text-base font-medium">Project</span>
                <span class="text-base font-medium">Time Spent</span>
              </div>
            </div>
            <div id="project-time-list" class="flex-1 overflow-auto pt-2">
              <div class="grid grid-cols-2 gap-2 mb-2">
                <p class="text-center text-gray-600">Project A</p>
                <p class="text-center text-gray-600">10:30:00</p>
              </div>
              <div class="grid grid-cols-2 gap-2 mb-2">
                <p class="text-center text-gray-600">Project B</p>
                <p class="text-center text-gray-600">8:15:45</p>
              </div>
              <div class="grid grid-cols-2 gap-2 mb-2">
                <p class="text-center text-gray-600">Project C</p>
                <p class="text-center text-gray-600">5:00:00</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>

  <script>
    // Mock data for EmployeeProductivityDashboard
    const dashboardData = {
      startDate: new Date('2025-08-04'),
      endDate: new Date('2025-09-04'),
      onlineHours: 120,
      idleHours: 10,
      screenPresence: [
        { name: "Present", value: 75, color: "#376C8B" },
        { name: "Absent", value: 25, color: "#D3D3D3" },
      ],
      appUsage: [
        { name: "App A", value: 40 },
        { name: "App B", value: 30 },
        { name: "App C", value: 20 },
        { name: "App D", value: 10 },
      ],
      webUsage: [
        { name: "Site A", value: 50 },
        { name: "Site B", value: 25 },
        { name: "Site C", value: 15 },
        { name: "Site D", value: 10 },
      ],
      dailyProject: [
        { name: "2025-09-01", ProjectA: 4, ProjectB: 2, ProjectC: 1 },
        { name: "2025-09-02", ProjectA: 3, ProjectB: 3, ProjectC: 2 },
        { name: "2025-09-03", ProjectA: 5, ProjectB: 1, ProjectC: 0 },
      ],
      overallUsage: [
        { name: "Productive", value: 60, color: "#376C8B" },
        { name: "Neutral", value: 30, color: "#6B7280" },
        { name: "Unproductive", value: 10, color: "#EF4444" },
      ],
      projectTime: [
        { name: "Project A", hours: 10, minutes: 30, seconds: 0 },
        { name: "Project B", hours: 8, minutes: 15, seconds: 45 },
        { name: "Project C", hours: 5, minutes: 0, seconds: 0 },
      ],
    };

    // Chart instances
    let screenPresenceChart = null;
    let appUsageChart = null;
    let webUsageChart = null;
    let dailyProjectChart = null;
    let overallUsageChart = null;

    // Utility Functions
    function formatDate(date) {
      return `${date.getFullYear()}-${(date.getMonth() + 1).toString().padStart(2, '0')}-${date.getDate().toString().padStart(2, '0')}`;
    }

    function parseDateString(dateString) {
      const parts = dateString.split('-');
      return new Date(parts[0], parts[1] - 1, parts[2]);
    }

    function showErrorMessage(message) {
      document.getElementById('error-message').textContent = message;
      document.getElementById('error-snackbar').classList.remove('hidden');
      setTimeout(closeErrorMessage, 5000);
    }

    function closeErrorMessage() {
      document.getElementById('error-snackbar').classList.add('hidden');
    }

    // Sidebar Toggle
    const menuToggle = document.getElementById('menu-toggle');
    const sidebar = document.getElementById('sidebar');
    menuToggle.addEventListener('click', () => {
      sidebar.classList.toggle('-translate-x-full');
    });

    // User Menu Dropdown
    const userMenuToggle = document.getElementById('user-menu-toggle');
    const userMenu = document.getElementById('user-menu');
    userMenuToggle.addEventListener('click', () => {
      userMenu.classList.toggle('hidden');
    });

    document.addEventListener('click', (e) => {
      if (!userMenu.contains(e.target) && !userMenuToggle.contains(e.target)) {
        userMenu.classList.add('hidden');
      }
    });

    // Navigation Toggle
    function toggleNav(button) {
      const ul = button.nextElementSibling;
      const icon = button.querySelector('.expand-icon');
      ul.classList.toggle('hidden');
      icon.classList.toggle('fa-chevron-down');
      icon.classList.toggle('fa-chevron-up');
    }

    // Change Password Modal
    function showChangePasswordModal() {
      document.getElementById('change-password-modal').classList.remove('hidden');
      userMenu.classList.add('hidden');
    }

    function closeChangePasswordModal() {
      document.getElementById('change-password-modal').classList.add('hidden');
    }

    // Logout
    function logout() {
      console.log('Logout initiated');
    }

    // Chart Rendering
    function renderDashboardCharts() {
      // Screen Presence Chart
      if (screenPresenceChart) screenPresenceChart.destroy();
      screenPresenceChart = new Chart(document.getElementById('screen-presence-chart'), {
        type: 'doughnut',
        data: {
          labels: dashboardData.screenPresence.map(d => d.name),
          datasets: [{
            data: dashboardData.screenPresence.map(d => d.value),
            backgroundColor: dashboardData.screenPresence.map(d => d.color),
            borderWidth: 0,
          }],
        },
        options: {
          cutout: '30%',
          rotation: -90,
          circumference: 180,
          plugins: {
            tooltip: {
              callbacks: {
                label: function(context) {
                  return `${context.label}: ${context.raw}%`;
                },
              },
            },
          },
        },
      });

      // App Usage Chart
      if (appUsageChart) appUsageChart.destroy();
      appUsageChart = new Chart(document.getElementById('app-usage-chart'), {
        type: 'bar',
        data: {
          labels: dashboardData.appUsage.map(d => d.name),
          datasets: [{
            data: dashboardData.appUsage.map(d => d.value),
            backgroundColor: '#376C8B',
            barThickness: 50,
          }],
        },
        options: {
          indexAxis: 'y',
          scales: {
            x: { display: false, max: 100 },
            y: { ticks: { autoSkip: false } },
          },
          plugins: {
            legend: { display: false },
            tooltip: {
              callbacks: {
                label: function(context) {
                  return `${context.label}: ${context.raw}%`;
                },
              },
            },
          },
        },
      });

      // Web Usage Chart
      if (webUsageChart) webUsageChart.destroy();
      webUsageChart = new Chart(document.getElementById('web-usage-chart'), {
        type: 'bar',
        data: {
          labels: dashboardData.webUsage.map(d => d.name),
          datasets: [{
            data: dashboardData.webUsage.map(d => d.value),
            backgroundColor: '#376C8B',
            barThickness: 50,
          }],
        },
        options: {
          indexAxis: 'y',
          scales: {
            x: { display: false, max: 100 },
            y: { ticks: { autoSkip: false } },
          },
          plugins: {
            legend: { display: false },
            tooltip: {
              callbacks: {
                label: function(context) {
                  return `${context.label}: ${context.raw}%`;
                },
              },
            },
          },
        },
      });

      // Daily Project Allocation Chart
      if (dailyProjectChart) dailyProjectChart.destroy();
      const barKeys = Object.keys(dashboardData.dailyProject[0]).filter(key => key !== 'name');
      dailyProjectChart = new Chart(document.getElementById('daily-project-chart'), {
        type: 'bar',
        data: {
          labels: dashboardData.dailyProject.map(d => d.name),
          datasets: barKeys.map(key => ({
            label: key,
            data: dashboardData.dailyProject.map(d => d[key]),
            backgroundColor: '#' + (parseInt(key, 36) % 0xFFFFFF).toString(16).padStart(6, '0'),
            stack: 'a',
          })),
        },
        options: {
          scales: {
            x: { stacked: true },
            y: { stacked: true, title: { display: true, text: 'Hours' } },
          },
          plugins: {
            legend: { position: 'top', align: 'end' },
            tooltip: {
              callbacks: {
                label: function(context) {
                  return `${context.dataset.label}: ${context.raw} ${context.raw === 1 ? 'hr' : 'hrs'}`;
                },
              },
            },
          },
        },
      });

      // Overall Usage Chart
      if (overallUsageChart) overallUsageChart.destroy();
      overallUsageChart = new Chart(document.getElementById('overall-usage-chart'), {
        type: 'pie',
        data: {
          labels: dashboardData.overallUsage.map(d => d.name),
          datasets: [{
            data: dashboardData.overallUsage.map(d => d.value),
            backgroundColor: dashboardData.overallUsage.map(d => d.color),
            borderWidth: 0,
          }],
        },
        options: {
          plugins: {
            tooltip: {
              callbacks: {
                label: function(context) {
                  return `${context.label}: ${context.raw}%`;
                },
              },
            },
          },
        },
      });
    }

    // Filter Functions
    function toggleFilterModal() {
      document.getElementById('filter-modal').classList.toggle('hidden');
    }

    function applyFilter() {
      const start = new Date(document.getElementById('start-date').value);
      const end = new Date(document.getElementById('end-date').value);
      if (isNaN(start.getTime()) || isNaN(end.getTime())) {
        showErrorMessage('Please select valid dates.');
        return;
      }
      document.getElementById('date-text').textContent = `${start.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })} - ${end.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}`;
      document.getElementById('filter-modal').classList.add('hidden');
      // In a real app, update dashboardData with new API call here
    }

    function resetFilter() {
      document.getElementById('start-date').value = formatDate(new Date('2025-08-04'));
      document.getElementById('end-date').value = formatDate(new Date('2025-09-04'));
      document.getElementById('date-text').textContent = `Aug 4, 2025 - Sep 4, 2025`;
      document.getElementById('filter-modal').classList.add('hidden');
      // In a real app, reset dashboardData with default API call
    }

    // Dynamic Work Area Height
    const workArea = document.getElementById('work-area');
    function resizeWorkArea() {
      workArea.style.height = (window.innerHeight - 84) + 'px';
    }
    window.addEventListener('resize', resizeWorkArea);
    resizeWorkArea();

    // Initial Chart Rendering
    renderDashboardCharts();
  </script>
</body>
</html>