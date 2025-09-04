<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agile Jewelries</title>
    <!-- TailwindCSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/styles.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body class="bg-white text-gray-900">
    <!-- Header -->
    <header class="max-w-lg mx-auto text-center bg-yellow-400 py-2 text-sm font-bold">
        FREE SHIPPING NATIONWIDE
    </header>

    <main class="max-w-lg mx-auto p-4">
        <!-- Hero Section -->
        <section class="text-center">
            <hr class="border-t-4 border-gray-800 my-2">
            <h1 class="text-lg font-bold">Agile Jewelries</h1>
            <hr class="border-t-4 border-gray-800 my-4">
            <video class="w-full rounded object-cover" autoplay muted loop controls loading="lazy">
                <source src="assets/video/vid1.mp4" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </section>

        <hr class="border-t-4 border-gray-800 my-4">

        <!-- Men Section -->
        <section class="mt-8 text-center">
            <h2 class="font-bold uppercase">For Men</h2>
            <p class="text-sm">Japanese Cut Necklace & Bracelet Set (ALL IN ONE SET)</p>

            <div class="mt-4 space-y-4">
                <img src="assets/img/men/men1.png" alt="Men Necklace" class="mx-auto rounded">
                <!-- Main dynamic image -->
                <div class="relative inline-block w-full">
                    <img id="menBraceletDisplay" src="assets/img/men/men2.png" alt="Men Bracelet" class="mx-auto rounded">
                </div>
                <!-- Carousel with buttons -->
                <div class="carousel-container">
                    <button class="carousel-btn prev" data-carousel="#menBraceletCarousel" aria-label="Previous Men's Bracelet Image">&#9664;</button>
                    <div id="menBraceletCarousel" class="carousel flex justify-center items-center gap-2" data-display="#menBraceletDisplay">
                        <img src="assets/img/men/bracelet_men.png" class="h-28 rounded flex-shrink-0" alt="Men's Bracelet Option 1" loading="lazy">
                        <img src="assets/img/men/hand_bracelet_men.png" class="h-28 rounded flex-shrink-0" alt="Men's Bracelet Option 2" loading="lazy">
                        <img src="assets/img/men/hand_necklace_women.png" class="h-28 rounded flex-shrink-0" alt="Men's Bracelet Option 3" loading="lazy">
                    </div>
                    <button class="carousel-btn next" data-carousel="#menBraceletCarousel" aria-label="Next Men's Bracelet Image">&#9654;</button>
                </div>
                <div class="text-center font-bold">
                    <h2>Bracelet</h2>
                </div>
            </div>

            <div class="mt-4 space-y-4">
                <!-- Main dynamic image -->
                <div class="relative inline-block w-full">
                    <img id="menNecklaceDisplay" src="assets/img/men/necklace_men.png" alt="Men Necklace" class="mx-auto rounded">
                </div>
                <!-- Carousel with buttons -->
                <div class="carousel-container">
                    <button class="carousel-btn prev" data-carousel="#menNecklaceCarousel" aria-label="Previous Men's Necklace Image">&#9664;</button>
                    <div id="menNecklaceCarousel" class="carousel flex justify-center items-center gap-2 overflow-hidden" data-display="#menNecklaceDisplay">
                        <img src="assets/img/men/necklace_men.png" class="h-28 rounded flex-shrink-0" alt="Men's Necklace Option 1" loading="lazy">
                        <img src="assets/img/men/necklace_men2.png" class="h-28 rounded flex-shrink-0" alt="Men's Necklace Option 2" loading="lazy">
                        <img src="assets/img/men/necklace_men3.png" class="h-28 rounded flex-shrink-0" alt="Men's Necklace Option 3" loading="lazy">
                        <img src="assets/img/men/hand_necklace_women.png" class="h-28 rounded flex-shrink-0" alt="Men's Necklace Option 4" loading="lazy">
                        <img src="assets/img/men/necklace_men2.png" class="h-28 rounded flex-shrink-0" alt="Men's Necklace Option 5" loading="lazy">
                    </div>
                    <button class="carousel-btn next" data-carousel="#menNecklaceCarousel" aria-label="Next Men's Necklace Image">&#9654;</button>
                </div>
                <div class="text-center font-bold">
                    <h2>Necklace</h2>
                </div>
            </div>

            <!-- Pricing -->
            <div class="mt-4">
                <p class="line-through text-red-500">₱1,699.00</p>
                <p class="text-2xl font-bold text-green-600">₱898.00</p>
                <p class="text-sm text-gray-600">FREE SHIPPING AND COD NATIONWIDE</p>
                <button class="mt-2 bg-black text-white px-6 py-2 rounded buy-btn">Buy Now</button>
            </div>
        </section>

        <!-- Women Section -->
        <section class="mt-12 text-center">
            <h2 class="font-bold uppercase">For Women</h2>
            <p class="text-sm">Japanese Cut Necklace & Bracelet Set (ALL IN ONE SET)</p>

            <div class="mt-4 space-y-4">
                <img src="assets/img/women/women.png" alt="Women Necklace" class="mx-auto rounded">
                <!-- Main dynamic image -->
                <div class="relative inline-block w-full">
                    <img id="womenBraceletDisplay" src="assets/img/women/watch.png" alt="Women Bracelet" class="mx-auto rounded">
                </div>
                <!-- Carousel with buttons -->
                <div class="carousel-container">
                    <button class="carousel-btn prev" data-carousel="#womenBraceletCarousel" aria-label="Previous Women's Bracelet Image">&#9664;</button>
                    <div id="womenBraceletCarousel" class="carousel flex justify-center items-center gap-2 overflow-hidden" data-display="#womenBraceletDisplay">
                        <img src="assets/img/women/bracelet1.png" class="h-28 rounded flex-shrink-0" alt="Women's Bracelet Option 1" loading="lazy">
                        <img src="assets/img/women/bracelet2.png" class="h-28 rounded flex-shrink-0" alt="Women's Bracelet Option 2" loading="lazy">
                        <img src="assets/img/women/bracelet3.png" class="h-28 rounded flex-shrink-0" alt="Women's Bracelet Option 3" loading="lazy">
                    </div>
                    <button class="carousel-btn next" data-carousel="#womenBraceletCarousel" aria-label="Next Women's Bracelet Image">&#9654;</button>
                </div>
                <div class="text-center font-bold">
                    <h2>Bracelet</h2>
                </div>
            </div>

            <div class="mt-4 space-y-4">
                <!-- Main dynamic image -->
                <div class="relative inline-block w-full">
                    <img id="womenNecklaceDisplay" src="assets/img/women/necklace1.png" alt="Women Necklace" class="mx-auto rounded">
                </div>
                <!-- Carousel with buttons -->
                <div class="carousel-container">
                    <button class="carousel-btn prev" data-carousel="#womenNecklaceCarousel" aria-label="Previous Women's Necklace Image">&#9664;</button>
                    <div id="womenNecklaceCarousel" class="carousel flex justify-center items-center gap-2 overflow-hidden" data-display="#womenNecklaceDisplay">
                        <img src="assets/img/women/women.png" class="h-28 rounded flex-shrink-0" alt="Women's Necklace Option 1" loading="lazy">
                        <img src="assets/img/women/necklace1.png" class="h-28 rounded flex-shrink-0" alt="Women's Necklace Option 2" loading="lazy">
                        <img src="assets/img/women/necklace2.png" class="h-28 rounded flex-shrink-0" alt="Women's Necklace Option 3" loading="lazy">
                        <img src="assets/img/women/necklace3.png" class="h-28 rounded flex-shrink-0" alt="Women's Necklace Option 4" loading="lazy">
                    </div>
                    <button class="carousel-btn next" data-carousel="#womenNecklaceCarousel" aria-label="Next Women's Necklace Image">&#9654;</button>
                </div>
                <div class="text-center font-bold">
                    <h2>Necklace</h2>
                </div>
            </div>

            <!-- Pricing -->
            <div class="mt-4">
                <p class="line-through text-red-500">₱1,699.00</p>
                <p class="text-2xl font-bold text-green-600">₱898.00</p>
                <p class="text-sm text-gray-600">FREE SHIPPING AND COD NATIONWIDE</p>
                <button class="mt-2 bg-black text-white px-6 py-2 rounded buy-btn">Buy Now</button>
            </div>
        </section>

        <!-- Order Form -->
        <section id="order-form" class="mt-12">
            <h2 class="text-center font-bold text-xl mb-4">Order Form</h2>
            <form id="checkoutForm" class="space-y-3">
                <input type="text" name="customer_name" placeholder="Full Name" class="w-full border px-3 py-2 rounded border border-gray-800 border-2" required>
                <input type="text" name="phone_number" placeholder="Phone Number" class="w-full border px-3 py-2 rounded border border-gray-800 border-2" required>
                <input type="text" name="address" placeholder="Address" class="w-full border px-3 py-2 rounded border border-gray-800 border-2" required>
                <div class="flex space-x-2">
                    <select name="province" id="province-select" class="w-1/3 border text-xs sm:text-sm px-3 py-2 rounded border-gray-800 border-2 cursor-pointer" required>
                        <option value="" disabled selected>Select Province</option>
                    </select>
                    <select name="city" id="city-select" class="w-1/3 border text-xs sm:text-sm px-3 py-2 rounded border-gray-800 border-2 cursor-pointer" required disabled>
                        <option value="" disabled selected>Select City/Municipality</option>
                    </select>
                    <select name="barangay" id="barangay-select" class="w-1/3 border text-xs sm:text-sm px-3 py-2 rounded border-gray-800 border-2 cursor-pointer" required disabled>
                        <option value="" disabled selected>Select Barangay</option>
                    </select>
                </div>
                <div class="border border-gray-800 border-2 p-2 rounded space-y-2">
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="men_set" value="1">
                        <span class="text-sm">
                            Japan Cut 25 Inches Necklace & 8 inches Bracelet Set with Clip Lock for MAN ₱898.00
                        </span>
                    </label>
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="women_set" value="1">
                        <span class="text-sm">
                            Japan Cut 25 Inches Necklace & 8 inches Bracelet Set with Clip Lock for WOMAN ₱898.00
                        </span>
                    </label>
                </div>
                <button type="submit" class="w-full bg-black text-white py-2 rounded" id="submit-btn">Buy Now</button>
            </form>
        </section>

        <div class="flex items-center justify-center p-4 text-center font-semibold text-sm sm:text-base md:text-lg lg:text-xl">
            🚚 CASH ON DELIVERY NATIONWIDE 📦
        </div>

        <!-- Reviews -->
        <section class="mt-12">
            <h2 class="font-bold text-xl text-center mb-4">Customer Reviews</h2>
            <div class="text-start">
                <p class="text-2xl font-bold px-6">5.0 ⭐⭐⭐⭐⭐ <span class="text-sm justify-center">87 Reviews</span></p>
                <div class="mt-2 text-lg text-gray-600 space-y-2 mt-4">
                    <div class="flex items-center justify-center space-x-2">
                        <span class="w-12 text-left">5 ⭐</span>
                        <div class="flex-1 h-3 bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-full bg-yellow-500" style="width: (80/87)*100%"></div>
                        </div>
                        <span class="w-12 text-yellow-800">80</span>
                    </div>
                    <div class="flex items-center justify-center space-x-2">
                        <span class="w-12 text-left">4 ⭐</span>
                        <div class="flex-1 h-3 bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-full bg-yellow-500" style="width: 0%"></div>
                        </div>
                        <span class="w-12 text-gray-500">0</span>
                    </div>
                    <div class="flex items-center justify-center space-x-2">
                        <span class="w-12 text-left">3 ⭐</span>
                        <div class="flex-1 h-3 bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-full bg-yellow-500" style="width: 0%"></div>
                        </div>
                        <span class="w-12 text-gray-500">0</span>
                    </div>
                    <div class="flex items-center justify-center space-x-2">
                        <span class="w-12 text-left">2 ⭐</span>
                        <div class="flex-1 h-3 bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-full bg-yellow-500" style="width: 0%"></div>
                        </div>
                        <span class="w-12 text-gray-500">0</span>
                    </div>
                    <div class="flex items-center justify-center space-x-2">
                        <span class="w-12 text-left">1 ⭐</span>
                        <div class="flex-1 h-3 bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-full bg-yellow-500" style="width: 0%"></div>
                        </div>
                        <span class="w-12 text-gray-500">0</span>
                    </div>
                </div>
            </div>
            <div class="mt-6 space-y-6">
                <div class="flex items-start space-x-4 p-4 border border-gray-200 rounded-lg">
                    <img src="assets/img/reviews/review1.png" class="w-40 h-40 rounded-lg object-cover hover:scale-105 transition-transform duration-200 cursor-pointer review-image" alt="Customer Review 1" data-large-src="assets/img/reviews/review1.png">
                    <div class="flex-1">
                        <p class="text-base italic">“I received it already and it is beautiful ❤️ Thank you!”</p>
                        <p class="text-lg text-yellow-500 mt-1">★★★★★</p>
                        <p class="text-xs text-gray-600 mt-1">— Edwin C.</p>
                    </div>
                </div>
                <div class="flex items-start space-x-4 p-4 border border-gray-200 rounded-lg">
                    <div class="flex-1 text-right">
                        <p class="text-base italic">“Thanks much, I already ordered twice… great quality”</p>
                        <p class="text-lg text-yellow-500 mt-1">★★★★★</p>
                        <p class="text-xs text-gray-600 mt-1">— Mark S.</p>
                    </div>
                    <img src="assets/img/reviews/review2.png" class="w-40 h-40 rounded-lg object-cover hover:scale-105 transition-transform duration-200 cursor-pointer review-image" alt="Customer Review 2" data-large-src="assets/img/reviews/review2.png">
                </div>
                <div class="flex items-start space-x-4 p-4 border border-gray-200 rounded-lg">
                    <img src="assets/img/reviews/review3.png" class="w-40 h-40 rounded-lg object-cover hover:scale-105 transition-transform duration-200 cursor-pointer review-image" alt="Customer Review 3" data-large-src="assets/img/reviews/review3.png">
                    <div class="flex-1">
                        <p class="text-base italic">“Wowww this is my first time to this store and d ako nabigo sa expectation ko, so uulit at uulit ako hehehe 99% yan❤️”</p>
                        <p class="text-lg text-yellow-500 mt-1">★★★★★</p>
                        <p class="text-xs text-gray-600 mt-1">— Emily R.</p>
                    </div>
                </div>
                <div class="flex items-start space-x-4 p-4 border border-gray-200 rounded-lg">
                    <div class="flex-1 text-right">
                        <p class="text-base italic">“Maraming salamat po natanggap ko na yung bracelet napakaganda po. oorder ako ulit with necklace na po”</p>
                        <p class="text-lg text-yellow-500 mt-1">★★★★★</p>
                        <p class="text-xs text-gray-600 mt-1">— Joshua P.</p>
                    </div>
                    <img src="assets/img/reviews/review4.png" class="w-40 h-40 rounded-lg object-cover hover:scale-105 transition-transform duration-200 cursor-pointer review-image" alt="Customer Review 4" data-large-src="assets/img/reviews/review4.png">
                </div>
            </div>
        </section>

        <!-- Modal for Validation and Order Confirmation -->
        <div id="modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
            <div class="bg-white p-6 rounded-lg max-w-md w-full">
                <h2 id="modal-title" class="text-xl font-bold mb-4"></h2>
                <div id="modal-message" class="mb-4"></div>
                <div id="modal-order-details" class="mb-4"></div>
                <button id="modal-close" class="bg-black text-white px-4 py-2 rounded">Close</button>
            </div>
        </div>

        <!-- Fixed Footer (Desktop Only) -->
        <div class="fixed bottom-2 left-2 hidden sm:block">
            <p class="text-xs text-gray-600">
                <em>
                    <a href="https://zentrix-solutions.vercel.app" target="_blank" class="hover:underline">
                        Powered by: Zentrix Solutions
                    </a>
                </em>
            </p>
        </div>
    </main>

    <!-- Custom Script -->
    <script src="js/scripts.js"></script>
</body>

</html>