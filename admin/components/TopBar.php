<?php
include_once "../config/database.php";

// Use existing CSRF token or log error if not set
$logout_csrf_token = $_SESSION['logout_csrf_token'] ?? '';
if (!$logout_csrf_token) {
    error_log("CSRF token not found in session for TopBar.php");
} else {
    error_log("CSRF token in TopBar.php: {$logout_csrf_token}");
}
?>

<div class="container mx-auto flex items-center justify-between px-4 sm:px-6 py-3 h-16">
    <!-- Logo, Product Name, and Menu Toggle -->
    <div class="flex items-center space-x-4 sm:space-x-6">
        <div class="flex items-center space-x-2">
            <img src="../assets/agile_bg.png"
                alt="Logo"
                class="w-7 h-7 sm:w-8 sm:h-8 rounded-full border border-gray-300">
            <h1 class="text-sm sm:text-md font-bold text-teal-700">
                Agile Jewelries
            </h1>
        </div>
        <!-- Menu Toggle (Mobile) -->
        <button id="menu-toggle" class="lg:hidden text-teal-600 focus:outline-none">
            <svg id="menu-icon" class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
            </svg>
        </button>
    </div>

    <div class="flex-1"></div>
    <!-- Notifications and User Avatar -->
    <div class="flex items-center space-x-3 sm:space-x-4">
        <button class="text-gray-600 hover:text-teal-600">
            <svg class="w-5 h-5 sm:w-6 sm:h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
        </button>
        <button id="user-menu-toggle" class="focus:outline-none">
            <img src="../assets/default_profile.png" alt="User Avatar" class="w-7 h-7 sm:w-8 sm:h-8 rounded-full border border-gray-300 cursor-pointer">
        </button>
    </div>
</div>

<!-- User Menu Dropdown -->
<div id="user-menu" class="hidden absolute top-16 right-2 sm:right-4 bg-white shadow-lg rounded-md py-2 w-40 sm:w-48 z-30">
    <button class="w-full text-left px-4 py-2 text-sm sm:text-base hover:bg-gray-100" onclick="showChangePasswordModal()">Change Password</button>
    <button id="logout-btn" class="w-full text-left px-4 py-2 text-sm sm:text-base hover:bg-gray-100">Logout</button>
</div>

<!-- Change Password Modal -->
<div id="change-password-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-40">
    <div class="bg-white p-4 sm:p-6 rounded-lg w-full max-w-xs sm:max-w-md mx-2 sm:mx-auto">
        <h2 class="text-lg font-bold mb-4">Change Password</h2>
        <div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700" for="current-password">Current Password</label>
                <input id="current-password" type="password" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-600 text-sm sm:text-base">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700" for="new-password">New Password</label>
                <input id="new-password" type="password" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-600 text-sm sm:text-base">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700" for="confirm-password">Confirm New Password</label>
                <input id="confirm-password" type="password" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-600 text-sm sm:text-base">
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" class="px-3 py-1 sm:px-4 sm:py-2 bg-gray-200 rounded-md hover:bg-gray-300 text-sm sm:text-base" onclick="closeChangePasswordModal()">Cancel</button>
                <button type="button" id="save-password-btn" class="px-3 py-1 sm:px-4 sm:py-2 bg-teal-600 text-white rounded-md hover:bg-teal-700 text-sm sm:text-base">Save</button>
            </div>
        </div>
    </div>
</div>

<!-- Message Modal for Change Password -->
<div id="message-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white p-4 sm:p-6 rounded-lg w-full max-w-xs sm:max-w-md mx-2 sm:mx-auto">
        <h2 id="message-modal-title" class="text-lg font-bold mb-4"></h2>
        <div id="message-modal-content" class="mb-4"></div>
        <div class="flex justify-end">
            <button id="message-modal-close" class="px-3 py-1 sm:px-4 sm:py-2 bg-gray-200 rounded-md hover:bg-gray-300 text-sm sm:text-base">Close</button>
        </div>
    </div>
</div>

<?php include_once 'LoadingModal.php'; ?>

