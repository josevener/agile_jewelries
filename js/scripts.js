document.addEventListener("DOMContentLoaded", () => {
  const provinceSelect = document.getElementById("province-select");
  const citySelect = document.getElementById("city-select");
  const barangaySelect = document.getElementById("barangay-select");

  async function fetchRegions() {
    try {
      const res = await fetch("https://psgc.cloud/api/regions");
      if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
      const regions = await res.json();
      console.log("Regions fetched:", regions);
      if (provinceSelect) {
        provinceSelect.innerHTML = '<option value="" disabled selected>Select Province</option>';
        regions.forEach(region => {
          const option = document.createElement("option");
          option.value = region.name;
          option.dataset.code = region.code;
          option.textContent = region.name;
          provinceSelect.appendChild(option);
        });
        provinceSelect.disabled = false;
      }
    } 
    catch (error) {
      console.error("Failed to fetch regions:", error);
      showModal("Error", "Failed to load provinces. Please try again later.");
      if (provinceSelect) provinceSelect.disabled = true;
    }
  }

  async function fetchCitiesMunicipalities(code) {
    try {
      citySelect.disabled = true;
      citySelect.innerHTML = '<option value="" disabled selected>Loading...</option>';
      const res = await fetch(`https://psgc.cloud/api/v2/regions/${code}/cities-municipalities`);
      if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
      const cities = await res.json();
      console.log("Cities fetched for region", code, ":", cities);
      citySelect.innerHTML = '<option value="" disabled selected>Select City/Municipality</option>';
      cities.data.forEach(city => {
        const option = document.createElement("option");
        option.value = city.name;
        option.dataset.code = city.code;
        option.textContent = city.name;
        citySelect.appendChild(option);
      });
      citySelect.disabled = false;
      barangaySelect.innerHTML = '<option value="" disabled selected>Select Barangay</option>';
      barangaySelect.disabled = true;
    } 
    catch (error) {
      console.error("Failed to fetch cities for region", code, ":", error);
      showModal("Error", "Failed to load cities. Please try again later.");
      citySelect.innerHTML = '<option value="" disabled selected>Select City/Municipality</option>';
      citySelect.disabled = true;
      barangaySelect.disabled = true;
    }
  }

  async function fetchBarangays(code) {
    try {
      barangaySelect.disabled = true;
      barangaySelect.innerHTML = '<option value="" disabled selected>Loading...</option>';
      const res = await fetch(`https://psgc.cloud/api/v2/cities-municipalities/${code}/barangays`);
      if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
      const barangays = await res.json();
      console.log("Barangays fetched for city", code, ":", barangays);
      barangaySelect.innerHTML = '<option value="" disabled selected>Select Barangay</option>';
      barangays.data.forEach(barangay => {
        const option = document.createElement("option");
        option.value = barangay.name;
        option.textContent = barangay.name;
        barangaySelect.appendChild(option);
      });
      barangaySelect.disabled = false;
    } 
    catch (error) {
      console.error("Failed to fetch barangays for city", code, ":", error);
      showModal("Error", "Failed to load barangays. Please try again later.");
      barangaySelect.innerHTML = '<option value="" disabled selected>Select Barangay</option>';
      barangaySelect.disabled = true;
    }
  }

  async function initializeForm() {
    await fetchRegions();
    if (provinceSelect) {
      provinceSelect.addEventListener("change", () => {
        const selectedOption = provinceSelect.selectedOptions[0];
        const code = selectedOption ? selectedOption.dataset.code : null;
        console.log("Province selected:", provinceSelect.value, "Code:", code);
        if (code) {
          fetchCitiesMunicipalities(code);
        } else {
          citySelect.innerHTML = '<option value="" disabled selected>Select City/Municipality</option>';
          citySelect.disabled = true;
          barangaySelect.innerHTML = '<option value="" disabled selected>Select Barangay</option>';
          barangaySelect.disabled = true;
        }
      });
    }
    if (citySelect) {
      citySelect.addEventListener("change", () => {
        const selectedOption = citySelect.selectedOptions[0];
        const code = selectedOption ? selectedOption.dataset.code : null;
        console.log("City selected:", citySelect.value, "Code:", code);
        if (code) {
          fetchBarangays(code);
        } else {
          barangaySelect.innerHTML = '<option value="" disabled selected>Select Barangay</option>';
          barangaySelect.disabled = true;
        }
      });
    }
  }

  initializeForm();

  // Smooth scroll to order form on Buy Now button click
  const buyButtons = document.querySelectorAll(".buy-btn");
  const orderForm = document.getElementById("order-form");
  const header = document.querySelector("header");

  buyButtons.forEach(button => {
    button.addEventListener("click", () => {
      console.log("Buy Now button clicked, scrolling to order form");
      if (orderForm) {
        const headerHeight = header ? header.offsetHeight : 0;
        const formPosition = orderForm.getBoundingClientRect().top + window.pageYOffset - headerHeight;
        window.scrollTo({
          top: formPosition,
          behavior: "smooth"
        });
        const firstInput = orderForm.querySelector("input[name='customer_name']");
        if (firstInput) {
          firstInput.focus();
        }
      } 
      else {
        console.error("Order form not found");
      }
    });
  });

  // Checkout Form Submit
  const checkoutForm = document.getElementById("checkoutForm");
  const submitBtn = document.getElementById("submit-btn");

  if (checkoutForm) {
    checkoutForm.addEventListener("submit", async function (e) {
      e.preventDefault();

      // Client-side validation
      const customerName = this.querySelector("[name='customer_name']").value.trim();
      const phoneNumber = this.querySelector("[name='phone_number']").value.trim();
      const address = this.querySelector("[name='address']").value.trim();
      const province = this.querySelector("[name='province']").value;
      const city = this.querySelector("[name='city']").value;
      const barangay = this.querySelector("[name='barangay']").value;
      const menSet = this.querySelector("[name='men_set']").checked;
      const womenSet = this.querySelector("[name='women_set']").checked;

      const errors = [];
      if (!customerName) errors.push("Please enter your full name.");
      if (!phoneNumber || !/^\+?[0-9]{10,11}$/.test(phoneNumber)) {
        errors.push("Please enter a valid phone number.");
      }
      if (!address) errors.push("Please enter your address.");
      if (!province) errors.push("Please select a province.");
      if (!city) errors.push("Please select a city.");
      if (!barangay) errors.push("Please select a barangay.");
      if (!menSet && !womenSet) {
        errors.push("Please select at least one product (Men's or Women's Set).");
      }

      if (errors.length > 0) {
        showModal(
          "Submission Error",
          "<ul class='list-disc pl-5'>" +
          errors.map(err => `<li>${err}</li>`).join("") +
          "</ul>"
        );
        return;
      }

      // Show loading state
      const originalBtnText = submitBtn.textContent;
      submitBtn.textContent = "Loading...";
      submitBtn.disabled = true;
      submitBtn.classList.add("opacity-50", "cursor-not-allowed");

      try {
        // Fetch IP address from GeoJS API
        let ipAddress = "::1";
        try {
          const ipResponse = await fetch("https://get.geojs.io/v1/ip.json");
          const ipData = await ipResponse.json();
          if (ipData.ip) {
            ipAddress = ipData.ip;
          } 
          else {
            console.warn("GeoJS API did not return an IP address. Using default value.");
          }
        } 
        catch (ipError) {
          console.error("GeoJS API fetch failed:", ipError);
        }

        const formData = new FormData(this);
        formData.append("ip_address", ipAddress);

        const response = await fetch("submit_order.php", {
          method: "POST",
          body: formData
        });

        const data = await response.json();

        if (data.success) {
          showModal(
            "Order Submitted",
            data.message,
            `
              <p><strong>Name:</strong> ${data.order.customer_name}</p>
              <p><strong>Phone:</strong> ${data.order.phone_number}</p>
              <p><strong>Address:</strong> ${data.order.address}, ${data.order.barangay}, ${data.order.city}, ${data.order.province}</p>
              <p><strong>Products:</strong> ${data.order.men_set ? "Men's Set" : ""} ${data.order.women_set ? "Women's Set" : ""}</p>
            `
          );
          this.reset();
          // Reset dropdowns
          provinceSelect.innerHTML = '<option value="" disabled selected>Select Province</option>';
          citySelect.innerHTML = '<option value="" disabled selected>Select City/Municipality</option>';
          barangaySelect.innerHTML = '<option value="" disabled selected>Select Barangay</option>';
          citySelect.disabled = true;
          barangaySelect.disabled = true;
          initializeForm();
        } 
        else {
          showModal(
            "Submission Error",
            "<ul class='list-disc pl-5'>" +
            data.errors.map(err => `<li>${err}</li>`).join("") +
            "</ul>"
          );
        }
      } 
      catch (err) {
        console.error("Form submission failed:", err);
        showModal(
          "Network Error",
          "Could not connect to the server. Please try again later."
        );
      } 
      finally {
        submitBtn.textContent = originalBtnText;
        submitBtn.disabled = false;
        submitBtn.classList.remove("opacity-50", "cursor-not-allowed");
      }
    });
  }

  // Modal Logic
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

  // Carousel Logic
  function setupCarousel(carousel) {
    const slides = carousel.querySelectorAll("img");
    const displaySelector = carousel.getAttribute("data-display");
    const display = document.querySelector(displaySelector);

    let index = Array.from(slides).findIndex(slide => slide.src === display.src);
    if (index === -1) index = 0;

    let interval;

    function showSlide(i) {
      index = (i + slides.length) % slides.length;

      if (display) {
        display.style.opacity = 0;
        setTimeout(() => {
          display.src = slides[index].src;
          display.alt = slides[index].alt;
          display.style.opacity = 1;
        }, 200);
      }

      slides.forEach((slide, j) => {
        slide.classList.toggle("active", j === index);
      });
    }

    function startAutoPlay() {
      stopAutoPlay();
      interval = setInterval(() => {
        showSlide(index + 1);
      }, 5000);
    }

    function stopAutoPlay() {
      if (interval) clearInterval(interval);
    }

    showSlide(index);
    startAutoPlay();

    const prevBtn = carousel.parentElement.querySelector(".carousel-btn.prev");
    const nextBtn = carousel.parentElement.querySelector(".carousel-btn.next");

    if (prevBtn) prevBtn.addEventListener("click", () => { showSlide(index - 1); startAutoPlay(); });
    if (nextBtn) nextBtn.addEventListener("click", () => { showSlide(index + 1); startAutoPlay(); });

    slides.forEach((slide, i) => {
      slide.addEventListener("click", () => {
        showSlide(i);
        startAutoPlay();
      });
    });
  }

  document.querySelectorAll(".carousel").forEach(setupCarousel);
});