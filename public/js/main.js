"use strict";

// Enlever le hash "#_=_" de l'URL pour éviter que ce dernier ne perturbe l'URL de votre page
if (window.location.hash === "#_=_") {
    history.replaceState(null, null, window.location.href.split("#")[0]);
}
//Pour le menu navbar ajuster
document.addEventListener("DOMContentLoaded", () => {
    const navbar = document.querySelector('.navbar.fixed-top');
    const contentWrapper = document.body;
    if (navbar) {
        const adjustBodyPadding = () => {
            contentWrapper.style.paddingTop = `${navbar.offsetHeight}px`;
        };
        adjustBodyPadding();
        window.addEventListener('resize', adjustBodyPadding);
    }
});

// -------------------------------
// Bouton "Back to Top"
// -------------------------------
document.addEventListener('DOMContentLoaded', function () {
    const backToTopButton = document.getElementById("back-to-top");

    window.onscroll = function () {
        if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
            backToTopButton.style.display = "flex";
        } else {
            backToTopButton.style.display = "none";
        }
    };

    backToTopButton.onclick = function () {
        window.scrollTo({ top: 0, behavior: "smooth" });
        return false;
    };
});