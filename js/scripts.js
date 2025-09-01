// Wait until DOM is loaded
document.addEventListener("DOMContentLoaded", () => {
    // ===============================
    // Checkout Form Submit
    // ===============================
    const checkoutForm = document.getElementById("checkoutForm");

    if (checkoutForm) {
        checkoutForm.addEventListener("submit", async function (e) {
            e.preventDefault();

            const formData = new FormData(this);

            try {
                const response = await fetch("submit_order.php", {
                    method: "POST",
                    body: formData,
                });

                const data = await response.json();

                if (data.success) {
                    showModal("Order Submitted", data.message);
                    this.reset();
                } else {
                    showModal(
                        "Submission Error",
                        data.message || "Something went wrong."
                    );
                }
            } catch (err) {
                console.error("Form submission failed:", err);
                showModal("Network Error", "Could not connect to server.");
            }
        });
    }

    // ===============================
    // Modal Logic
    // ===============================
    const modal = document.getElementById("modal");
    const modalTitle = document.getElementById("modal-title");
    const modalMessage = document.getElementById("modal-message");
    const modalClose = document.getElementById("modal-close");

    window.showModal = function (title, message) {
        if (modal && modalTitle && modalMessage) {
            modalTitle.textContent = title;
            modalMessage.textContent = message;
            modal.classList.remove("hidden");
        }
    };

    function closeModal() {
        if (modal) {
            modal.classList.add("hidden");
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
