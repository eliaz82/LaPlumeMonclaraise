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

    $(document).ready(function () {
        let isButtonClicked = false;

        $('#refreshButton').click(function () {
            if (isButtonClicked) return; // Si le bouton est déjà cliqué, ne rien faire

            isButtonClicked = true; // Marquer que le bouton a été cliqué

            let refreshUrl = $(this).data('refresh-url');

            // Afficher un indicateur de chargement (changer le texte du bouton)
            $(this).html('<i class="bi bi-arrow-clockwise spin"></i> Chargement...').prop('disabled', true);

            $.ajax({
                url: refreshUrl,
                type: 'POST',
                dataType: 'json',
                success: function (response) {
                    // Pas de message, on recharge simplement la page
                    location.reload();
                },
                error: function () {
                    alert('Erreur lors de l\'actualisation');
                },
                complete: function () {
                    // Réinitialiser l'indicateur de chargement et réactiver le bouton
                    $('#refreshButton').html('<i class="bi bi-arrow-clockwise"></i> Rafraîchir').prop('disabled', false);
                    // Réinitialiser l'état du bouton après un délai (par exemple, 2 secondes)
                    setTimeout(function () {
                        isButtonClicked = false;
                    }, 2000); // 2 secondes de délai
                }
            });
        });
    });

});