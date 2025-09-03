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
    
    <!-- Portal Badge -->
    <!-- <div class="bg-green-700 text-white font-bold text-sm px-4 py-2 rounded-full ml-5">
        Admin Portal
    </div> -->

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