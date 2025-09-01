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
    const modalTitle = document.getElementById("modalTitle");
    const modalMessage = document.getElementById("modalMessage");
    const modalClose = document.getElementById("modalClose");

    window.showModal = function (title, message) {
        if (modal && modalTitle && modalMessage) {
            modalTitle.textContent = title;
            modalMessage.textContent = message;
            modal.classList.add("open"); // Add your CSS for .open { display:block; }
        }
    };

    function closeModal() {
        if (modal) {
            modal.classList.remove("open");
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
    // Carousel (if you have one)
    // ===============================
    const carousels = document.querySelectorAll(".carousel");

    carousels.forEach((carousel) => {
        let index = 0;
        const slides = carousel.querySelectorAll(".carousel-item");

        function showSlide(i) {
            slides.forEach((slide, idx) => {
                slide.style.display = idx === i ? "block" : "none";
            });
        }

        if (slides.length > 0) {
            showSlide(index);

            setInterval(() => {
                index = (index + 1) % slides.length;
                showSlide(index);
            }, 3000); // change every 3s
        }
    });
});
