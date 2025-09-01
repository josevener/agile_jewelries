// // Fetch and display orders
// async function fetchOrders() {
//     try {
//         const response = await fetch('../api/get_orders.php');
//         const data = await response.json();
//         if (data.success) {
//             populateOrdersTable(data.orders);
//         } else {
//             console.error('Failed to fetch orders:', data.error);
//             alert('Failed to fetch orders: ' + data.error);
//         }
//     } catch (error) {
//         console.error('Error fetching orders:', error);
//         alert('Error fetching orders');
//     }
// }

// function populateOrdersTable(orders) {
//     const tbody = document.getElementById('orders-table-body');
//     tbody.innerHTML = ''; // Clear existing rows

//     orders.forEach(order => {
//         const status = order.created_at === '0000-00-00 00:00:00' ? 'Pending' : 'Shipped'; // Example logic for status
//         const statusClass = status === 'Pending' ? 'bg-warning' : 'bg-success';

//         const row = document.createElement('tr');
//         row.innerHTML = `
//             <td class="px-6 py-4 whitespace-nowrap text-sm">${order.id}</td>
//             <td class="px-6 py-4 whitespace-nowrap text-sm">${order.customer_name}</td>
//             <td class="px-6 py-4 whitespace-nowrap text-sm">${order.phone_number}</td>
//             <td class="px-6 py-4 text-sm">${order.address}, ${order.barangay}, ${order.city}, ${order.province}</td>
//             <td class="px-6 py-4 whitespace-nowrap text-sm">${order.men_set}</td>
//             <td class="px-6 py-4 whitespace-nowrap text-sm">${order.women_set}</td>
//             <td class="px-6 py-4 whitespace-nowrap text-sm">
//                 <span class="px-2 py-1 text-xs font-semibold rounded-full ${statusClass} text-white">${status}</span>
//             </td>
//             <td class="px-6 py-4 whitespace-nowrap text-sm">
//                 <div class="relative inline-block">
//                     <button class="action-btn px-3 py-1 bg-primary text-white rounded-md hover:bg-primary/80 transition view-order" data-order-id="${order.id}" aria-haspopup="true">Actions</button>
//                     <div class="action-menu hidden absolute z-10 mt-1 w-32 bg-white dark:bg-gray-800 rounded-md shadow-lg">
//                         <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 view-order" data-order-id="${order.id}">View</a>
//                         <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">Edit</a>
//                         <a href="#" class="block px-4 py-2 text-sm text-danger hover:bg-gray-100 dark:hover:bg-gray-700">Delete</a>
//                     </div>
//                 </div>
//             </td>
//         `;
//         tbody.appendChild(row);
//     });

//     // Re-attach event listeners for action buttons
//     attachActionButtonListeners();

//     // Re-attach event listeners for view order buttons
//     document.querySelectorAll('.view-order').forEach(btn => {
//         btn.addEventListener('click', (e) => {
//             e.preventDefault();
//             const orderId = btn.getAttribute('data-order-id');
//             const order = orders.find(o => o.id == orderId);
//             showOrderDetails(order);
//         });
//     });
// }

// // Show order details in modal
// function showOrderDetails(order) {
//     const orderDetails = document.getElementById('order-details');
//     const status = order.created_at === '0000-00-00 00:00:00' ? 'Pending' : 'Shipped';
//     orderDetails.innerHTML = `
//         <div class="space-y-2">
//             <p><strong>Order ID:</strong> ${order.id}</p>
//             <p><strong>Customer Name:</strong> ${order.customer_name}</p>
//             <p><strong>Phone:</strong> ${order.phone_number}</p>
//             <p><strong>Address:</strong> ${order.address}, ${order.barangay}, ${order.city}, ${order.province}</p>
//             <p><strong>Men's Set:</strong> ${order.men_set}</p>
//             <p><strong>Women's Set:</strong> ${order.women_set}</p>
//             <p><strong>IP Address:</strong> ${order.ip_address}</p>
//             <p><strong>Status:</strong> ${status}</p>
//             <div class="mt-4">
//                 <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Order Timeline</h3>
//                 <div class="space-y-4 mt-2">
//                     <div class="flex items-center gap-3">
//                         <i data-lucide="check-circle" class="w-5 h-5 text-success"></i>
//                         <span>Order placed - ${order.created_at}</span>
//                     </div>
//                     ${status === 'Shipped' ? `
//                         <div class="flex items-center gap-3">
//                             <i data-lucide="truck" class="w-5 h-5 text-warning"></i>
//                             <span>Order shipped - ${order.updated_at}</span>
//                         </div>
//                     ` : ''}
//                 </div>
//             </div>
//         </div>
//     `;
//     lucide.createIcons(); // Re-render icons in modal
//     document.getElementById('order-modal').classList.remove('hidden');
// }

// // Static search for orders
// const orderSearch = document.getElementById('order-search');
// if (orderSearch) {
//     orderSearch.addEventListener('input', function () {
//         const search = this.value.toLowerCase();
//         const rows = document.querySelectorAll('#orders-table-body tr');
//         rows.forEach(row => {
//             const customerName = row.cells[1].textContent.toLowerCase();
//             const phone = row.cells[2].textContent.toLowerCase();
//             row.style.display = (customerName.includes(search) || phone.includes(search)) ? '' : 'none';
//         });
//     });
// }

// // Export buttons (static alerts for demo)
// document.getElementById('export-csv').addEventListener('click', () => {
//     alert('CSV export functionality available in dynamic version.');
// });
// document.getElementById('export-pdf').addEventListener('click', () => {
//     alert('PDF export functionality available in dynamic version.');
// });

// // Modal close
// document.getElementById('close-modal').addEventListener('click', () => {
//     document.getElementById('order-modal').classList.add('hidden');
// });
// document.getElementById('order-modal').addEventListener('click', (e) => {
//     if (e.target === document.getElementById('order-modal')) {
//         document.getElementById('order-modal').classList.add('hidden');
//     }
// });

// // Fetch orders on page load
// document.addEventListener('DOMContentLoaded', () => {
//     if (document.getElementById('orders').classList.contains('hidden') === false) {
//         fetchOrders();
//     }
//     // Fetch orders when Orders page is navigated to
//     document.querySelectorAll('.sidebar-link').forEach(link => {
//         link.addEventListener('click', (e) => {
//             if (link.getAttribute('data-page') === 'orders') {
//                 fetchOrders();
//             }
//         });
//     });
// });