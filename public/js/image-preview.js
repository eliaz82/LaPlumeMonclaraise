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

//Script JavaScript pour la prévisualisation de plusieurs images dans la page Photos
function previewImagesPhotos(event) {
    const files = event.target.files;
    const previewContainer = document.getElementById('photoPreview');
    previewContainer.innerHTML = ''; // Réinitialise le conteneur pour effacer les anciens aperçus

    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        // Vérifier que le fichier est une image
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();

            reader.onload = function (e) {
                const img = document.createElement("img");
                img.src = e.target.result;
                img.alt = "Aperçu de la photo " + (i + 1);
                img.style.maxWidth = "80%";
                img.style.maxHeight = "200px";
                img.style.borderRadius = "8px";
                img.style.objectFit = "cover";
                img.style.padding = "5px";
                img.style.margin = "5px"; // Pour espacer les images entre elles

                previewContainer.appendChild(img);
            };

            reader.readAsDataURL(file);
        }
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