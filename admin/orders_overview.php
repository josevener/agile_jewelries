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
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Agile Jewelries</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/@heroicons/react/24/outline/index.js"></script>
  <link rel="stylesheet" href="../assets/fontawesome/css/all.min.css">

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
    <main class="flex-1 bg-teal-50 rounded-t-xl overflow-auto">
      <div id="work-area" class="p-4" style="height: calc(100vh - 84px);">
        <!-- Title Bar -->
        <div class="mb-4">
          <h2 class="text-2xl font-bold text-gray-800">Orders Overview</h2>
          <p class="text-sm text-gray-600">Manage and track all customer orders in one place.</p>
        </div>
        <!-- Search Bar -->
        <div class="bg-white rounded-t-lg p-2 flex flex-row gap-2">
          <div class="relative flex-1 max-w-[326px]">
            <input
              id="search-input"
              type="text"
              placeholder="Search Employee Name or Code"
              class="w-full border border-gray-300 rounded-md p-2 pr-20 text-sm">

            <div class="absolute inset-y-0 right-0 flex items-center space-x-1 pr-2">
              <button id="clear-search" class="hidden text-gray-500 hover:text-gray-700">
                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
              <button id="search-button" class="text-gray-500 hover:text-gray-700">
                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
              </button>
            </div>
          </div>
          <button id="search-submit" class="border border-teal-600 text-teal-600 px-4 py-1 rounded-md text-sm hover:bg-teal-50">Search</button>
        </div>

        <!-- Employee Grid -->
        <div class="bg-white rounded-b-lg shadow-md flex flex-col" style="height: calc(100vh - 220px);">
          <div class="overflow-auto flex-1">
            <table class="w-full text-sm text-left text-gray-600 mx-auto">
              <thead class="bg-white">
                <tr>
                  <th class="px-2 py-1 text-center">
                    <input type="checkbox" id="select-all"
                      class="h-4 w-4 text-teal-600 border-gray-300 rounded focus:ring-teal-500 cursor-pointer">
                  </th>
                  <th class="p-2">Full Name</th>
                  <th class="p-2">Contact No.</th>
                  <th class="p-2">Address</th>
                  <th class="p-2">Men's Set</th>
                  <th class="p-2">Women's Set</th>
                  <th class="p-2">Order Date</th>
                  <th class="p-2">Status</th>
                  <th class="p-2"></th>
                </tr>
              </thead>
              <!-- id="employee-table" -->
              <tbody class="divide-y divide-gray-200">
                <!-- <p class="text-gray-600">Loading employee productivity data...</p> -->
                <?php foreach ($orders as $order): ?>
                  <?php
                  $status = $order['order_status'];
                  $statusClass = match ($status) {
                    'pending' => 'bg-orange-100 text-orange-800',
                    'processed' => 'bg-blue-100 text-blue-800 ',
                    'completed' => 'bg-green-100 text-green-800 ',
                    'cancelled' => 'bg-red-100 text-red-800',
                    default => 'bg-gray-500 text-white'
                  };
                  ?>
                  <tr>
                    <td class="px-2 py-1 text-center">
                      <input type="checkbox"
                        class="row-checkbox h-4 w-4 text-teal-600 border-gray-300 rounded focus:ring-teal-500 cursor-pointer">
                    </td>
                    <td class="p-2">
                      <!-- <img src="../assets/default_profile.png" alt="Employee" class="w-8 h-8 rounded-full border border-2"> -->
                      <p><?php echo htmlspecialchars($order['customer_name']); ?></p>
                    </td>
                    <td class="p-2"><?php echo htmlspecialchars($order['phone_number']); ?></td>
                    <td class="p-2 max-w-[150px] truncate cursor-pointer"
                      title="<?php echo htmlspecialchars($order['address'] . ', ' . $order['barangay'] . ', ' . $order['city'] . ', ' . $order['province']); ?>">
                      <?php echo htmlspecialchars($order['address'] . ', ' . $order['barangay'] . ', ' . $order['city'] . ', ' . $order['province']); ?>
                    </td>
                    <td class="p-2 text-center"><?php echo htmlspecialchars($order['men_set']); ?></td>
                    <td class="p-2 text-center"><?php echo htmlspecialchars($order['women_set']); ?></td>
                    <td class="p-2">
                      <?php
                      if (!empty($order['created_at']) && $order['created_at'] !== '0000-00-00 00:00:00') {
                        echo (new DateTime($order['created_at']))->format('F d, Y');
                      } else {
                        echo 'N/A';
                      }
                      ?>
                    </td>
                    <td class="">
                      <span class="text-xs font-semibold px-2.5 py-0.5 rounded <?php echo $statusClass; ?>">
                        <?php echo htmlspecialchars(ucfirst($status)); ?>
                      </span>
                    </td>
                    <td class="text-start">
                      <button
                        class="text-center text-teal-600 hover:text-teal-800"
                        onclick='openOrderDetails(<?= json_encode($order, JSON_HEX_APOS | JSON_HEX_QUOT) ?>)'>
                        <i class="fa-solid fa-arrow-up-right-from-square text-xl"></i>
                        </button>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          <div class="flex justify-between items-center p-4 bg-white border-t">
            <!-- <button id="load-all" class="px-4 py-2 bg-teal-600 text-white rounded-md text-sm">Load All</button> -->
            <p></p>
            <div class="flex space-x-2">
              <button id="prev-page" class="px-4 py-2 bg-gray-200 rounded-md text-sm">Previous</button>
              <span id="page-info" class="text-sm self-center">Page 1</span>
              <button id="next-page" class="px-4 py-2 bg-gray-200 rounded-md text-sm">Next</button>
            </div>
          </div>
        </div>

        <!-- Order Details Modal -->
        <div id="order-details-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
          <div class="bg-white p-6 rounded-lg w-full max-w-lg">
            <div class="flex justify-between items-center mb-4">
              <h2 id="order-modal-title" class="text-lg font-bold">Order Details</h2>
              <button onclick="closeOrderDetailsModal()" class="text-gray-500 hover:text-gray-700">
                <i class="fa-solid fa-xmark text-xl"></i>
              </button>
            </div>
            <div id="order-modal-content" class="text-gray-700 space-y-2">
              <!-- Filled dynamically -->
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>

  <script>
    // Mock data for demonstration
    let employees = [{
        id: 1,
        code: "EMP001",
        first_name: "John",
        last_name: "Doe",
        active: "Yes",
        employee_image: "../assets/default_profile.png"
      },
      {
        id: 100,
        code: "EMP100",
        first_name: "Emily",
        last_name: "Myers",
        active: "Yes",
        employee_image: "../assets/default_profile.png"
      }
    ];

    let currentPage = 1;
    const defaultMaxRows = 100;
    let maxRows = defaultMaxRows;
    let isLoadAll = false;
    let totalRecords = 1000; // Mock total records
    const clientCode = "agile_jewelries";

    // Sidebar Toggle for Mobile
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

    // Close user menu when clicking outside
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
      icon.innerHTML = ul.classList.contains('hidden') ?
        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />' :
        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />';
    }

    // Get header checkbox

    const selectAll = document.getElementById('select-all');

    if (selectAll) {
      // Toggle all when header is clicked
      selectAll.addEventListener('change', function() {
        document.querySelectorAll('.row-checkbox').forEach(cb => {
          cb.checked = this.checked;
        });
      });

      // Use event delegation for row checkboxes
      document.addEventListener('change', function(e) {
        if (e.target.classList.contains('row-checkbox')) {
          const all = document.querySelectorAll('.row-checkbox');
          const checked = document.querySelectorAll('.row-checkbox:checked');
          selectAll.checked = all.length === checked.length;
        }
      });
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
    async function logout() {
      try {
        // Call logout.php with confirmation
        const res = await fetch('logout.php?confirm=true', {
          method: 'GET',
          headers: {
            'X-Requested-With': 'XMLHttpRequest'
          }
        });

        // Since PHP redirects, we manually force redirect in JS
        if (res.ok) {
          window.location.href = 'login.php';
        }
      } catch (error) {
        console.error(`${new Date()} Logout failed: ${error.message}`);
      }
    }

    // function renderEmployeeTable(data) {
    //   const tbody = document.getElementById('employee-table');
    //   tbody.innerHTML = '';
    //   data.forEach(item => {
    //     const row = document.createElement('tr');
    //     row.innerHTML = `
    //       <td class="px-2 py-1 text-center">
    //         <input type="checkbox"
    //           class="row-checkbox h-4 w-4 text-teal-600 border-gray-300 rounded focus:ring-teal-500 cursor-pointer">
    //       </td>
    //       <td class="p-2">${item.first_name} ${item.last_name}</td>
    //       <td class="p-2">—</td>
    //       <td class="p-2">—</td>
    //       <td class="p-2 text-center">—</td>
    //       <td class="p-2 text-center">—</td>
    //       <td class="p-2">—</td>
    //       <td class="">
    //         <span class="text-xs font-semibold px-2.5 py-0.5 rounded bg-gray-100 text-gray-800">—</span>
    //       </td>
    //       <td class="text-start">
    //         <button class="text-center text-teal-600 hover:text-teal-800">
    //           <i class="fa-solid fa-arrow-up-right-from-square text-xl"></i>
    //         </button>
    //       </td>
    //     `;
    //     tbody.appendChild(row);
    //   });
    // }

    // async function loadData(page, searchQuery = '', showInactive = false, rows = maxRows) {
    //   console.log('Loading data...', {
    //     page,
    //     searchQuery,
    //     showInactive,
    //     rows
    //   });
    //   renderEmployeeTable(employees);
    //   document.getElementById('page-info').textContent = `Page ${page}`;
    // }

    // function openEmployeeDashboard(id, name) {
    //   const modal = document.getElementById('employee-dashboard-modal');
    //   const modalTitle = document.getElementById('modal-title');
    //   const modalContent = document.getElementById('employee-dashboard-content');
    //   modalTitle.textContent = `Employee Productivity Dashboard - ${name}`;
    //   modalContent.innerHTML = `<p class="text-gray-600">Productivity data for employee ID ${id}</p>`;
    //   modal.classList.remove('hidden');
    //   window.history.pushState({}, '', `/${clientCode}/app/reports/employee_productivity_dashboard/${id}`);
    // }

    // function closeEmployeeDashboardModal() {
    //   document.getElementById('employee-dashboard-modal').classList.add('hidden');
    //   window.history.pushState({}, '', `/${clientCode}/app/reports/employee_productivity_dashboard`);
    // }

    // Search Functionality
    // const searchInput = document.getElementById('search-input');
    // const clearSearch = document.getElementById('clear-search');
    // const searchButton = document.getElementById('search-button');
    // const searchSubmit = document.getElementById('search-submit');

    // searchInput.addEventListener('input', (e) => {
    //   clearSearch.classList.toggle('hidden', e.target.value === '');
    //   if (e.target.value === '') {
    //     loadData(1);
    //     window.history.pushState({}, '', `/${clientCode}/app/reports/employee_productivity_dashboard`);
    //   }
    // });

    // searchInput.addEventListener('keypress', (e) => {
    //   if (e.key === 'Enter') {
    //     loadData(1, searchInput.value);
    //     window.history.pushState({}, '', `/${clientCode}/app/reports/employee_productivity_dashboard?search=${encodeURIComponent(searchInput.value)}`);
    //   }
    // });

    // clearSearch.addEventListener('click', () => {
    //   searchInput.value = '';
    //   clearSearch.classList.add('hidden');
    //   loadData(1);
    //   window.history.pushState({}, '', `/${clientCode}/app/reports/employee_productivity_dashboard`);
    // });

    // searchButton.addEventListener('click', () => {
    //   loadData(1, searchInput.value);
    //   window.history.pushState({}, '', `/${clientCode}/app/reports/employee_productivity_dashboard?search=${encodeURIComponent(searchInput.value)}`);
    // });

    // searchSubmit.addEventListener('click', () => {
    //   loadData(1, searchInput.value);
    //   window.history.pushState({}, '', `/${clientCode}/app/reports/employee_productivity_dashboard?search=${encodeURIComponent(searchInput.value)}`);
    // });

    // // Pagination
    // document.getElementById('prev-page').addEventListener('click', () => {
    //   if (currentPage > 1) {
    //     currentPage--;
    //     loadData(currentPage, searchInput.value);
    //   }
    // });

    // document.getElementById('next-page').addEventListener('click', () => {
    //   if (currentPage < Math.ceil(totalRecords / maxRows)) {
    //     currentPage++;
    //     loadData(currentPage, searchInput.value);
    //   }
    // });

    // document.getElementById('load-all').addEventListener('click', () => {
    //   if (isLoadAll) {
    //     maxRows = defaultMaxRows;
    //     isLoadAll = false;
    //     document.getElementById('load-all').textContent = 'Load All';
    //   } else {
    //     maxRows = totalRecords;
    //     isLoadAll = true;
    //     document.getElementById('load-all').textContent = 'Reset';
    //   }
    //   currentPage = 1;
    //   loadData(currentPage, searchInput.value);
    //   window.history.pushState({}, '', `/${clientCode}/app/reports/employee_productivity_dashboard`);
    // });

    // Dynamic Work Area Height
    const workArea = document.getElementById('work-area');

    function resizeWorkArea() {
      workArea.style.height = (window.innerHeight - 84) + 'px';
    }
    window.addEventListener('resize', resizeWorkArea);
    resizeWorkArea();

    // Initial Load
    // loadData(currentPage);

    // Handle URL Parameters
    // window.addEventListener('popstate', () => {
    //   const params = new URLSearchParams(window.location.search);
    //   const search = params.get('search') || '';
    //   const id = window.location.pathname.split('/').pop();
    //   searchInput.value = search;
    //   clearSearch.classList.toggle('hidden', search === '');
    //   if (id && !isNaN(id)) {
    //     const employee = employees.find(emp => emp.id === parseInt(id));
    //     if (employee) {
    //       openEmployeeDashboard(employee.id, `${employee.first_name} ${employee.last_name}`);
    //     }
    //   } else {
    //     loadData(currentPage, search);
    //   }
    // });

    function openOrderDetails(order) {
      const modal = document.getElementById('order-details-modal');
      const modalTitle = document.getElementById('order-modal-title');
      const modalContent = document.getElementById('order-modal-content');

      modalTitle.textContent = `Order Details - #${order.id}`;
      modalContent.innerHTML = `
        <p><strong>Customer:</strong> ${order.customer_name}</p>
        <p><strong>Phone:</strong> ${order.phone_number}</p>
        <p><strong>Address:</strong> ${order.address}, ${order.barangay}, ${order.city}, ${order.province}</p>
        <p><strong>Men Set:</strong> ${order.men_set}</p>
        <p><strong>Women Set:</strong> ${order.women_set}</p>
        <p><strong>Status:</strong> ${order.order_status}</p>
      `;

      modal.classList.remove('hidden');
      // window.history.pushState({}, '', `/app/orders/${order.id}`);
    }

    function closeOrderDetailsModal() {
      document.getElementById('order-details-modal').classList.add('hidden');
      // window.history.pushState({}, '', `/app/orders`);
    }
  </script>
</body>

</html>