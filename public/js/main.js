"use strict";
if (window.location.hash === "#_=_") {
    history.replaceState(null, null, window.location.href.split("#")[0]);
}

$(document).ready(function () {
    // Pré-remplir le formulaire de modification
    $('.bouton-modifier-adherent').click(function () {
        const id = $(this).data('id');
        const nom = $(this).data('nom');
        const prenom = $(this).data('prenom');
        const grade = $(this).data('grade');
        const photo = $(this).data('photo');

        // Injecter les données dans les champs du formulaire
        $('#modifier-id-adherent').val(id);
        $('#modifier-nom-adherent').val(nom);
        $('#modifier-prenom-adherent').val(prenom);
        $('#modifier-grade-adherent').val(grade);

        // Prévisualiser l'image si elle existe
        if (photo) {
            $('#modifierPhotoPreviewAdherent').attr('src', photo).show();
        }
    });


    $('.bouton-modifier-partenaire').click(function () {
        const idPartenaire = this.getAttribute('data-id');
        const info = this.getAttribute('data-info');
        const lien = this.getAttribute('data-lien');
        const logo = this.getAttribute('data-logo');

        document.getElementById('modifier-id').value = idPartenaire;
        document.getElementById('modifier-info').value = info;
        document.getElementById('modifier-lien').value = lien;

        if (logo) {
            $('#modifierLogoPreview').attr('src', logo).show();
        }
    });
    $('.bouton-modifier-album').click(function () {
        const idAlbums = $(this).data('idalbums'); // Récupération de l'ID
        const date = $(this).data('date');
        const nom = $(this).data('nom');
        const photo = $(this).data('photo');

        $('#modifier-id-album').val(idAlbums); // Remplissage du champ caché
        $('#modifier-date-album').val(date);
        $('#modifier-nom-album').val(nom);

        if (photo) {
            $('#modifierPhotoPreviewAlbum').attr('src', photo).show();
        } else {
            $('#modifierPhotoPreviewAlbum').hide();
        }
    });

});

// Fonction de prévisualisation d'image
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

// Gestion du glisser-déposer pour le fichier d'inscription
$(document).ready(function () {
    var dropArea = $('#drop-area');
    var fileInput = $('#fichier_inscription');
    var fileNameDisplay = $('#file-name');

    dropArea.on('dragover', function (e) {
        e.preventDefault();
        e.stopPropagation();
        dropArea.css('background', 'rgba(0, 140, 255, 0.1)');
    });

    dropArea.on('dragleave', function (e) {
        e.preventDefault();
        e.stopPropagation();
        dropArea.css('background', 'transparent');
    });

    dropArea.on('drop', function (e) {
        e.preventDefault();
        e.stopPropagation();
        dropArea.css('background', 'transparent');

        var files = e.originalEvent.dataTransfer.files;
        if (files.length > 0) {
            fileInput[0].files = files;
            fileNameDisplay.text(files[0].name); // Afficher le nom du fichier déposé
        }
    });

    fileInput.on('change', function () {
        var file = fileInput[0].files[0];
        if (file) {
            fileNameDisplay.text(file.name); // Afficher le nom du fichier sélectionné
        }
    });

    dropArea.on('click', function () {
        fileInput.click(); // Déclencher l'événement de clic sur l'input
    });
});


