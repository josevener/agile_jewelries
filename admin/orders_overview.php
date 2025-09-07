<?php
require_once '../config/database.php';
require_once 'auth.php';
checkAuth();

// Set default date range to current month
$defaultFromDate = (new DateTime('first day of this month'))->format('Y-m-d');
$defaultToDate = (new DateTime('last day of this month'))->format('Y-m-d');

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$fromDate = isset($_GET['from_date']) && !empty($_GET['from_date']) ? trim($_GET['from_date']) : $defaultFromDate;
$toDate = isset($_GET['to_date']) && !empty($_GET['to_date']) ? trim($_GET['to_date']) : $defaultToDate;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$perPage = 25; // Orders per page

try {
  // Validate dates
  $fromDateTime = new DateTime($fromDate);
  $toDateTime = new DateTime($toDate);
  if ($fromDateTime > $toDateTime) {
    throw new Exception('From date cannot be later than To date.');
  }

  // Count total orders for pagination
  $countQuery = 'SELECT COUNT(*) FROM orders';
  $conditions = [];
  $params = [];
  if ($search !== '') {
    $conditions[] = '(customer_name LIKE ? OR phone_number LIKE ? OR address LIKE ? OR order_status LIKE ? OR barangay LIKE ? OR city LIKE ? OR province LIKE ?)';
    $searchParam = "%$search%";
    $params = array_fill(0, 7, $searchParam);
  }
  if ($fromDate && $toDate) {
    $conditions[] = 'created_at BETWEEN ? AND ?';
    $params[] = $fromDate . ' 00:00:00';
    $params[] = $toDate . ' 23:59:59';
  }
  if (!empty($conditions)) {
    $countQuery .= ' WHERE ' . implode(' AND ', $conditions);
  }
  $stmt = $pdo->prepare($countQuery);
  $stmt->execute($params);
  $totalOrders = $stmt->fetchColumn();
  $totalPages = max(1, ceil($totalOrders / $perPage));

  // Adjust page if out of bounds
  $page = min($page, $totalPages);

  // Fetch orders for current page
  $query = 'SELECT * FROM orders';
  if (!empty($conditions)) {
    $query .= ' WHERE ' . implode(' AND ', $conditions);
  }
  $query .= ' ORDER BY id DESC LIMIT ? OFFSET ?';
  $params[] = $perPage;
  $params[] = ($page - 1) * $perPage;

  $stmt = $pdo->prepare($query);
  $stmt->execute($params);
  $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

  $orders = array_map(function ($o) {
    $o['men_set'] = $o['men_set'] ? 'Yes' : 'No';
    $o['women_set'] = $o['women_set'] ? 'Yes' : 'No';
    return $o;
  }, $orders);
} catch (Exception $e) {
  $error = $e instanceof PDOException ? 'Database error: ' . $e->getMessage() : $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Agile Jewelries - Orders Overview</title>
  <link rel="stylesheet" href="../css/output.css">
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
    <aside id="sidebar"
      class="bg-white w-64 lg:w-64 fixed lg:static top-16 bottom-0 left-0 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out z-10">
      <?php include 'components/SideBar.php'; ?>
    </aside>

    <!-- Work Area -->
    <main class="flex-1 bg-gray-200 rounded-t-xl overflow-auto">
      <div id="work-area" class="p-4" style="height: calc(100vh - 84px);">
        <!-- Title Bar with Date Filters -->
        <div class="mb-4 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
          <div>
            <h2 class="text-2xl font-bold text-gray-800">Orders Overview</h2>
            <p class="text-sm text-gray-600">Manage and track all customer orders in one place.</p>
          </div>
          <div class="flex flex-col sm:flex-row gap-2 items-start sm:items-end">
            <div>
              <label class="block text-sm text-gray-600 mb-1">From</label>
              <input type="date" id="from-date"
                value="<?= htmlspecialchars($fromDate) ?>"
                class="border border-gray-300 rounded-md p-2 text-sm w-full sm:w-40">
            </div>
            <div>
              <label class="block text-sm text-gray-600 mb-1">To</label>
              <input type="date" id="to-date"
                value="<?= htmlspecialchars($toDate) ?>"
                class="border border-gray-300 rounded-md p-2 text-sm w-full sm:w-40">
            </div>
            <button id="filter-date-btn"
              class="mt-6 sm:mt-0 border border-teal-600 text-teal-600 px-4 py-2 rounded-md text-sm hover:bg-teal-50 flex items-center gap-2">
              <i class="fa-solid fa-filter"></i>
              <span>Filter</span>
            </button>
            <button id="reset-date-btn"
              class="mt-6 sm:mt-0 border border-teal-600 text-teal-600 px-4 py-2 rounded-md text-sm hover:bg-teal-50 flex items-center gap-2">
              <i class="fa-solid fa-rotate-left"></i>
              <span>Reset</span>
            </button>
          </div>
        </div>

        <!-- Search Bar -->
        <div class="bg-white rounded-t-lg p-2 flex flex-row gap-2">
          <form id="search-form" method="get" action="orders_overview.php" class="flex gap-2 flex-1">
            <div class="relative flex-1 max-w-[326px]">
              <input id="search-input" name="search" type="text"
                placeholder="Search by name, phone, address, or status"
                value="<?= htmlspecialchars($search) ?>"
                class="w-full border border-gray-300 rounded-md p-2 pr-20 text-sm">
              <div class="absolute inset-y-0 right-0 flex items-center space-x-1 pr-2">
                <?php if ($search): ?>
                  <button type="button" id="clear-search" class="text-gray-500 hover:text-gray-700">
                    <i class="fa-solid fa-xmark"></i>
                  </button>
                <?php endif; ?>
                <button type="submit" id="search-icon-btn" class="text-gray-500 hover:text-gray-700">
                  <i class="fa-solid fa-magnifying-glass"></i>
                </button>
              </div>
            </div>
            <input type="hidden" name="from_date" id="form-from-date">
            <input type="hidden" name="to_date" id="form-to-date">
            <button type="submit" id="search-submit"
              class="border border-teal-600 text-teal-600 px-4 py-1 rounded-md text-sm hover:bg-teal-50 flex items-center gap-2">
              <span>Search</span>
              <span id="search-btn-loader" class="hidden">
                <i class="fa-solid fa-spinner animate-spin"></i>
              </span>
            </button>
          </form>
          <button id="change-status-btn"
            class="ml-auto bg-teal-600 text-white px-4 py-1 rounded-md text-sm hover:bg-teal-700 disabled:opacity-50 disabled:cursor-not-allowed"
            disabled>
            Change Status
          </button>
        </div>

        <!-- Orders Table -->
        <div class="bg-white rounded-b-lg shadow-md flex flex-col" style="height: calc(100vh - 220px);">
          <div class="overflow-auto flex-1">
            <table id="orders-table" class="w-full text-sm text-left text-gray-600 mx-auto">
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
                      <input type="checkbox" data-id="<?= htmlspecialchars($order['id']) ?>"
                        <?php echo ($order['order_status'] === 'completed') ? 'disabled' : ''; ?>
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
                      <span class="text-xs font-semibold px-2.5 py-0.5 rounded <?= $statusClass; ?>">
                        <?= htmlspecialchars(ucfirst($status)); ?>
                      </span>
                    </td>
                    <td class="text-start">
                      <button class="text-center text-teal-600 hover:text-teal-800"
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
            <p class="text-sm text-gray-600">
              Showing <?= count($orders) ?> of <?= $totalOrders ?> orders
            </p>
            <div class="flex space-x-2 items-center">
              <a href="orders_overview.php?<?= http_build_query(['search' => $search, 'from_date' => $fromDate, 'to_date' => $toDate, 'page' => $page - 1]) ?>"
                class="px-4 py-2 bg-gray-200 rounded-md text-sm disabled:opacity-50 disabled:cursor-not-allowed"
                <?= $page <= 1 ? 'disabled' : '' ?>>Previous</a>
              <?php
              $pagesToShow = [1, 2, 3, 4, 5, 6, 100, 200, 300];
              $pagesToShow = array_filter($pagesToShow, fn($p) => $p <= $totalPages);
              foreach ($pagesToShow as $p):
                if ($p > $totalPages) continue;
              ?>
                <a href="orders_overview.php?<?= http_build_query(['search' => $search, 'from_date' => $fromDate, 'to_date' => $toDate, 'page' => $p]) ?>"
                  class="px-3 py-1 rounded-md text-sm <?= $p === $page ? 'bg-teal-600 text-white' : 'bg-gray-200' ?>">
                  <?= $p ?>
                </a>
              <?php endforeach; ?>
              <a href="orders_overview.php?<?= http_build_query(['search' => $search, 'from_date' => $fromDate, 'to_date' => $toDate, 'page' => $page + 1]) ?>"
                class="px-4 py-2 bg-gray-200 rounded-md text-sm disabled:opacity-50 disabled:cursor-not-allowed"
                <?= $page >= $totalPages ? 'disabled' : '' ?>>Next</a>
            </div>
          </div>
        </div>

        <!-- Order Details Modal -->
        <div id="order-details-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
          <div class="bg-white p-6 rounded-lg shadow-md w-full max-w-lg">
            <div class="flex justify-between items-center mb-4">
              <h2 id="order-modal-title" class="text-xl font-bold text-gray-800">Order Details</h2>
              <button onclick="closeOrderDetailsModal()" class="text-gray-500 hover:bg-gray-100 p-2 rounded-full transition">
                <i class="fa-solid fa-xmark text-2xl"></i>
              </button>
            </div>
            <div id="order-modal-content" class="text-gray-700 grid grid-cols-1 sm:grid-cols-2 gap-4">
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
                <i class="fa-solid fa-spinner animate-spin text-teal-600 mr-2"></i>
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

        <!-- Feedback Modal -->
        <div id="feedback-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
          <div id="feedback-box" class="bg-white p-6 rounded-lg shadow-lg w-full max-w-sm text-center">
            <div class="flex flex-col items-center">
              <i id="feedback-icon" class="fa-solid fa-circle-info text-3xl mb-2"></i>
              <h3 id="feedback-title" class="text-lg font-semibold mb-2"></h3>
              <p id="feedback-message" class="text-sm text-gray-600 mb-4"></p>
            </div>
            <button onclick="closeFeedbackModal()"
              class="bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700">OK</button>
          </div>
        </div>

        <!-- Filter Loading Modal -->
        <?php include_once 'components/LoadingModal.php'; ?>

      </div>
    </main>
  </div>

  <script>
    const endpoint = "orders_overview.php";
    const defaultFromDate = "<?= htmlspecialchars($defaultFromDate) ?>";
    const defaultToDate = "<?= htmlspecialchars($defaultToDate) ?>";
    let confirmStep = false;
    let resultStep = false;

    const changeStatusBtn = document.getElementById("change-status-btn");
    const confirmStepBtn = document.getElementById("confirm-step-btn");
    const searchInput = document.getElementById("search-input");
    const searchIconBtn = document.getElementById("search-icon-btn");
    const searchSubmit = document.getElementById("search-submit");
    const clearSearch = document.getElementById("clear-search");
    const fromDateInput = document.getElementById("from-date");
    const toDateInput = document.getElementById("to-date");
    const formFromDate = document.getElementById("form-from-date");
    const formToDate = document.getElementById("form-to-date");
    const filterDateBtn = document.getElementById("filter-date-btn");
    const resetDateBtn = document.getElementById("reset-date-btn");
    const selectAll = document.getElementById("select-all");
    const ordersTable = document.getElementById("orders-table");

    // Feedback modal with icons/colors
    function showFeedback(type, message) {
      const modal = document.getElementById("feedback-modal");
      const icon = document.getElementById("feedback-icon");
      const title = document.getElementById("feedback-title");
      const msg = document.getElementById("feedback-message");

      if (type === "success") {
        icon.className = "fa-solid fa-circle-check text-green-500 text-3xl mb-2";
        title.textContent = "Success";
      } else {
        icon.className = "fa-solid fa-circle-xmark text-red-500 text-3xl mb-2";
        title.textContent = "Error";
      }
      msg.textContent = message;
      modal.classList.remove("hidden");
    }

    function closeFeedbackModal() {
      console.log("Closing feedback modal");
      document.getElementById("feedback-modal").classList.add("hidden");
    }

    // Show/hide filter loading modal
    function showFilterLoadingModal() {
      console.log("Showing filter loading modal");
      document.getElementById("loading-modal").classList.remove("hidden");
    }

    function hideFilterLoadingModal() {
      console.log("Hiding filter loading modal");
      document.getElementById("loading-modal").classList.add("hidden");
    }

    // Search/filter functionality
    function performSearch() {
      const query = searchInput.value.trim();
      const fromDate = fromDateInput.value || defaultFromDate;
      const toDate = toDateInput.value || defaultToDate;
      formFromDate.value = fromDate;
      formToDate.value = toDate;

      // Validate dates
      const fromDateTime = new Date(fromDate);
      const toDateTime = new Date(toDate);
      if (fromDate && toDate && fromDateTime > toDateTime) {
        hideFilterLoadingModal();
        showFeedback("error", "From date cannot be later than To date.");
        return;
      }

      // Show loading modal
      showFilterLoadingModal();

      // Construct URL with query parameters
      const params = new URLSearchParams({
        search: query,
        from_date: fromDate,
        to_date: toDate,
        page: 1
      });
      const url = `${endpoint}?${params.toString()}`;

      // Async request
      fetch(url, {
          method: "GET"
        })
        .then(response => {
          if (!response.ok) {
            throw new Error("Network response was not ok");
          }
          return response.text();
        })
        .then(() => {
          window.location.href = url;
        })
        .catch(error => {
          hideFilterLoadingModal();
          showFeedback("error", `Failed to filter orders: ${error.message}`);
        });
    }

    // Reset filters to default
    function resetFilters() {
      searchInput.value = "";
      fromDateInput.value = defaultFromDate;
      toDateInput.value = defaultToDate;
      if (clearSearch) clearSearch.classList.add("hidden");
      // Redirect to endpoint without query parameters
      showFilterLoadingModal();
      fetch(endpoint, {
          method: "GET"
        })
        .then(response => {
          if (!response.ok) {
            throw new Error("Network response was not ok");
          }
          return response.text();
        })
        .then(() => {
          window.location.href = endpoint;
        })
        .catch(error => {
          hideFilterLoadingModal();
          showFeedback("error", `Failed to reset filters: ${error.message}`);
        });
    }

    // Show/hide clear button based on input
    searchInput.addEventListener("input", () => {
      if (clearSearch) {
        clearSearch.classList.toggle("hidden", !searchInput.value);
      }
    });

    // Search icon button click
    searchIconBtn.addEventListener("click", () => {
      performSearch();
    });

    // Search submit button click
    searchSubmit.addEventListener("click", () => {
      performSearch();
    });

    // Enter key in search input
    searchInput.addEventListener("keypress", (e) => {
      if (e.key === "Enter") {
        performSearch();
      }
    });

    // Filter button click
    filterDateBtn.addEventListener("click", () => {
      performSearch();
    });

    // Reset button click
    resetDateBtn.addEventListener("click", () => {
      resetFilters();
    });

    // Clear search button
    if (clearSearch) {
      clearSearch.addEventListener("click", () => {
        resetFilters();
      });
    }

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
      console.log("Change Status button clicked");
      document.getElementById("change-status-modal").classList.remove("hidden");
    });

    // Confirm button logic
    confirmStepBtn.addEventListener("click", () => {
      const newStatus = document.getElementById("new-status").value;
      const checkedBoxes = document.querySelectorAll(".row-checkbox:checked");
      const ids = Array.from(checkedBoxes).map(cb => cb.dataset.id);

      if (ids.length === 0 && !resultStep) {
        showFeedback("error", "No orders selected.");
        return;
      }

      if (!confirmStep && !resultStep) {
        // Step 1: Show confirmation text
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
        document.getElementById("status-confirmation").classList.add("hidden");
        document.getElementById("loading-state").classList.remove("hidden");
        document.querySelectorAll(".cancel-btn, .confirm-btn").forEach(btn => btn.disabled = true);

        // Log the request
        console.log("Sending update request:", {
          order_ids: ids,
          status: newStatus
        });

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
            console.log("Fetch response status:", res.status);
            return res.json();
          })
          .then(data => {
            console.log("Fetch response data:", data);
            document.getElementById("loading-state").classList.add("hidden");
            document.getElementById("result-state").classList.remove("hidden");
            confirmStepBtn.textContent = "Close";
            resultStep = true;

            if (data.success) {
              document.getElementById("result-message").className = "text-sm text-green-700 mb-4";
              document.getElementById("result-message").textContent = `Successfully updated ${data.updated_count} order(s) to ${data.new_status}.`;
              // Auto-close after 2 seconds and reload
              setTimeout(() => {
                console.log("Auto-closing change status modal after success");
                closeChangeStatusModal();
                location.reload();
              }, 2000);
            } else {
              document.getElementById("result-message").className = "text-sm text-red-700 mb-4";
              document.getElementById("result-message").textContent = `Failed to update status: ${data.error || "Unknown error"}`;
              document.querySelectorAll(".cancel-btn, .confirm-btn").forEach(btn => btn.disabled = false);
            }
          })
          .catch(err => {
            console.error("Fetch error:", err);
            document.getElementById("loading-state").classList.add("hidden");
            document.getElementById("result-state").classList.remove("hidden");
            document.getElementById("result-message").className = "text-sm text-red-700 mb-4";
            document.getElementById("result-message").textContent = `Error: ${err.message}`;
            confirmStepBtn.textContent = "Close";
            resultStep = true;
            document.querySelectorAll(".cancel-btn, .confirm-btn").forEach(btn => btn.disabled = false);
          });
      } else if (resultStep) {
        // Step 4: Manual close and reload on success
        console.log("Manual close triggered in result step");
        if (document.getElementById("result-message").className.includes("text-green-700")) {
          location.reload();
        }
        closeChangeStatusModal();
      }
    });

    // Select All checkbox logic
    if (selectAll) {
      selectAll.addEventListener('change', function() {
        console.log("Select All changed:", this.checked);
        document.querySelectorAll('.row-checkbox:not(:disabled)').forEach(cb => {
          cb.checked = this.checked;
        });
        updateChangeStatusBtn();
      });

      // Use event delegation on the table for row checkboxes
      ordersTable.addEventListener('change', function(e) {
        if (e.target.classList.contains('row-checkbox')) {
          console.log("Row checkbox changed:", e.target.dataset.id, e.target.checked);
          const all = document.querySelectorAll('.row-checkbox:not(:disabled)');
          const checked = document.querySelectorAll('.row-checkbox:checked');
          selectAll.checked = all.length === checked.length && all.length > 0;
          updateChangeStatusBtn();
        }
      });
    }

    function updateChangeStatusBtn() {
      const checked = document.querySelectorAll('.row-checkbox:checked').length;
      console.log("Checked boxes:", checked, "Change Status disabled:", checked === 0);
      changeStatusBtn.disabled = checked === 0;
    }

    function openOrderDetails(order) {
      const modal = document.getElementById('order-details-modal');
      const modalTitle = document.getElementById('order-modal-title');
      const modalContent = document.getElementById('order-modal-content');

      const statusClass = {
        'pending': 'bg-orange-100 text-orange-800',
        'processed': 'bg-blue-100 text-blue-800',
        'completed': 'bg-green-100 text-green-800',
        'cancelled': 'bg-red-100 text-red-800'
      } [order.order_status] || 'bg-gray-500 text-white';

      modalTitle.textContent = `Order Details - #${order.id}`;
      modalContent.innerHTML = `
                <div class="font-semibold text-gray-800">Customer</div>
                <div>${order.customer_name}</div>
                <div class="font-semibold text-gray-800">Phone</div>
                <div>${order.phone_number}</div>
                <div class="font-semibold text-gray-800">Address</div>
                <div>${order.address}, ${order.barangay}, ${order.city}, ${order.province}</div>
                <div class="font-semibold text-gray-800">Men's Set</div>
                <div>${order.men_set}</div>
                <div class="font-semibold text-gray-800">Women's Set</div>
                <div>${order.women_set}</div>
                <div class="font-semibold text-gray-800">Amount</div>
                <div>₱${parseFloat(order.amount).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</div>
                <div class="font-semibold text-gray-800">Status</div>
                <div><span class="text-xs font-semibold px-2.5 py-0.5 rounded ${statusClass}">${order.order_status.charAt(0).toUpperCase() + order.order_status.slice(1)}</span></div>
            `;
      modal.classList.remove('hidden');
    }

    function closeOrderDetailsModal() {
      console.log("Closing order details modal");
      document.getElementById('order-details-modal').classList.add('hidden');
    }

    // Dynamic Work Area Height
    const workArea = document.getElementById('work-area');

    function resizeWorkArea() {
      workArea.style.height = (window.innerHeight - 84) + 'px';
    }
    window.addEventListener('resize', resizeWorkArea);
    resizeWorkArea();

    // Display error if any
    <?php if (isset($error)): ?>
      showFeedback("error", "<?= htmlspecialchars($error) ?>");
    <?php endif; ?>

    // Initialize checkbox state
    updateChangeStatusBtn();
  </script>
</body>

</html>