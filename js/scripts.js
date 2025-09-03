// Wait until DOM is loaded
document.addEventListener("DOMContentLoaded", () => {
    // ===============================
    // Checkout Form Submit
    // ===============================
    const checkoutForm = document.getElementById("checkoutForm");
    const submitBtn = document.getElementById("submit-btn");

    if (checkoutForm) {
        checkoutForm.addEventListener("submit", async function (e) {
            e.preventDefault();

            // Client-side validation
            const customerName = this.querySelector("[name='customer_name']").value.trim();
            const phoneNumber = this.querySelector("[name='phone_number']").value.trim();
            const address = this.querySelector("[name='address']").value.trim();
            const province = this.querySelector("[name='province']").value.trim();
            const city = this.querySelector("[name='city']").value.trim();
            const barangay = this.querySelector("[name='barangay']").value.trim();
            const menSet = this.querySelector("[name='men_set']").checked;
            const womenSet = this.querySelector("[name='women_set']").checked;

            const errors = [];
            if (!customerName) errors.push("Please enter your full name.");
            if (!phoneNumber || !/^\+?[0-9]{10,11}$/.test(phoneNumber)) {
                errors.push("Please enter a valid phone number.");
            }
            if (!address) errors.push("Please enter your address.");
            if (!province) errors.push("Please enter your province.");
            if (!city) errors.push("Please enter your city.");
            if (!barangay) errors.push("Please enter your barangay.");
            if (!menSet && !womenSet) errors.push("Please select at least one product (Men's or Women's Set).");

            if (errors.length > 0) {
                showModal("Submission Error", "<ul class='list-disc pl-5'>" + errors.map(err => `<li>${err}</li>`).join("") + "</ul>");
                return;
            }

            // Show loading state
            const originalBtnText = submitBtn.textContent;
            submitBtn.textContent = "Loading...";
            submitBtn.disabled = true;
            submitBtn.classList.add("opacity-50", "cursor-not-allowed");

            try {
                // Fetch IP address from GeoJS API
                let ipAddress = '::1';
                try {
                    const ipResponse = await fetch('https://get.geojs.io/v1/ip.json');
                    const ipData = await ipResponse.json();
                    if (ipData.ip) {
                        ipAddress = ipData.ip;
                    } 
                    else {
                        console.warn("GeoJS API did not return an IP address. Using default value.");
                    }
                } catch (ipError) {
                    console.error("GeoJS API fetch failed:", ipError);
                }

                const formData = new FormData(this);
                formData.append('ip_address', ipAddress);

                const response = await fetch("submit_order.php", {
                    method: "POST",
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    showModal("Order Submitted", data.message, `
                        <p><strong>Name:</strong> ${data.order.customer_name}</p>
                        <p><strong>Phone:</strong> ${data.order.phone_number}</p>
                        <p><strong>Address:</strong> ${data.order.address}, ${data.order.barangay}, ${data.order.city}, ${data.order.province}</p>
                        <p><strong>Products:</strong> ${data.order.men_set ? "Men's Set" : ""} ${data.order.women_set ? "Women's Set" : ""}</p>
                    `);
                    this.reset();
                } else {
                    showModal("Submission Error", "<ul class='list-disc pl-5'>" + data.errors.map(err => `<li>${err}</li>`).join("") + "</ul>");
                }
            } catch (err) {
                console.error("Form submission failed:", err);
                showModal("Network Error", "Could not connect to the server. Please try again later.");
            } finally {
                // Reset button state
                submitBtn.textContent = originalBtnText;
                submitBtn.disabled = false;
                submitBtn.classList.remove("opacity-50", "cursor-not-allowed");
            }
        });
    }

    // ===============================
    // Modal Logic
    // ===============================
    const modal = document.getElementById("modal");
    const modalTitle = document.getElementById("modal-title");
    const modalMessage = document.getElementById("modal-message");
    const modalOrderDetails = document.getElementById("modal-order-details");
    const modalClose = document.getElementById("modal-close");

    window.showModal = function (title, message, orderDetails = "") {
        if (modal && modalTitle && modalMessage) {
            modalTitle.textContent = title;
            modalMessage.innerHTML = message;
            modalOrderDetails.innerHTML = orderDetails;
            modal.classList.remove("hidden");
        }
    };

    function closeModal() {
        if (modal) {
            modal.classList.add("hidden");
            modalMessage.innerHTML = "";
            modalOrderDetails.innerHTML = "";
        }
    }

    if (modalClose) {
        modalClose.addEventListener("click", closeModal);
    }

    window.addEventListener("click", (e) => {
        if (modal && e.target === modal) {
            closeModal();
        }
    });

    // ===============================
    // Carousel Logic
    // ===============================
    function setupCarousel(carousel) {
        const slides = carousel.querySelectorAll("img");
        const displaySelector = carousel.getAttribute("data-display");
        const display = document.querySelector(displaySelector);

        let index = 0;
        let interval;

        function showSlide(i) {
            index = (i + slides.length) % slides.length; // wrap around
            if (display) {
                display.src = slides[index].src;
                display.alt = slides[index].alt;
            }
        }

        function startAutoPlay() {
            stopAutoPlay();
            interval = setInterval(() => {
                showSlide(index + 1);
            }, 5000); // 5 seconds
        }

        function stopAutoPlay() {
            if (interval) clearInterval(interval);
        }

        // Initialize
        showSlide(index);
        startAutoPlay();

        // Button controls
        const prevBtn = carousel.parentElement.querySelector(".carousel-btn.prev");
        const nextBtn = carousel.parentElement.querySelector(".carousel-btn.next");

        if (prevBtn) {
            prevBtn.addEventListener("click", () => {
                showSlide(index - 1);
                startAutoPlay();
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener("click", () => {
                showSlide(index + 1);
                startAutoPlay();
            });
        }

        // Thumbnail click
        slides.forEach((slide, i) => {
            slide.addEventListener("click", () => {
                showSlide(i);
                startAutoPlay();
            });
        });
    }

    // Initialize all carousels
    document.querySelectorAll(".carousel").forEach(setupCarousel);
});