/*pour la carte*/
document.addEventListener("DOMContentLoaded", function () {
    // Vérifier que l'élément de la carte existe
    let mapElement = document.getElementById('map');
    if (!mapElement) return;

    // Récupérer le conteneur et ses données initiales
    let mapContainer = document.getElementById('map-container');
    let initialLat = parseFloat(mapContainer.getAttribute('data-lat'));
    let initialLon = parseFloat(mapContainer.getAttribute('data-lon'));

    if (isNaN(initialLat) || isNaN(initialLon)) {
        console.error("Les coordonnées initiales sont invalides.");
        return;
    }

    // Initialiser la carte avec Leaflet
    let leafletMap = L.map('map').setView([initialLat, initialLon], 15);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(leafletMap);
    leafletMap.invalidateSize(); // Permet de bien afficher la carte

    // Récupérer l'URL du logo
    let logoUrl = mapContainer.getAttribute('data-logo');

    // Fonction utilitaire pour générer le contenu du popup
    function getPopupContent(logoUrl, adresse) {
        let encodedAdresse = encodeURIComponent(adresse);
        let googleMapsUrl = `https://www.google.com/maps/search/?api=1&query=${encodedAdresse}`;
        return `
            <a href="${googleMapsUrl}" target="_blank" style="text-decoration: none; color: inherit;">
                <div id="popup-content"
                    style="text-align: center; font-family: Arial, sans-serif; padding: 10px; border-radius: 8px; transition: background-color 0.3s ease, color 0.3s ease;"
                    onmouseover="this.style.backgroundColor='#1D2243'; this.style.color='white';"
                    onmouseout="this.style.backgroundColor='white'; this.style.color='black';"
                >
                    <img src="${logoUrl}" alt="Logo de l'association" style="width: 70px; height: auto;" />
                    <p style="margin: 5px 0; font-weight: bold;">La Plume Monclaraise</p>
                    <p style="margin: 0;">${adresse}</p>
                    <p style="font-size: 12px; color: gray;">(Cliquez pour voir sur Google Maps)</p>
                </div>
            </a>
        `;
    }

    // Créer le marker initial et lui attacher un popup
    let adresseInput = document.getElementById('adresse'); // le champ de saisie de l'adresse
    let initialAdresse = adresseInput ? adresseInput.value.trim() : "Adresse non renseignée";

    let marker = L.marker([initialLat, initialLon]).addTo(leafletMap);
    marker.bindPopup(getPopupContent(logoUrl, initialAdresse)).openPopup();

    // Fonction debounce pour éviter trop d'appels API à chaque frappe
    function debounce(func, delay) {
        let debounceTimer;
        return function () {
            const context = this;
            const args = arguments;
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => func.apply(context, args), delay);
        };
    }

    // Mettre à jour en temps réel le popup ET la position du marker dès qu'on saisit une nouvelle adresse
    adresseInput.addEventListener('input', debounce(function () {
        let nouvelleAdresse = this.value.trim();
        let popupContent = nouvelleAdresse !== "" 
                           ? getPopupContent(logoUrl, nouvelleAdresse) 
                           : getPopupContent(logoUrl, "Adresse non renseignée");

        // Mettre à jour le contenu du popup et l'afficher
        if (marker.getPopup()) {
            marker.getPopup().setContent(popupContent);
        } else {
            marker.bindPopup(popupContent);
        }
        marker.openPopup();

        // Si une adresse est saisie, effectuer le géocodage pour obtenir de nouvelles coordonnées
        if (nouvelleAdresse !== "") {
            let url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(nouvelleAdresse)}`;
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data && data.length > 0) {
                        let newLat = parseFloat(data[0].lat);
                        let newLon = parseFloat(data[0].lon);
                        // Mettre à jour la vue de la carte et la position du marker
                        leafletMap.setView([newLat, newLon], 15);
                        marker.setLatLng([newLat, newLon]);
                    }
                })
                .catch(error => console.error("Erreur lors du géocodage :", error));
        }
    }, 800));
});









function zoomImage(imageSrc) {
    const zoomContainer = document.getElementById('zoom-container');
    const zoomedImage = document.getElementById('zoomed-image');

    // Modifie la source de l'image zoomée
    zoomedImage.src = imageSrc;

    // Affiche le conteneur du zoom
    zoomContainer.style.display = 'flex';
}

function closeZoom() {
    // Cache le conteneur du zoom
    const zoomContainer = document.getElementById('zoom-container');
    zoomContainer.style.display = 'none';
}


/*carrousel facebook */


document.addEventListener('DOMContentLoaded', function () {
    let currentIndex = 0; // L'index de l'élément actuellement affiché
    const posts = document.querySelectorAll('.carousel-cell'); // Tous les posts
    const totalPosts = posts.length;
    const wrapper = document.querySelector('.carousel-wrapper');

    // S'assurer que la largeur du carousel est ajustée à la taille des posts
    wrapper.style.width = `${totalPosts * 100}%`;

    // Fonction pour afficher l'élément suivant
    function showNext() {
        if (currentIndex < totalPosts - 1) {
            currentIndex++;
        } else {
            currentIndex = 0; // Si on est à la fin, on revient au début
        }
        updateCarousel();
    }

    // Fonction pour afficher l'élément précédent
    function showPrev() {
        if (currentIndex > 0) {
            currentIndex--;
        } else {
            currentIndex = totalPosts - 1; // Si on est au début, on revient à la fin
        }
        updateCarousel();
    }

    // Mettre à jour la position du carousel
    function updateCarousel() {
        const offset = -currentIndex * (100 / totalPosts); // Décale le carousel d'une largeur d'élément
        wrapper.style.transform = `translateX(${offset}%)`; // Applique la transformation
    }

    // Événements pour les boutons
    document.getElementById('nextButton').addEventListener('click', showNext);
    document.getElementById('prevButton').addEventListener('click', showPrev);

    // Slide automatique toutes les 5 secondes
    setInterval(showNext, 5000); // Change d'élément toutes les 5 secondes

    // Initialisation de l'affichage
    updateCarousel();
});

// Récupérer l'élément du carousel
const carouselWrapper = document.querySelector('.carousel-wrapper');
const cells = document.querySelectorAll('.carousel-cell');

// Définir la largeur totale de la wrapper
let cellWidth = cells[0].offsetWidth + 20; // Largeur de la cellule + margin (10px de chaque côté)
let index = 0;

// Fonction pour faire défiler les publications
function slideCarousel() {
    index++;

    // Si on a atteint la dernière publication, on revient à la première
    if (index >= cells.length) {
        index = 0;
    }

    // Déplacer la wrapper à la position souhaitée
    carouselWrapper.style.transform = `translateX(-${index * cellWidth}px)`;
}

// Appeler la fonction toutes les 3 secondes
setInterval(slideCarousel, 3000);

// Fonction pour ajouter une publication à la fin du carousel
function addPost(postHTML) {
    const maxPosts = 10;
    const carouselCells = document.querySelectorAll('.carousel-cell');

    // Si le nombre de publications dépasse 10, supprimer la plus ancienne
    if (carouselCells.length >= maxPosts) {
        carouselWrapper.removeChild(carouselCells[0]); // Supprimer la première cellule
    }

    // Ajouter la nouvelle publication à la fin
    const newPost = document.createElement('div');
    newPost.classList.add('carousel-cell');
    newPost.innerHTML = postHTML;
    carouselWrapper.appendChild(newPost);
}

/*pour les boutons modifier */

// Récupère tous les boutons avec la classe edit-button
const buttons = document.querySelectorAll('.edit-button');

// Modifie le texte de chaque bouton au survol
buttons.forEach(button => {
    button.addEventListener('mouseover', function () {
        const text = button.getAttribute('data-text');
        button.setAttribute('data-original-text', text);
    });
});