<script>
    (function() {
        // Message Modal Logic (used only for change password)
        function showModal(title, message) {
            const modal = document.getElementById("message-modal");
            const modalTitle = document.getElementById("message-modal-title");
            const modalContent = document.getElementById("message-modal-content");
            if (modal && modalTitle && modalContent) {
                modalTitle.textContent = title;
                modalContent.innerHTML = message;
                modal.classList.remove("hidden");
            }
        }

        function closeModal() {
            const modal = document.getElementById("message-modal");
            if (modal) {
                modal.classList.add("hidden");
                modal.querySelector("#message-modal-content").innerHTML = "";
            }
        }

        const messageModalClose = document.getElementById("message-modal-close");
        if (messageModalClose) {
            messageModalClose.addEventListener("click", closeModal);
        }

        document.getElementById("message-modal")?.addEventListener("click", (e) => {
            if (e.target === document.getElementById("message-modal")) {
                closeModal();
            }
        });

        // Loading Modal Logic
        function showLoadingModal() {
            const loadingModal = document.getElementById("loading-modal");
            if (loadingModal) {
                loadingModal.classList.remove("hidden");
            }
        }

        function hideLoadingModal() {
            const loadingModal = document.getElementById("loading-modal");
            if (loadingModal) {
                loadingModal.classList.add("hidden");
            }
        }

        // Change Password Modal Logic
        const userMenuToggle = document.getElementById("user-menu-toggle");
        const userMenu = document.getElementById("user-menu");
        const changePasswordModal = document.getElementById("change-password-modal");
        const changePasswordForm = changePasswordModal?.querySelector("div > div");
        const currentPasswordInput = document.getElementById("current-password");
        const newPasswordInput = document.getElementById("new-password");
        const confirmPasswordInput = document.getElementById("confirm-password");
        const savePasswordBtn = document.getElementById("save-password-btn");
        const cancelPasswordBtn = changePasswordModal?.querySelector("button.bg-gray-200");

        window.showChangePasswordModal = function() {
            if (changePasswordModal && userMenu) {
                changePasswordModal.classList.remove("hidden");
                userMenu.classList.add("hidden");
                if (currentPasswordInput) currentPasswordInput.focus();
            }
        };

        window.closeChangePasswordModal = function() {
            if (changePasswordModal && changePasswordForm) {
                changePasswordModal.classList.add("hidden");
                changePasswordForm.querySelectorAll("input").forEach(input => {
                    input.value = "";
                    input.classList.remove("border-red-500");
                });
            }
        };

        if (userMenuToggle && userMenu) {
            userMenuToggle.addEventListener("click", () => {
                userMenu.classList.toggle("hidden");
            });
        }

        if (cancelPasswordBtn) {
            cancelPasswordBtn.addEventListener("click", closeChangePasswordModal);
        }

        if (changePasswordModal) {
            changePasswordModal.addEventListener("click", (e) => {
                if (e.target === changePasswordModal) {
                    closeChangePasswordModal();
                }
            });
        }

        if (savePasswordBtn && changePasswordForm && currentPasswordInput && newPasswordInput && confirmPasswordInput) {
            savePasswordBtn.addEventListener("click", async () => {
                const currentPassword = currentPasswordInput.value.trim();
                const newPassword = newPasswordInput.value.trim();
                const confirmPassword = confirmPasswordInput.value.trim();

                const errors = [];
                if (!currentPassword) {
                    errors.push("Current password is required.");
                    currentPasswordInput.classList.add("border-red-500");
                } else {
                    currentPasswordInput.classList.remove("border-red-500");
                }

                if (!newPassword) {
                    errors.push("New password is required.");
                    newPasswordInput.classList.add("border-red-500");
                } else if (newPassword.length < 8) {
                    errors.push("New password must be at least 8 characters long.");
                    newPasswordInput.classList.add("border-red-500");
                } else {
                    newPasswordInput.classList.remove("border-red-500");
                }

                if (newPassword !== confirmPassword) {
                    errors.push("New password and confirmation do not match.");
                    confirmPasswordInput.classList.add("border-red-500");
                } else {
                    confirmPasswordInput.classList.remove("border-red-500");
                }

                if (errors.length > 0) {
                    showModal(
                        "Change Password Error",
                        "<ul class='list-disc pl-5'>" +
                        errors.map(err => `<li>${err}</li>`).join("") +
                        "</ul>"
                    );
                    return;
                }

                showLoadingModal();
                try {
                    const response = await fetch("helpers/change_password.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            current_password: currentPassword,
                            new_password: newPassword,
                            confirm_password: confirmPassword
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        showModal("Success", data.message);
                        closeChangePasswordModal();
                    } else {
                        showModal(
                            "Change Password Error",
                            "<ul class='list-disc pl-5'>" +
                            data.errors.map(err => `<li>${err}</li>`).join("") +
                            "</ul>"
                        );
                    }
                } catch (err) {
                    showModal(
                        "Network Error",
                        "Could not connect to the server. Please try again later."
                    );
                } finally {
                    hideLoadingModal();
                    savePasswordBtn.textContent = "Save";
                    savePasswordBtn.disabled = false;
                    savePasswordBtn.classList.remove("opacity-50", "cursor-not-allowed");
                }
            });
        }

        // Sidebar Toggle for Mobile
        function initializeSidebar() {
            const menuToggle = document.getElementById('menu-toggle');
            const menuIcon = document.getElementById('menu-icon');
            const sidebar = document.getElementById('sidebar');
            const isDesktop = () => window.matchMedia("(min-width: 1024px)").matches;

            if (!menuToggle || !sidebar || !menuIcon) {
                return;
            }

            menuToggle.addEventListener('click', (e) => {
                e.stopPropagation();
                const isOpen = sidebar.classList.contains('translate-x-0');
                sidebar.classList.toggle('translate-x-0', !isOpen);
                sidebar.classList.toggle('-translate-x-full', isOpen);
                sidebar.classList.toggle('opacity-100', !isOpen);
                sidebar.classList.toggle('opacity-0', isOpen);
                menuIcon.innerHTML = isOpen ?
                    `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />` :
                    `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />`;
            });

            // Close sidebar when clicking a link (only on mobile)
            sidebar.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', () => {
                    if (!isDesktop()) {
                        sidebar.classList.remove('translate-x-0');
                        sidebar.classList.add('-translate-x-full');
                        sidebar.classList.remove('opacity-100');
                        sidebar.classList.add('opacity-0');
                        menuIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />`;
                    }
                    // Navigation happens via href, no preventDefault
                });
            });

            // Close sidebar when clicking outside (only on mobile)
            document.addEventListener('click', (e) => {
                if (!isDesktop() && !sidebar.contains(e.target) && !menuToggle.contains(e.target) && !sidebar.classList.contains('-translate-x-full')) {
                    sidebar.classList.remove('translate-x-0');
                    sidebar.classList.add('-translate-x-full');
                    sidebar.classList.remove('opacity-100');
                    sidebar.classList.add('opacity-0');
                    menuIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />`;
                }
            });
        }

        // Wait for DOM to load or retry until sidebar is available
        function waitForSidebar(attempts = 10) {
            if (document.getElementById('sidebar')) {
                initializeSidebar();
            } else if (attempts > 0) {
                setTimeout(() => waitForSidebar(attempts - 1), 100);
            }
        }

        // Initialize sidebar after DOM is loaded
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => waitForSidebar());
        } else {
            waitForSidebar();
        }

        // Logout Function
        const logoutBtn = document.getElementById('logout-btn');
        if (logoutBtn) {
            logoutBtn.addEventListener('click', async () => {
                try {
                    const response = await fetch("logout.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            csrf_token: "<?= htmlspecialchars($logout_csrf_token) ?>"
                        })
                    });
                    if (!response.ok) {
                        const text = await response.text();
                        throw new Error(`Logout request failed with status ${response.status}: ${text}`);
                    }
                    const data = await response.json();
                    document.cookie = "PHPSESSID=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/";
                    window.location.href = "login.php";
                } catch (err) {
                    document.cookie = "PHPSESSID=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/";
                    window.location.href = "login.php";
                } finally {
                    hideLoadingModal();
                }
            });
        }
    })();
</script>