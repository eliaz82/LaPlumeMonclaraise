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
    let formHeight = document.querySelector('form').offsetHeight; // Récupérer la hauteur du formulaire
    let map = document.getElementById('map');
    map.style.height = formHeight + '600px'; // Donner à la carte la même hauteur que le formulaire

    let lat = 43.966742479238754;
    let lon = 1.5866446106619663;

    let leafletMap = L.map('map').setView([lat, lon], 15);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(leafletMap);

    // Récupérer l'URL du logo depuis un attribut data-logo
    let logoUrl = document.getElementById('map-container').getAttribute('data-logo');

    // Adresse complète encodée pour Google Maps
    let address = encodeURIComponent("esplanade du lac 82230 Monclar-de-Quercy, France");
    let googleMapsUrl = `https://www.google.com/maps/search/?api=1&query=${address}`;

    // Ajouter un marker avec un pop-up cliquable qui redirige vers Google Maps avec l'adresse
    L.marker([lat, lon]).addTo(leafletMap)
        .bindPopup(`
            <a href="${googleMapsUrl}" target="_blank" style="text-decoration: none; color: inherit;">
                <div id="popup-content"
                    style="text-align: center; font-family: Arial, sans-serif; padding: 10px; border-radius: 8px; transition: background-color 0.3s ease, color 0.3s ease;"
                    onmouseover="this.style.backgroundColor='#1D2243'; this.style.color='white';"
                    onmouseout="this.style.backgroundColor='white'; this.style.color='black';"
                >
                    <img src="${logoUrl}" alt="Logo de l'association"
                        style="width: 70px; height: auto;"
                    />
                    <p style="margin: 5px 0; font-weight: bold;">La Plume Monclaraise</p>
                    <p style="margin: 0;">Esplanade du lac, 82230 Monclar-de-Quercy</p>
                    <p style="font-size: 12px; color: gray;">(Cliquez pour voir sur Google Maps)</p>
                </div>
            </a>
        `)
        .openPopup();
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



/*pour le carousel facebook*/
setTimeout(function () {
    if (typeof FB !== "undefined") {
        FB.XFBML.parse();
    }
}, 1000);


