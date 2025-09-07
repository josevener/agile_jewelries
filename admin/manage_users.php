<?php
require_once '../config/database.php';
require_once 'auth.php';
checkAuth();

$currentUserId = $_SESSION['user_id'] ?? null;

try {
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';

    $query = 'SELECT * FROM users WHERE id != 1 AND STATUS = "active"';
    if ($search !== '') {
        $query .= ' WHERE first_name LIKE ?
                OR middle_name LIKE ?
                OR last_name LIKE ?
                OR email LIKE ?
                OR phone_number LIKE ?
                OR STATUS LIKE ? ';
    }
    $query .= ' ORDER BY id DESC';

    $stmt = $pdo->prepare($query);

    if ($search !== '') {
        $searchParam = "%$search%";
        $values = array_fill(0, 6, $searchParam);
        $stmt->execute($values);
    } else {
        $stmt->execute();
    }

    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = 'Database error: ' . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agile Jewelries - Users Management</title>
    <link rel="stylesheet" href="../css/output.css">
    <link rel="stylesheet" href="../assets/fontawesome/css/all.min.css">
</head>

<body class="bg-white font-sans antialiased h-screen overflow-hidden">
    <header class="bg-white h-16 flex items-center fixed top-0 left-0 right-0 z-20">
        <?php include 'components/TopBar.php'; ?>
    </header>

    <div class="flex min-h-[calc(100vh-4rem)] mt-16">
        <aside id="sidebar"
            class="bg-white w-64 lg:w-64 fixed lg:static top-16 bottom-0 left-0 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out z-10">
            <?php include 'components/SideBar.php'; ?>
        </aside>

        <main class="flex-1 bg-gray-200 rounded-t-xl overflow-auto">
            <div id="work-area" class="p-4" style="height: calc(100vh - 84px);">
                <div class="mb-4">
                    <h2 class="text-2xl font-bold text-gray-800">Users Management</h2>
                    <p class="text-sm text-gray-600">Manage user accounts easily.</p>
                </div>

                <!-- Search -->
                <div class="bg-white rounded-t-lg p-2 flex flex-row gap-2">
                    <form id="search-form" method="get" action="manage_users.php" class="flex gap-2">
                        <div class="relative flex-1 max-w-[326px]">
                            <input id="search-input" name="search" type="text"
                                placeholder="Search by name, email, phone"
                                value="<?= htmlspecialchars($search) ?>"
                                class="w-full border border-gray-300 rounded-md p-2 pr-20 text-sm">
                            <div class="absolute inset-y-0 right-0 flex items-center space-x-1 pr-2">
                                <?php if ($search): ?>
                                    <a href="manage_users.php" class="text-gray-500 hover:text-gray-700">
                                        <i class="fa-solid fa-xmark"></i>
                                    </a>
                                <?php endif; ?>
                                <button type="submit" class="text-gray-500 hover:text-gray-700">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </button>
                            </div>
                        </div>
                        <button type="submit"
                            class="border border-teal-600 text-teal-600 px-4 py-1 rounded-md text-sm hover:bg-teal-50 flex items-center gap-2">
                            <span>Search</span>
                            <span id="search-btn-loader" class="hidden">
                                <i class="fa-solid fa-spinner animate-spin"></i>
                            </span>
                        </button>
                    </form>
                    <button id="add-user-btn"
                        class="ml-auto bg-teal-600 text-white px-4 py-1 rounded-md text-sm hover:bg-teal-700">
                        Add User
                    </button>
                </div>

                <!-- Users Table -->
                <div class="bg-white rounded-b-lg shadow-md flex flex-col" style="height: calc(100vh - 220px);">
                    <div class="overflow-auto flex-1">
                        <table class="w-full text-sm text-left text-gray-600 mx-auto">
                            <thead class="bg-white">
                                <tr>
                                    <th class="p-2"></th>
                                    <th class="hidden">ID</th>
                                    <th class="p-2">Full Name</th>
                                    <th class="p-2">Email</th>
                                    <th class="p-2">Phone</th>
                                    <th class="p-2">Status</th>
                                    <th class="p-2">Last Login</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <?php foreach ($users as $user): ?>
                                    <?php
                                    $status = $user['STATUS'];
                                    $statusClass = match ($status) {
                                        'active' => 'bg-green-100 text-green-800',
                                        'inactive' => 'bg-gray-200 text-gray-800',
                                        'suspended' => 'bg-red-100 text-red-800',
                                        default => 'bg-gray-500 text-white'
                                    };
                                    ?>
                                    <tr>
                                        <td class="px-4">
                                            <button class="text-teal-600 hover:text-teal-800"
                                                onclick='openUserModal(<?= json_encode($user, JSON_HEX_APOS | JSON_HEX_QUOT) ?>, "view")'>
                                                <i class="fa-solid fa-arrow-up-right-from-square text-xl"></i>
                                            </button>
                                        </td>
                                        <td class="hidden"><?= htmlspecialchars($user['id']) ?></td>
                                        <td class="p-2"><?= htmlspecialchars($user['first_name'] . ' ' . ($user['middle_name'] ?? '') . ' ' . $user['last_name']); ?></td>
                                        <td class="p-2"><?= htmlspecialchars($user['email']); ?></td>
                                        <td class="p-2"><?= htmlspecialchars($user['phone_number']); ?></td>
                                        <td>
                                            <span class="text-xs font-semibold px-2.5 py-0.5 rounded <?= $statusClass; ?>">
                                                <?= htmlspecialchars(ucfirst($status)); ?>
                                            </span>
                                        </td>
                                        <td class="p-2">
                                            <?= !empty($user['last_login']) ? (new DateTime($user['last_login']))->format('F d, Y H:i') : 'Never'; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Loading Modal -->
                <?php include_once 'components/LoadingModal.php'; ?>

                <!-- User Modal -->
                <div id="user-modal"
                    class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                    <div class="bg-white p-6 rounded-xl w-full max-w-4xl shadow-lg">
                        <div class="flex justify-between items-center border-b pb-3 mb-4">
                            <h2 id="user-modal-title" class="text-xl font-semibold text-gray-800">User Management</h2>
                            <button onclick="closeUserModal()" class="text-gray-500 hover:text-gray-700">
                                <i class="fa-solid fa-xmark text-2xl"></i>
                            </button>
                        </div>

                        <form id="user-form" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <input type="hidden" id="user-id">

                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">First Name</label>
                                <input type="text" id="first_name" required
                                    class="w-full border rounded-lg px-3 py-2">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Middle Name</label>
                                <input type="text" id="middle_name" class="w-full border rounded-lg px-3 py-2">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Last Name</label>
                                <input type="text" id="last_name" required class="w-full border rounded-lg px-3 py-2">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Phone</label>
                                <input type="text" id="phone_number" class="w-full border rounded-lg px-3 py-2">
                                <p id="phone-error" class="text-xs text-red-600 mt-1 hidden">
                                    Invalid phone number. Must start with 9 (10 digits) or 09 (11 digits).
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Email</label>
                                <input type="email" id="email" required class="w-full border rounded-lg px-3 py-2">
                                <p id="email-error" class="text-xs text-red-600 mt-1 hidden">
                                    Invalid email format or domain.
                                </p>
                            </div>
                            <div id="password-field" class="hidden relative">
                                <label class="block text-sm font-medium text-gray-600 mb-1">Password</label>
                                <input type="password" id="password"
                                    class="w-full border rounded-lg px-3 py-2 pr-10">
                                <button type="button" id="toggle-password"
                                    class="absolute right-3 top-9 text-gray-500 hover:text-gray-700">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                                <p id="password-error" class="text-xs text-red-600 mt-1 hidden">
                                    Password must contain at least 8 characters, 1 uppercase, 1 lowercase, and 1 number.
                                </p>
                            </div>
                        </form>

                        <div id="view-mode-buttons" class="hidden flex justify-end gap-2 mt-6 border-t pt-4">
                            <button id="edit-user-btn" onclick="enableEdit()"
                                class="px-5 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 flex items-center gap-2">
                                <span>Edit</span>
                            </button>
                            <button id="delete-user-btn"
                                class="px-5 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 flex items-center gap-2">
                                <span>Delete</span>
                                <span id="delete-btn-loader" class="hidden">
                                    <i class="fa-solid fa-spinner animate-spin"></i>
                                </span>
                            </button>
                        </div>

                        <div id="edit-mode-buttons" class="hidden flex justify-end gap-3 mt-6 border-t pt-4">
                            <button onclick="closeUserModal()"
                                class="px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-100">Cancel</button>
                            <button type="submit" form="user-form" id="user-submit-btn"
                                class="px-5 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 flex items-center gap-2">
                                <span id="btn-text">Save</span>
                                <span id="btn-loader" class="hidden">
                                    <i class="fa-solid fa-spinner animate-spin"></i>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Feedback Modal -->
                <div id="feedback-modal"
                    class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
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

                <!-- Confirmation Modal -->
                <div id="confirm-modal"
                    class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-sm text-center">
                        <div class="flex flex-col items-center">
                            <i class="fa-solid fa-circle-question text-yellow-500 text-3xl mb-2"></i>
                            <h3 class="text-lg font-semibold mb-2">Confirm Deletion</h3>
                            <p class="text-sm text-gray-600 mb-4">Are you sure you want to delete this user?</p>
                        </div>
                        <div class="flex justify-center gap-3">
                            <button id="confirm-delete-btn"
                                class="px-5 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 flex items-center gap-2">
                                <span>Confirm</span>
                                <span id="confirm-btn-loader" class="hidden">
                                    <i class="fa-solid fa-spinner animate-spin"></i>
                                </span>
                            </button>
                            <button onclick="closeConfirmModal()"
                                class="px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-100">Cancel</button>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <script>
        const currentUserId = <?= json_encode($currentUserId) ?>;
        const passwordField = document.getElementById("password-field");
        const passwordInput = document.getElementById("password");
        const passwordError = document.getElementById("password-error");
        const togglePassword = document.getElementById("toggle-password");
        const loadingModal = document.getElementById("loading-modal");
        const phoneError = document.getElementById("phone-error");
        const emailError = document.getElementById("email-error");
        const submitBtn = document.getElementById("user-submit-btn");
        const deleteBtn = document.getElementById("delete-user-btn");
        const editBtn = document.getElementById("edit-user-btn");
        const confirmModal = document.getElementById("confirm-modal");
        const confirmDeleteBtn = document.getElementById("confirm-delete-btn");
        let currentMode = '';

        function showLoading() {
            loadingModal.classList.remove("hidden");
        }

        function hideLoading() {
            loadingModal.classList.add("hidden");
        }

        function validatePassword(pwd) {
            const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;
            return regex.test(pwd);
        }

        function validateEmail(email) {
            const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            const domainRegex = /\.[a-zA-Z]{2,}$/;
            return emailRegex.test(email) && domainRegex.test(email);
        }

        function validatePhoneNumber(phone) {
            const phoneRegex = /^(9\d{9}|09\d{9})$/;
            return phoneRegex.test(phone);
        }

        document.getElementById("add-user-btn").addEventListener("click", () => {
            openUserModal(null, "add");
        });

        if (togglePassword) {
            togglePassword.addEventListener("click", () => {
                const type = passwordInput.type === "password" ? "text" : "password";
                passwordInput.type = type;
                togglePassword.innerHTML =
                    type === "password" ?
                    '<i class="fa-solid fa-eye"></i>' :
                    '<i class="fa-solid fa-eye-slash"></i>';
            });
        }

        function openUserModal(user, mode) {
            const modal = document.getElementById("user-modal");
            const title = document.getElementById("user-modal-title");
            const form = document.getElementById("user-form");
            const userIdInput = document.getElementById("user-id");
            const viewModeButtons = document.getElementById("view-mode-buttons");
            const editModeButtons = document.getElementById("edit-mode-buttons");

            currentMode = mode;
            form.reset();
            phoneError.classList.add("hidden");
            emailError.classList.add("hidden");
            passwordError.classList.add("hidden");

            if (mode === "add") {
                title.textContent = "Add User";
                userIdInput.value = "";
                passwordField.classList.remove("hidden");
                viewModeButtons.classList.add("hidden");
                editModeButtons.classList.remove("hidden");
                Array.from(form.elements).forEach(el => el.disabled = false);
            } else if (mode === "view") {
                title.textContent = "View User";
                for (const key in user) {
                    if (document.getElementById(key)) {
                        document.getElementById(key).value = user[key] ?? "";
                    }
                }
                userIdInput.value = user.id;
                passwordField.classList.add("hidden");
                viewModeButtons.classList.remove("hidden");
                editModeButtons.classList.add("hidden");
                Array.from(form.elements).forEach(el => el.disabled = true);
                // Hide Edit and Delete buttons for current user
                if (user.id == currentUserId) {
                    editBtn.classList.add("hidden");
                    deleteBtn.classList.add("hidden");
                } else {
                    editBtn.classList.remove("hidden");
                    deleteBtn.classList.remove("hidden");
                }
            } else if (mode === "edit") {
                title.textContent = "Edit User";
                for (const key in user) {
                    if (document.getElementById(key)) {
                        document.getElementById(key).value = user[key] ?? "";
                    }
                }
                userIdInput.value = user.id;
                passwordField.classList.add("hidden");
                viewModeButtons.classList.add("hidden");
                editModeButtons.classList.remove("hidden");
                Array.from(form.elements).forEach(el => el.disabled = false);
            }
            modal.classList.remove("hidden");
        }

        function enableEdit() {
            const userId = document.getElementById("user-id").value;
            if (userId == currentUserId) {
                showFeedback("error", "You cannot edit your own account.");
                return;
            }
            const user = {
                id: userId,
                first_name: document.getElementById("first_name").value,
                middle_name: document.getElementById("middle_name").value,
                last_name: document.getElementById("last_name").value,
                email: document.getElementById("email").value,
                phone_number: document.getElementById("phone_number").value
            };
            openUserModal(user, "edit");
        }

        function closeUserModal() {
            document.getElementById("user-modal").classList.add("hidden");
        }

        function closeConfirmModal() {
            confirmModal.classList.add("hidden");
        }

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
            document.getElementById("feedback-modal").classList.add("hidden");
        }

        // Attach loading modal on search
        document.getElementById("search-form").addEventListener("submit", () => {
            showLoading();
            document.getElementById("search-btn-loader").classList.remove("hidden");
        });

        document.getElementById("user-form").addEventListener("submit", function(e) {
            e.preventDefault();
            const id = document.getElementById("user-id").value;
            const email = document.getElementById("email").value.trim();
            const phone = document.getElementById("phone_number").value.trim();
            let isValid = true;

            // Email validation
            if (!validateEmail(email)) {
                emailError.classList.remove("hidden");
                isValid = false;
            } else {
                emailError.classList.add("hidden");
            }

            // Phone validation
            if (!validatePhoneNumber(phone)) {
                phoneError.classList.remove("hidden");
                isValid = false;
            } else {
                phoneError.classList.add("hidden");
            }

            // Password validation for add mode
            if (!id && currentMode === "add") {
                const pwd = passwordInput.value;
                if (!validatePassword(pwd)) {
                    passwordError.classList.remove("hidden");
                    isValid = false;
                } else {
                    passwordError.classList.add("hidden");
                }
            }

            if (!isValid) return;

            const payload = {
                id,
                first_name: document.getElementById("first_name").value.trim(),
                middle_name: document.getElementById("middle_name").value.trim(),
                last_name: document.getElementById("last_name").value.trim(),
                email,
                phone_number: phone,
            };

            if (!id) {
                payload.password = passwordInput.value;
            }

            showLoading();
            submitBtn.querySelector("#btn-text").classList.add("hidden");
            submitBtn.querySelector("#btn-loader").classList.remove("hidden");

            fetch("user_actions.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify(payload)
                })
                .then(res => res.json())
                .then(data => {
                    hideLoading();
                    submitBtn.querySelector("#btn-text").classList.remove("hidden");
                    submitBtn.querySelector("#btn-loader").classList.add("hidden");
                    if (data.success) {
                        closeUserModal();
                        showFeedback("success", data.message || "User saved successfully!");
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        showFeedback("error", data.message || "Something went wrong.");
                    }
                })
                .catch(err => {
                    hideLoading();
                    submitBtn.querySelector("#btn-text").classList.remove("hidden");
                    submitBtn.querySelector("#btn-loader").classList.add("hidden");
                    showFeedback("error", err.message || "Network error occurred.");
                });
        });

        deleteBtn.addEventListener("click", () => {
            const userId = document.getElementById("user-id").value;
            if (userId == currentUserId) {
                showFeedback("error", "You cannot delete your own account.");
                return;
            }
            confirmModal.classList.remove("hidden");
        });

        confirmDeleteBtn.addEventListener("click", () => {
            const id = document.getElementById("user-id").value;

            showLoading();
            confirmDeleteBtn.querySelector("#confirm-btn-loader").classList.remove("hidden");
            confirmDeleteBtn.querySelector("span:first-child").classList.add("hidden");

            fetch("user_actions.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        id,
                        action: "delete"
                    })
                })
                .then(res => res.json())
                .then(data => {
                    hideLoading();
                    confirmDeleteBtn.querySelector("#confirm-btn-loader").classList.add("hidden");
                    confirmDeleteBtn.querySelector("span:first-child").classList.remove("hidden");
                    closeConfirmModal();
                    if (data.success) {
                        closeUserModal();
                        showFeedback("success", data.message || "User deleted successfully!");
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        showFeedback("error", data.message || "Failed to delete user.");
                    }
                })
                .catch(err => {
                    hideLoading();
                    confirmDeleteBtn.querySelector("#confirm-btn-loader").classList.add("hidden");
                    confirmDeleteBtn.querySelector("span:first-child").classList.remove("hidden");
                    closeConfirmModal();
                    showFeedback("error", err.message || "Network error occurred.");
                });
        });
    </script>
</body>

</html>