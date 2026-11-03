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

            if (rowText.includes(searchValue)) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }

        });

    });

}


// MOBILE SIDEBAR TOGGLE

const menuButton = document.querySelector(".mobile-menu-btn");
const sidebar = document.querySelector(".sidebar");

if (menuButton && sidebar) {

    menuButton.addEventListener("click", function () {

        sidebar.classList.toggle("open");

    });

    const sidebarLinks = document.querySelectorAll(".sidebar-nav .nav-link");

    sidebarLinks.forEach(function (link) {

        link.addEventListener("click", function () {
            sidebar.classList.remove("open");
        });

    });

}