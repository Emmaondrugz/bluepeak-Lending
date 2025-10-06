// Handle the side nav toggle behavior
const nav_button = document.querySelector(".nav-button");
const aside = document.querySelector(".side-nav");

nav_button.addEventListener("click", () => {
  if (aside.classList.contains("active")) {
    aside.classList.remove("active");
    document.body.style.overflow = "auto"; // Enable scrolling when aside is closed
  } else {
    aside.classList.toggle("active");
    document.body.style.overflow = "hidden"; // Disable scrolling when aside is open
  }
});

// Toggle dropdown Inside Nav for Loan Programs
document.querySelectorAll(".mb-nav-link").forEach(function (btn) {
  if (btn.textContent.trim() === "Loan Programs") {
    btn.addEventListener("click", function () {
      console.log("this is the button");
      var dropdown = document.querySelector(".mobile-dropdown");
      if (dropdown.style.height === "0px" || dropdown.style.height === "") {
        dropdown.style.height = "140px"; // Adjust as needed for content
      } else {
        dropdown.style.height = "0px";
      }
    });
  }
});

// The code below handles the dropdown for large screens
const trigger = document.querySelector(".trigger");
const dropdown = document.querySelector(".nav-dropdown.programmes");

// Show dropdown when mouse enters trigger
trigger.addEventListener("mouseenter", function () {
  dropdown.classList.add("active");
});

// Keep dropdown active when mouse enters dropdown itself
dropdown.addEventListener("mouseenter", function () {
  dropdown.classList.add("active");
});

// Hide dropdown when mouse leaves both trigger and dropdown
trigger.addEventListener("mouseleave", function () {
  dropdown.classList.remove("active");
});
dropdown.addEventListener("mouseleave", function () {
  dropdown.classList.remove("active");
});

document.querySelector(".trigger").addEventListener("mouseleave", function () {
  var dropdown = document.querySelector(".nav-dropdown.programmes");
  dropdown.classList.remove("active");
});
