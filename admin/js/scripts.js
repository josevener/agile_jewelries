document.addEventListener('DOMContentLoaded', () => {
    // Initialize Lucide icons
    lucide.createIcons();

    // Sidebar toggle for mobile
    const sidebar = document.getElementById('sidebar');
    const openBtn = document.getElementById('open-sidebar');
    const closeBtn = document.getElementById('close-sidebar');
    if (openBtn && closeBtn && sidebar) {
        openBtn.addEventListener('click', () => {
            sidebar.classList.remove('-translate-x-full');
            openBtn.setAttribute('aria-expanded', 'true');
        });
        closeBtn.addEventListener('click', () => {
            sidebar.classList.add('-translate-x-full');
            openBtn.setAttribute('aria-expanded', 'false');
        });
    }

    // Avatar dropdown
    const avatarBtn = document.getElementById('avatar-btn');
    const avatarDropdown = document.getElementById('avatar-dropdown');
    if (avatarBtn && avatarDropdown) {
        avatarBtn.addEventListener('click', () => {
            avatarDropdown.classList.toggle('hidden');
            const isOpen = !avatarDropdown.classList.contains('hidden');
            avatarBtn.setAttribute('aria-expanded', isOpen);
        });
        document.addEventListener('click', (e) => {
            if (!avatarBtn.contains(e.target) && !avatarDropdown.contains(e.target)) {
                avatarDropdown.classList.add('hidden');
                avatarBtn.setAttribute('aria-expanded', 'false');
            }
        });
    }

    // Action dropdowns
    document.querySelectorAll('.action-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const menu = btn.nextElementSibling;
            if (menu) {
                menu.classList.toggle('hidden');
                btn.setAttribute('aria-expanded', !menu.classList.contains('hidden'));
            }
        });
    });
    document.addEventListener('click', (e) => {
        document.querySelectorAll('.action-menu').forEach(menu => {
            if (menu.previousElementSibling && !menu.previousElementSibling.contains(e.target) && !menu.contains(e.target)) {
                menu.classList.add('hidden');
                menu.previousElementSibling.setAttribute('aria-expanded', 'false');
            }
        });
    });

    // Dark mode toggle
    const themeToggle = document.getElementById('theme-toggle');
    if (themeToggle) {
        themeToggle.addEventListener('click', () => {
            document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light');
        });
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.classList.add('dark');
        }
    }

    // Sales chart for dashboard
    const ctx = document.getElementById('sales-chart')?.getContext('2d');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Revenue',
                    data: [5000, 6000, 5500, 7000, 6500, 7500],
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.2)',
                    fill: true,
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    // Order modal handling
    const orderModal = document.getElementById('order-modal');
    const closeModalBtn = document.getElementById('close-modal');
    if (orderModal && closeModalBtn) {
        const orders = JSON.parse(document.getElementById('orders-data')?.textContent || '[]');
        document.querySelectorAll('.view-order').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const orderId = btn.getAttribute('data-order-id');
                const order = orders.find(o => o.id == orderId);
                if (order) {
                    const orderDetails = document.getElementById('order-details');
                    const status = order.created_at === '0000-00-00 00:00:00' ? 'Pending' : 'Shipped';
                    orderDetails.innerHTML = `
                        <div class="space-y-2">
                            <p><strong>Order ID:</strong> ${order.id}</p>
                            <p><strong>Customer Name:</strong> ${order.customer_name}</p>
                            <p><strong>Phone:</strong> ${order.phone_number}</p>
                            <p><strong>Address:</strong> ${order.address}, ${order.barangay}, ${order.city}, ${order.province}</p>
                            <p><strong>Men's Set:</strong> ${order.men_set}</p>
                            <p><strong>Women's Set:</strong> ${order.women_set}</p>
                            <p><strong>IP Address:</strong> ${order.ip_address}</p>
                            <p><strong>Status:</strong> ${status}</p>
                            <div class="mt-4">
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Order Timeline</h3>
                                <div class="space-y-4 mt-2">
                                    <div class="flex items-center gap-3">
                                        <i data-lucide="check-circle" class="w-5 h-5 text-success"></i>
                                        <span>Order placed - ${order.created_at}</span>
                                    </div>
                                    ${status === 'Shipped' ? `
                                        <div class="flex items-center gap-3">
                                            <i data-lucide="truck" class="w-5 h-5 text-warning"></i>
                                            <span>Order shipped - ${order.updated_at}</span>
                                        </div>
                                    ` : ''}
                                </div>
                            </div>
                        </div>
                    `;
                    lucide.createIcons();
                    orderModal.classList.remove('hidden');
                }
            });
        });
        closeModalBtn.addEventListener('click', () => {
            orderModal.classList.add('hidden');
        });
        orderModal.addEventListener('click', (e) => {
            if (e.target === orderModal) {
                orderModal.classList.add('hidden');
            }
        });
    }

    // Export buttons for orders
    ['export-csv', 'export-pdf'].forEach(id => {
        const btn = document.getElementById(id);
        if (btn) {
            btn.addEventListener('click', () => {
                alert(`${id === 'export-csv' ? 'CSV' : 'PDF'} export functionality available in dynamic version.`);
            });
        }
    });
});