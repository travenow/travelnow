document.addEventListener("DOMContentLoaded", function () {
  const navLinks = document.querySelectorAll(".nav-links a");
  const sections = document.querySelectorAll(".section");

  // Initially show only the first section
  sections.forEach((section, index) => {
    section.style.display = index === 0 ? "block" : "none";
  });

  // Show only the selected section
  function showSection(targetId) {
    sections.forEach((section) => {
      section.style.display = section.id === targetId ? "block" : "none";
    });

    // Update active link styling
    navLinks.forEach((link) => {
      link.classList.toggle(
        "active",
        link.getAttribute("href").substring(1) === targetId
      );
    });
  }

  // Handle navigation link clicks
  navLinks.forEach((link) => {
    link.addEventListener("click", function (event) {
      event.preventDefault();
      const targetId = this.getAttribute("href").substring(1);
      showSection(targetId);
    });
  });

  // Open popup
  const openBtn = document.getElementById("openPopup");
  if (openBtn) {
    openBtn.addEventListener("click", function () {
      document.getElementById("popupSection").style.display = "flex";
    });
  }

  // Close popup
  const closeBtn = document.getElementById("closePopup");
  if (closeBtn) {
    closeBtn.addEventListener("click", function () {
      document.getElementById("popupSection").style.display = "none";
    });
  }

  // Handle switching between login/register forms
  window.switchForm = function (formId) {
    document.querySelectorAll(".form-container").forEach((form) => {
      form.classList.remove("active");
    });
    document.getElementById(formId).classList.add("active");
  };

  // Optional: fetch profile data (if used)
  const fetchBtn = document.getElementById("fetchData");
  if (fetchBtn) {
    fetchBtn.addEventListener("click", async () => {
      try {
        let response = await fetch("profile.php");
        let data = await response.json();
        document.getElementById("output").innerText = data.message;
      } catch (error) {
        console.error("Error fetching data:", error);
      }
    });
  }

  // ✅ Handle URL params for popup (login after registration)
  const urlParams = new URLSearchParams(window.location.search);
  if (urlParams.get("registered") === "true") {
    document.getElementById("popupSection").style.display = "flex";
    switchForm("loginForm");
    alert("🎉 Registration successful! Please log in.");
  }
});

