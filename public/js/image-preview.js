"use strict";
// Fonction de prévisualisation d'image pour les formulaires
function previewImage(event, previewId) {
    const input = event.target;
    const preview = document.getElementById(previewId);

    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function (e) {
            preview.src = e.target.result; // Charger l'image
            preview.style.display = 'block'; // Afficher l'image
        };

        reader.readAsDataURL(input.files[0]); // Lire le fichier
    }
}

// Zoom d'image sécurisé
function zoomImage(imageSrc) {
    const zoomContainer = document.getElementById('zoom-container');
    const zoomedImage = document.getElementById('zoomed-image');

    zoomedImage.src = imageSrc;
    zoomContainer.style.display = 'flex';
}

function closeZoom() {
    const zoomContainer = document.getElementById('zoom-container');
    zoomContainer.style.display = 'none';
}