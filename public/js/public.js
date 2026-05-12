function closeModal() {
    document.getElementById("resultModal").classList.remove("show");
}
window.onclick = function (event) {
    let modal = document.getElementById("resultModal");
    if (event.target == modal) {
        closeModal();
    }
};

// Navbar mobile toggle
const navToggle = document.getElementById("navToggle");
const navMenu = document.querySelector(".nav-menu");

if (navToggle && navMenu) {
    navToggle.addEventListener("click", () => {
        navMenu.classList.toggle("active");
    });
}

// Tutup menu saat link diklik (opsional)
document.querySelectorAll(".nav-menu a, .nav-menu button").forEach((link) => {
    link.addEventListener("click", () => {
        navMenu.classList.remove("active");
    });
});

function openMapModal() {
    document.getElementById("mapOnlyModal").style.display = "block";
}
function closeMapModal() {
    document.getElementById("mapOnlyModal").style.display = "none";
}
window.onclick = function (event) {
    let modal = document.getElementById("mapOnlyModal");
    if (event.target == modal) {
        modal.style.display = "none";
    }
};
