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
            <video class="w-full rounded object-cover" autoplay muted loop controls>
                <source src="assets/video/vid1.mp4" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </section>

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
                    <div id="menBraceletCarousel" class="carousel" data-display="#menBraceletDisplay">
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
                    <div id="menNecklaceCarousel" class="carousel" data-display="#menNecklaceDisplay">
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
                <p class="text-2xl font-bold text-green-600">₱999.00</p>
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
                    <div id="womenBraceletCarousel" class="carousel" data-display="#womenBraceletDisplay">
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
                    <div id="womenNecklaceCarousel" class="carousel" data-display="#womenNecklaceDisplay">
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
                <p class="text-2xl font-bold text-green-600">₱999.00</p>
                <p class="text-sm text-gray-600">FREE SHIPPING AND COD NATIONWIDE</p>
                <button class="mt-2 bg-black text-white px-6 py-2 rounded buy-btn">Buy Now</button>
            </div>
        </section>

        <!-- Order Form -->
        <section id="order-form" class="mt-12">
            <h2 class="text-center font-bold text-xl mb-4">Order Form</h2>
            <form id="checkoutForm" class="space-y-3">
                <input type="text" name="name" placeholder="Full Name" class="w-full border px-3 py-2 rounded" required>
                <input type="text" name="phone" placeholder="Phone Number" class="w-full border px-3 py-2 rounded" required>
                <input type="text" name="address" placeholder="Address" class="w-full border px-3 py-2 rounded" required>
                <div class="flex space-x-2">
                    <input type="text" name="province" placeholder="Province" class="w-1/3 border px-3 py-2 rounded" required>
                    <input type="text" name="city" placeholder="City" class="w-1/3 border px-3 py-2 rounded" required>
                    <input type="text" name="barangay" placeholder="Barangay" class="w-1/3 border px-3 py-2 rounded" required>
                </div>
                <div>
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="men" value="Men Set"> <span>Men's Necklace & Bracelet Set</span>
                    </label>
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="women" value="Women Set"> <span>Women's Necklace & Bracelet Set</span>
                    </label>
                </div>
                <button type="submit" class="w-full bg-black text-white py-2 rounded">Buy Now</button>
            </form>
        </section>

        <!-- Reviews -->
        <section class="mt-12">
            <h2 class="font-bold text-xl text-center mb-4">Customer Reviews</h2>
            <div class="text-center">
                <p class="text-2xl font-bold">5.0 ⭐⭐⭐⭐⭐</p>
                <p class="text-sm text-gray-500">87 Reviews</p>
            </div>
            <div class="mt-6 space-y-6">
                <div class="flex items-center space-x-3">
                    <img src="assets/img/reviews/review1.png" class="w-16 h-16 rounded" alt="Customer Review 1">
                    <p class="text-sm">“I received it already and it is beautiful ❤️”</p>
                </div>
                <div class="flex items-center space-x-3">
                    <p class="text-sm">“Thanks much, I already ordered twice… great quality”</p>
                    <img src="assets/img/reviews/review2.png" class="w-16 h-16 rounded" alt="Customer Review 2">
                </div>
                <div class="flex items-center space-x-3">
                    <img src="assets/img/reviews/review3.png" class="w-16 h-16 rounded" alt="Customer Review 3">
                    <p class="text-sm">“I received it already and it is beautiful ❤️”</p>
                </div>
                <div class="flex items-center space-x-3">
                    <p class="text-sm">“Thanks much, I already ordered twice… great quality”</p>
                    <img src="assets/img/reviews/review4.png" class="w-16 h-16 rounded" alt="Customer Review 4">
                </div>
            </div>
        </section>

        <!-- Modal for Validation and Order Confirmation -->
        <div id="modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
            <div class="bg-white p-6 rounded-lg max-w-md w-full">
                <h2 id="modal-title" class="text-xl font-bold mb-4"></h2>
                <p id="modal-message" class="mb-4"></p>
                <div id="modal-order-details" class="mb-4"></div>
                <button id="modal-close" class="bg-black text-white px-4 py-2 rounded">Close</button>
            </div>
        </div>
    </main>

    <!-- Custom Script -->
    <script src="js/scripts.js"></script>
</body>

</html>