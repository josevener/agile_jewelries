<?php 
 include_once "../config/database.php";
?>

<div class="container mx-auto flex items-center justify-between px-4">
    <!-- Logo and Product Name -->
    <div class="flex items-center space-x-2">
        <img src="../assets/agile_bg.png"
            alt="Logo"
            class="w-8 h-8 rounded-full border border-gray-300">
        <h1 class="text-md font-bold text-teal-700">
            Agile Jewelries
        </h1>
    </div>

    <!-- Menu Toggle (Mobile) -->
    <button id="menu-toggle" class="lg:hidden text-teal-600">
        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
        </svg>
    </button>

    <div class="flex-1"></div>
    <!-- Notifications and User Avatar -->
    <div class="flex items-center space-x-4">
        <button class="text-gray-600">
            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
        </button>
        <button id="user-menu-toggle">
            <img src="../assets/default_profile.png" alt="User Avatar" class="w-8 h-8 rounded-full border border-gray-300 cursor-pointer">
        </button>
    </div>
</div>
<!-- User Menu Dropdown -->
<div id="user-menu" class="hidden absolute top-16 right-4 bg-white shadow-lg rounded-md py-2 w-48 z-30">
    <button class="w-full text-left px-4 py-2 hover:bg-gray-100" onclick="showChangePasswordModal()">Change Password</button>
    <button class="w-full text-left px-4 py-2 hover:bg-gray-100" onclick="logout()">Logout</button>
</div>
<!-- Change Password Modal -->
<div id="change-password-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-40">
    <div class="bg-white p-6 rounded-lg w-full max-w-md">
        <h2 class="text-lg font-bold mb-4">Change Password</h2>
        <div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700" for="current-password">Current Password</label>
                <input id="current-password" type="password" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-600">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700" for="new-password">New Password</label>
                <input id="new-password" type="password" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-600">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700" for="confirm-password">Confirm New Password</label>
                <input id="confirm-password" type="password" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-600">
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" class="px-4 py-2 bg-gray-200 rounded-md hover:bg-gray-300" onclick="closeChangePasswordModal()">Cancel</button>
                <button type="button" id="save-password-btn" class="px-4 py-2 bg-teal-600 text-white rounded-md hover:bg-teal-700">Save</button>
            </div>
        </div>
    </div>
</div>
<!-- Message Modal for Success/Error -->
<div id="message-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded-lg w-full max-w-md">
        <h2 id="message-modal-title" class="text-lg font-bold mb-4"></h2>
        <div id="message-modal-content" class="mb-4"></div>
        <div class="flex justify-end">
            <button id="message-modal-close" class="px-4 py-2 bg-gray-200 rounded-md hover:bg-gray-300">Close</button>
        </div>
    </div>
</div>
<!-- Loading Modal -->
<div id="loading-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded-lg flex items-center justify-center">
        <div class="animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-teal-600"></div>
        <span class="ml-3 text-gray-700">Processing...</span>
    </div>
</div>

<script>
    (function() {
        // Message Modal Logic
        function showModal(title, message) {
            console.log("showModal called with title:", title, "message:", message);
            const modal = document.getElementById("message-modal");
            const modalTitle = document.getElementById("message-modal-title");
            const modalContent = document.getElementById("message-modal-content");
            if (modal && modalTitle && modalContent) {
                modalTitle.textContent = title;
                modalContent.innerHTML = message;
                modal.classList.remove("hidden");
            } else {
                console.error("Message modal elements not found");
            }
        }

        function closeModal() {
            console.log("closeModal called");
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
            console.log("showLoadingModal called");
            const loadingModal = document.getElementById("loading-modal");
            if (loadingModal) {
                loadingModal.classList.remove("hidden");
            }
        }

        function hideLoadingModal() {
            console.log("hideLoadingModal called");
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
            console.log("showChangePasswordModal called");
            if (changePasswordModal && userMenu) {
                changePasswordModal.classList.remove("hidden");
                userMenu.classList.add("hidden");
                if (currentPasswordInput) currentPasswordInput.focus();
            }
        };

        window.closeChangePasswordModal = function() {
            console.log("closeChangePasswordModal called");
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
                console.log("User menu toggled");
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
                console.log("Save password button clicked");
                const currentPassword = currentPasswordInput.value.trim();
                const newPassword = newPasswordInput.value.trim();
                const confirmPassword = confirmPasswordInput.value.trim();

                const errors = [];
                if (!currentPassword) {
                    errors.push("Current password is required.");
                    currentPasswordInput.classList.add("border-red-500");
                } 
                else {
                    currentPasswordInput.classList.remove("border-red-500");
                }

                if (!newPassword) {
                    errors.push("New password is required.");
                    newPasswordInput.classList.add("border-red-500");
                } 
                else if (newPassword.length < 8) {
                    errors.push("New password must be at least 8 characters long.");
                    newPasswordInput.classList.add("border-red-500");
                } 
                else {
                    newPasswordInput.classList.remove("border-red-500");
                }

                if (newPassword !== confirmPassword) {
                    errors.push("New password and confirmation do not match.");
                    confirmPasswordInput.classList.add("border-red-500");
                } 
                else {
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

                // Show loading modal
                showLoadingModal();

                try {
                    console.log("Sending request to change_password.php");
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
                    console.log("Response from change_password.php:", data);

                    if (data.success) {
                        showModal("Success", data.message);
                        closeChangePasswordModal();
                    } 
                    else {
                        showModal(
                            "Change Password Error",
                            "<ul class='list-disc pl-5'>" +
                            data.errors.map(err => `<li>${err}</li>`).join("") +
                            "</ul>"
                        );
                    }
                } 
                catch (err) {
                    console.error("Change password failed:", err);
                    showModal(
                        "Network Error",
                        "Could not connect to the server. Please try again later." .err
                    );
                } 
                finally {
                    hideLoadingModal();
                    savePasswordBtn.textContent = "Save";
                    savePasswordBtn.disabled = false;
                    savePasswordBtn.classList.remove("opacity-50", "cursor-not-allowed");
                }
            });
        } 
        else {
            console.error("Required elements not found:", {
                savePasswordBtn: !!savePasswordBtn,
                changePasswordForm: !!changePasswordForm,
                currentPasswordInput: !!currentPasswordInput,
                newPasswordInput: !!newPasswordInput,
                confirmPasswordInput: !!confirmPasswordInput
            });
        }

        // Logout Function
        window.logout = function() {
            console.log("Logout called");
            window.location.href = "logout.php";
        };
    })();
</script>