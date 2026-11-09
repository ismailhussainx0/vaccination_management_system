// LANDING PAGE - mobile menu + navbar shadow

(function () {

    const header = document.querySelector(".site-header");
    const toggle = document.querySelector(".menu-toggle");
    const links = document.getElementById("navLinks");

    if (!toggle || !links) return;

    function setMenu(open) {
        links.classList.toggle("open", open);
        toggle.setAttribute("aria-expanded", open ? "true" : "false");
        toggle.querySelector("i").className = open ? "fa-solid fa-xmark" : "fa-solid fa-bars";
    }

    toggle.addEventListener("click", function () {
        setMenu(!links.classList.contains("open"));
    });

    links.querySelectorAll("a").forEach(function (a) {
        a.addEventListener("click", function () { setMenu(false); });
    });

    document.addEventListener("keydown", function (e) {
        if (e.key === "Escape") setMenu(false);
    });

    window.addEventListener("resize", function () {
        if (window.innerWidth > 820) setMenu(false);
    });

    function onScroll() {
        header.classList.toggle("scrolled", window.scrollY > 8);
    }

    window.addEventListener("scroll", onScroll, { passive: true });
    onScroll();

})();
