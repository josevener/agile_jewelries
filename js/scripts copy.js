// $(document).ready(function () {
//     // Smooth scroll to order form when clicking "Buy Now"
//     $('.buy-btn').on('click', function (e) {
//         e.preventDefault(); // Prevent default button behavior
//         $('html, body').animate(
//             {
//                 scrollTop: $('#order-form').offset().top - 20, // Add offset for better visibility
//             },
//             800
//         );
//     });

//     // Form validation and submission
//     $('#checkoutForm').on('submit', function (e) {
//         e.preventDefault();

//         // Get form values
//         const name = $("input[name='name']").val().trim();
//         const phone = $("input[name='phone']").val().trim();
//         const address = $("input[name='address']").val().trim();
//         const province = $("input[name='province']").val().trim();
//         const city = $("input[name='city']").val().trim();
//         const barangay = $("input[name='barangay']").val().trim();
//         const menSet = $("input[name='men']").is(':checked');
//         const womenSet = $("input[name='women']").is(':checked');

//         // Validate form and collect errors
//         const errors = [];
//         if (!name) errors.push("Full Name is required.");
//         if (!phone) errors.push("Phone Number is required.");
//         else if (!/^[0-9]{10,11}$/.test(phone)) errors.push("Phone Number must be 10-11 digits.");
//         if (!address) errors.push("Address is required.");
//         if (!province) errors.push("Province is required.");
//         if (!city) errors.push("City is required.");
//         if (!barangay) errors.push("Barangay is required.");
//         if (!menSet && !womenSet) errors.push("Please select at least one product (Men’s or Women’s Set).");

//         // Show error modal if there are errors
//         if (errors.length > 0) {
//             const errorMessage = `<ul class="list-disc pl-5">${errors.map(error => `<li>${error}</li>`).join('')}</ul>`;
//             showModal('Form Error', 'Please correct the following errors:', errorMessage);
//             return;
//         }

//         // Prepare order details
//         const orderDetails = `
//             <p><strong>Name:</strong> ${name}</p>
//             <p><strong>Phone:</strong> ${phone}</p>
//             <p><strong>Address:</strong> ${address}, ${barangay}, ${city}, ${province}</p>
//             <p><strong>Order:</strong> ${menSet ? "Men's Set" : ''}${menSet && womenSet ? ', ' : ''}${womenSet ? "Women's Set" : ''
//             }</p>
//         `;

//         // Submit to PHP backend
//         $.ajax({
//             url: 'submit_order.php',
//             method: 'POST',
//             data: { name, phone, address, province, city, barangay, menSet, womenSet },
//             dataType: 'json',
//             success: function (response) {
//                 if (response.success) {
//                     showModal('Order Submitted', 'Your order has been successfully submitted!', orderDetails);
//                     $('#checkoutForm')[0].reset();
//                 } else {
//                     showModal('Submission Error', response.message || 'Failed to submit order. Please try again.');
//                 }
//             },
//             error: function () {
//                 showModal('Submission Error', 'Failed to connect to the server. Please try again.');
//             }
//         });
//     });

//     // Modal handling
//     function showModal(title, message, details = '') {
//         $('#modal-title').text(title);
//         $('#modal-message').text(message);
//         $('#modal-order-details').html(details).removeClass('hidden');
//         $('#modal').removeClass('hidden');
//     }

//     // Close modal
//     $('#modal-close').on('click', function () {
//         $('#modal').addClass('hidden');
//     });

//     // Close modal when clicking outside
//     $('#modal').on('click', function (e) {
//         if (e.target === this) {
//             $(this).addClass('hidden');
//         }
//     });

//     // Carousel navigation
//     $('.carousel').each(function (index) {
//         const $carousel = $(this);
//         const $items = $carousel.children('img');
//         const total = $items.length;
//         let currentIndex = 0;
//         const itemWidth = $items.first().outerWidth(true);
//         const displaySelector = $carousel.data('display');
//         const $displayImg = $(displaySelector);

//         // Ensure carousel has items and display image exists
//         if (total === 0 || !$displayImg.length) {
//             console.warn('Carousel or display image not found:', displaySelector);
//             return;
//         }

//         // Function to update carousel and display image
//         function updateCarousel() {
//             // Crossfade main display image
//             const nextSrc = $items.eq(currentIndex).attr('src');
//             if ($displayImg.attr('src') !== nextSrc) {
//                 const $newImg = $('<img>', {
//                     src: nextSrc,
//                     alt: $displayImg.attr('alt'),
//                     class: $displayImg.attr('class'),
//                     css: { opacity: 0, position: 'absolute', top: 0, left: 0 }
//                 });
//                 $displayImg.after($newImg);
//                 $newImg.animate({ opacity: 1 }, 300, function () {
//                     $displayImg.remove();
//                     $newImg.css({ position: '', opacity: '' });
//                     $carousel.closest('.carousel-container').prev('.relative').find('img').first().attr('id', displaySelector.replace('#', ''));
//                 });
//                 $displayImg.animate({ opacity: 0 }, 300);
//             }

//             // Update carousel position
//             const offset = -currentIndex * itemWidth;
//             $carousel.css('transform', `translateX(${offset}px)`);
//         }

//         // Function to reset auto-slide
//         let autoSlide;
//         function startAutoSlide() {
//             clearInterval(autoSlide);
//             autoSlide = setInterval(function () {
//                 currentIndex = (currentIndex + 1) % total;
//                 updateCarousel();
//             }, 5000);
//         }

//         // Initial delay to stagger carousels
//         setTimeout(startAutoSlide, index * 1000);

//         // Next button
//         $carousel
//             .parent() // div.carousel-container
//             .find('.next[data-carousel="#' + $carousel.attr('id') + '"]')
//             .on('click', function () {
//                 currentIndex = (currentIndex + 1) % total;
//                 updateCarousel();
//                 startAutoSlide();
//             });

//         // Previous button
//         $carousel
//             .parent() // div.carousel-container
//             .find('.prev[data-carousel="#' + $carousel.attr('id') + '"]')
//             .on('click', function () {
//                 currentIndex = (currentIndex - 1 + total) % total;
//                 updateCarousel();
//                 startAutoSlide();
//             });

//         // Clickable carousel images
//         $items.on('click', function () {
//             currentIndex = $(this).index();
//             updateCarousel();
//             startAutoSlide();
//         });

//         // Stop auto-slide on hover
//         $carousel.parent().on('mouseenter', function () {
//             clearInterval(autoSlide);
//         });

//         // Resume auto-slide when not hovering
//         $carousel.parent().on('mouseleave', startAutoSlide);

//         // Initialize display image with first carousel item
//         $displayImg.attr('src', $items.eq(0).attr('src'));
//     });
// });