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

    $(document).ready(function() {
        const cooldownPeriod = 60000; // 1 minute en millisecondes
        const $refreshButton = $('#refreshButton');
        
        // Détermine si le délai doit être appliqué en fonction de l'attribut data-delay
        const applyDelay = $refreshButton.data('delay') === true;
        
        // Si le délai est appliqué, vérifie dans le localStorage au chargement de la page
        if (applyDelay) {
            const lastClickTime = localStorage.getItem('refreshButtonLastClick');
            if (lastClickTime) {
                const elapsed = Date.now() - parseInt(lastClickTime, 10);
                if (elapsed < cooldownPeriod) {
                    const remainingTime = cooldownPeriod - elapsed;
                    disableButton(remainingTime);
                } else {
                    localStorage.removeItem('refreshButtonLastClick');
                }
            }
        }
        
        // Gestion du clic sur le bouton
        $refreshButton.on('click', function() {
            // Si le bouton est déjà désactivé, ne rien faire
            if ($refreshButton.prop('disabled')) {
                return;
            }
            
            // Si le délai doit être appliqué, stocke l'heure du clic et désactive le bouton
            if (applyDelay) {
                localStorage.setItem('refreshButtonLastClick', Date.now());
                disableButton(cooldownPeriod);
            }
            
            // Récupère l'URL de rafraîchissement depuis l'attribut data
            const refreshUrl = $(this).data('refresh-url');
            
            // Affiche un indicateur de chargement
            $refreshButton.html('<i class="bi bi-arrow-clockwise spin"></i> Chargement...');
            
            // Appel AJAX
            $.ajax({
                url: refreshUrl,
                type: 'POST',
                dataType: 'json',
                success: function(response) {
                    // En cas de succès, recharge la page
                    location.reload();
                },
                error: function() {
                    alert('Erreur lors de l\'actualisation');
                    // En cas d'erreur, vous pouvez décider de réactiver le bouton immédiatement ou non
                }
            });
        });
        
        // Fonction qui désactive le bouton pendant une durée donnée
        function disableButton(duration) {
            $refreshButton.prop('disabled', true)
                          .addClass('disabled') // Pour appliquer un style CSS "grisé" (à définir dans vos styles)
                          .html('<i class="bi bi-arrow-clockwise"></i> Rafraîchir (attente)');
            
            setTimeout(function() {
                $refreshButton.prop('disabled', false)
                              .removeClass('disabled')
                              .html('<i class="bi bi-arrow-clockwise"></i> Rafraîchir');
                // Une fois le délai écoulé, on retire l'info du dernier clic
                localStorage.removeItem('refreshButtonLastClick');
            }, duration);
        }
    });
});