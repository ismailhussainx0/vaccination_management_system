// APPOINTMENT SEARCH

const searchInput = document.querySelector(".table-search input");

if (searchInput) {

    searchInput.disabled = false;

    searchInput.addEventListener("input", function () {

        const searchValue = this.value.toLowerCase();

        const appointmentRows = document.querySelectorAll(
            ".appointments-table tbody tr"
        );

        appointmentRows.forEach(function (row) {

            const rowText = row.textContent.toLowerCase();

            row.style.display = rowText.includes(searchValue) ? "" : "none";

        });

    });

}


// MOBILE SIDEBAR DRAWER

(function () {

    const body = document.body;
    const sidebar = document.getElementById("sidebar");
    const toggles = document.querySelectorAll("[data-menu-toggle]");
    const closers = document.querySelectorAll("[data-menu-close]");

    if (!sidebar) return;

    function openMenu() {
        body.classList.add("menu-open");
        toggles.forEach(function (t) { t.setAttribute("aria-expanded", "true"); });
    }

    function closeMenu() {
        body.classList.remove("menu-open");
        toggles.forEach(function (t) { t.setAttribute("aria-expanded", "false"); });
    }

    toggles.forEach(function (t) {
        t.addEventListener("click", function () {
            body.classList.contains("menu-open") ? closeMenu() : openMenu();
        });
    });

    closers.forEach(function (c) { c.addEventListener("click", closeMenu); });

    sidebar.querySelectorAll("a.nav-link").forEach(function (link) {
        link.addEventListener("click", closeMenu);
    });

    document.addEventListener("keydown", function (e) {
        if (e.key === "Escape") closeMenu();
    });

    window.addEventListener("resize", function () {
        if (window.innerWidth > 1024) closeMenu();
    });

})();
