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
    $values = array_fill(0, 7, $searchParam);
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
    <main class="flex-1 bg-gray-200 rounded-t-xl overflow-auto">
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
              placeholder="Search by name, phone, address, or status"
              value="<?= htmlspecialchars($search) ?>"
              class="w-full border border-gray-300 rounded-md p-2 pr-20 text-sm">
            <div class="absolute inset-y-0 right-0 flex items-center space-x-1 pr-2">
              <button id="clear-search" class="<?= $search ? '' : 'hidden' ?> text-gray-500 hover:text-gray-700">
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
          <button id="change-status-btn"
            class="ml-auto bg-teal-600 text-white px-4 py-1 rounded-md text-sm hover:bg-teal-700 disabled:opacity-50 disabled:cursor-not-allowed"
            disabled>
            Change Status
          </button>
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
                  <th class="p-2 hidden">ID</th>
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
              <tbody class="divide-y divide-gray-200">
                <?php foreach ($orders as $order): ?>
                  <?php
                  $status = $order['order_status'];
                  $statusClass = match ($status) {
                    'pending' => 'bg-orange-100 text-orange-800',
                    'processed' => 'bg-blue-100 text-blue-800',
                    'completed' => 'bg-green-100 text-green-800',
                    'cancelled' => 'bg-red-100 text-red-800',
                    default => 'bg-gray-500 text-white'
                  };
                  ?>
                  <tr>
                    <td class="px-2 py-1 text-center">
                      <input type="checkbox" data-id="<?= htmlspecialchars($order['id']) ?>" <?php echo ($order['order_status'] === 'completed') ? 'disabled' : ''; ?>
                        class="row-checkbox h-4 w-4 text-teal-600 border-gray-300 rounded focus:ring-teal-500 cursor-pointer">
                    </td>
                    <td class="hidden">
                      <p><?= htmlspecialchars($order['id']) ?></p>
                    </td>
                    <td class="p-2">
                      <p><?= htmlspecialchars($order['customer_name']); ?></p>
                    </td>
                    <td class="p-2"><?= htmlspecialchars($order['phone_number']); ?></td>
                    <td class="p-2 max-w-[150px] truncate cursor-pointer"
                      title="<?= htmlspecialchars($order['address'] . ', ' . $order['barangay'] . ', ' . $order['city'] . ', ' . $order['province']); ?>">
                      <?= htmlspecialchars($order['address'] . ', ' . $order['barangay'] . ', ' . $order['city'] . ', ' . $order['province']); ?>
                    </td>
                    <td class="p-2 text-center"><?= htmlspecialchars($order['men_set']); ?></td>
                    <td class="p-2 text-center"><?= htmlspecialchars($order['women_set']); ?></td>
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
                        <?= htmlspecialchars(ucfirst($status)); ?>
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

        <!-- Change Status Modal -->
        <div id="change-status-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
          <div class="bg-white p-6 rounded-lg w-full max-w-sm">
            <div class="flex justify-between items-center mb-4">
              <h2 class="text-lg font-bold">Change Order Status</h2>
              <button onclick="closeChangeStatusModal()" class="text-gray-500 hover:text-gray-700 cancel-btn">
                <i class="fa-solid fa-xmark text-xl"></i>
              </button>
            </div>

            <!-- Step 1: Select status -->
            <div id="status-selection">
              <label for="new-status" class="block text-sm text-gray-700 mb-2">Select new status:</label>
              <select id="new-status" class="w-full border border-gray-300 rounded-md p-2 mb-4">
                <option value="pending">Pending</option>
                <option value="processed">Processed</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
              </select>
            </div>

            <!-- Step 2: Confirmation -->
            <div id="status-confirmation" class="hidden">
              <p class="text-sm text-gray-700 mb-4">
                Are you sure you want to change the status of <span id="selected-count" class="font-bold"></span> order(s) to
                <span id="selected-status" class="font-bold text-teal-600"></span>?
              </p>
            </div>

            <!-- Step 3: Loading State -->
            <div id="loading-state" class="hidden">
              <div class="flex items-center justify-center mb-4">
                <svg class="animate-spin h-5 w-5 text-teal-600 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="text-sm text-teal-700">Updating...</p>
              </div>
            </div>

            <!-- Step 4: Result -->
            <div id="result-state" class="hidden">
              <p id="result-message" class="text-sm mb-4"></p>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end space-x-2">
              <button onclick="closeChangeStatusModal()" class="px-4 py-2 border rounded-md cancel-btn">Cancel</button>
              <button id="confirm-step-btn" class="px-4 py-2 bg-teal-600 text-white rounded-md hover:bg-teal-700 confirm-btn">
                Confirm
              </button>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>

  <script>
    const endpoint = "orders_overview.php";
    let confirmStep = false;
    let resultStep = false;

    const changeStatusBtn = document.getElementById("change-status-btn");
    const confirmStepBtn = document.getElementById("confirm-step-btn");
    const searchInput = document.getElementById("search-input");
    const searchButton = document.getElementById("search-button");
    const searchSubmit = document.getElementById("search-submit");
    const clearSearch = document.getElementById("clear-search");

    // Search functionality
    function performSearch() {
      const query = searchInput.value.trim();
      console.log("Performing search with query:", query);
      window.location.href = `${endpoint}${query ? `?search=${encodeURIComponent(query)}` : ''}`;
    }

    // Show/hide clear button based on input
    searchInput.addEventListener("input", () => {
      console.log("Search input changed, value:", searchInput.value);
      clearSearch.classList.toggle("hidden", !searchInput.value);
    });

    // Search button click
    searchButton.addEventListener("click", () => {
      console.log("Search button clicked");
      performSearch();
    });

    // Search submit button click
    searchSubmit.addEventListener("click", () => {
      console.log("Search submit button clicked");
      performSearch();
    });

    // Enter key in search input
    searchInput.addEventListener("keypress", (e) => {
      if (e.key === "Enter") {
        console.log("Enter key pressed in search input");
        performSearch();
      }
    });

    // Clear search button
    clearSearch.addEventListener("click", () => {
      console.log("Clear search button clicked");
      searchInput.value = "";
      clearSearch.classList.add("hidden");
      window.location.href = endpoint;
    });

    function closeChangeStatusModal() {
      console.log("Closing change status modal");
      document.getElementById("change-status-modal").classList.add("hidden");
      document.getElementById("status-selection").classList.remove("hidden");
      document.getElementById("status-confirmation").classList.add("hidden");
      document.getElementById("loading-state").classList.add("hidden");
      document.getElementById("result-state").classList.add("hidden");
      confirmStepBtn.textContent = "Confirm";
      confirmStep = false;
      resultStep = false;
      document.querySelectorAll(".cancel-btn, .confirm-btn").forEach(btn => btn.disabled = false);
    }

    // Open modal
    changeStatusBtn.addEventListener("click", () => {
      console.log("Opening change status modal");
      document.getElementById("change-status-modal").classList.remove("hidden");
    });

    // Confirm button logic
    confirmStepBtn.addEventListener("click", () => {
      console.log("Confirm button clicked, confirmStep:", confirmStep, "resultStep:", resultStep);
      const newStatus = document.getElementById("new-status").value;
      const checkedBoxes = document.querySelectorAll(".row-checkbox:checked");
      const ids = Array.from(checkedBoxes).map(cb => cb.dataset.id);

      if (ids.length === 0 && !resultStep) {
        console.log("No orders selected");
        alert("No orders selected.");
        return;
      }

      if (!confirmStep && !resultStep) {
        // Step 1: Show confirmation text
        console.log("Showing confirmation step, selected orders:", ids.length, "status:", newStatus);
        document.getElementById("selected-count").textContent = ids.length;
        document.getElementById("selected-status").textContent = newStatus;
        document.getElementById("status-selection").classList.add("hidden");
        document.getElementById("status-confirmation").classList.remove("hidden");
        confirmStepBtn.textContent = "Apply";
        confirmStep = true;
        return;
      }

      if (confirmStep && !resultStep) {
        // Step 2: Show loading state
        console.log("Showing loading state, sending AJAX request for IDs:", ids, "status:", newStatus);
        document.getElementById("status-confirmation").classList.add("hidden");
        document.getElementById("loading-state").classList.remove("hidden");
        document.querySelectorAll(".cancel-btn, .confirm-btn").forEach(btn => btn.disabled = true);

        // Step 3: Apply change via AJAX
        fetch("update_order_status.php", {
            method: "POST",
            headers: {
              "Content-Type": "application/json"
            },
            body: JSON.stringify({
              order_ids: ids,
              status: newStatus
            })
          })
          .then(res => {
            console.log("AJAX response received, status:", res.status);
            return res.json();
          })
          .then(data => {
            console.log("AJAX data:", data);
            document.getElementById("loading-state").classList.add("hidden");
            document.getElementById("result-state").classList.remove("hidden");
            confirmStepBtn.textContent = "Close";
            resultStep = true;

            if (data.success) {
              document.getElementById("result-message").className = "text-sm text-green-700 mb-4";
              document.getElementById("result-message").textContent = `Successfully updated ${data.updated_count} order(s) to ${data.new_status}.`;
              console.log("Success, auto-closing in 3 seconds");
              setTimeout(() => {
                console.log("Auto-closing modal and reloading page");
                closeChangeStatusModal();
                location.reload();
              }, 3000);
            } else {
              document.getElementById("result-message").className = "text-sm text-red-700 mb-4";
              document.getElementById("result-message").textContent = `Failed to update status: ${data.error || "Unknown error"}`;
              console.log("Error, auto-closing in 5 seconds");
              setTimeout(() => {
                console.log("Auto-closing modal");
                closeChangeStatusModal();
              }, 5000);
            }
          })
          .catch(err => {
            console.error("AJAX error:", err);
            document.getElementById("loading-state").classList.add("hidden");
            document.getElementById("result-state").classList.remove("hidden");
            document.getElementById("result-message").className = "text-sm text-red-700 mb-4";
            document.getElementById("result-message").textContent = `Error: ${err.message}`;
            confirmStepBtn.textContent = "Close";
            resultStep = true;
            console.log("Error, auto-closing in 5 seconds");
            setTimeout(() => {
              console.log("Auto-closing modal");
              closeChangeStatusModal();
            }, 5000);
          });
      } else if (resultStep) {
        // Step 4: Close modal and reload page on success
        console.log("Result step, closing modal");
        if (document.getElementById("result-message").className.includes("text-green-700")) {
          console.log("Success, reloading page");
          location.reload();
        }
        closeChangeStatusModal();
      }
    });

    // Get header checkbox
    const selectAll = document.getElementById('select-all');

    if (selectAll) {
      // Toggle all when header is clicked
      selectAll.addEventListener('change', function() {
        document.querySelectorAll('.row-checkbox:not(:disabled)').forEach(cb => {
          cb.checked = this.checked;
        });
        updateChangeStatusBtn();
      });

      // Use event delegation for row checkboxes
      document.addEventListener('change', function(e) {
        if (e.target.classList.contains('row-checkbox')) {
          const all = document.querySelectorAll('.row-checkbox:not(:disabled)');
          const checked = document.querySelectorAll('.row-checkbox:checked');
          selectAll.checked = all.length === checked.length;
          updateChangeStatusBtn();
        }
      });
    }

    function updateChangeStatusBtn() {
      const checked = document.querySelectorAll('.row-checkbox:checked').length;
      changeStatusBtn.disabled = checked === 0;
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
      console.log("Initiating logout");
      try {
        const res = await fetch('logout.php?confirm=true', {
          method: 'GET',
          headers: {
            'X-Requested-With': 'XMLHttpRequest'
          }
        });
        if (res.ok) {
          window.location.href = 'login.php';
        }
      } catch (error) {
        console.error(`${new Date()} Logout failed: ${error.message}`);
      }
    }

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
    }

    function closeOrderDetailsModal() {
      document.getElementById('order-details-modal').classList.add('hidden');
    }

    // Dynamic Work Area Height
    const workArea = document.getElementById('work-area');

    function resizeWorkArea() {
      workArea.style.height = (window.innerHeight - 84) + 'px';
    }
    window.addEventListener('resize', resizeWorkArea);
    resizeWorkArea();
  </script>
</body>

</html>