"use strict";

$(document).ready(function() {
    $('#refreshButton').click(function() {
        let refreshUrl = $(this).data('refresh-url'); // Récupérer l'URL depuis l'attribut data

        $.ajax({
            url: refreshUrl,  // URL récupérée depuis le bouton
            type: 'POST',
            dataType: 'json',  // Attente d'une réponse JSON
            success: function(response) {
                if(response.status === 'success') {
                    alert(response.message);  // Afficher un message de succès
                    location.reload();  // Recharger la page pour afficher les nouvelles données
                }
            },
            error: function() {
                alert('Erreur lors de l\'actualisation du cache');
            }
        });
    });
});
