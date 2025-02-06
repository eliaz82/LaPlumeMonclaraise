"use strict";

// Enlever le hash "#_=_" de l'URL pour éviter que ce dernier ne perturbe l'URL de votre page
if (window.location.hash === "#_=_") {
    history.replaceState(null, null, window.location.href.split("#")[0]);
}

$(document).ready(function () {
    // Pré-remplir le formulaire de modification d'adhérent
    $('.bouton-modifier-adherent').click(function () {
        const id = $(this).data('id');
        const nom = $(this).data('nom');
        const prenom = $(this).data('prenom');
        const grade = $(this).data('grade');
        const photo = $(this).data('photo');

        // Injecter les données dans les champs du formulaire de manière sécurisée
        $('#modifier-id-adherent').val(id);
        $('#modifier-nom-adherent').val(nom);
        $('#modifier-prenom-adherent').val(prenom);
        $('#modifier-grade-adherent').val(grade);

        // Prévisualiser l'image si elle existe
        if (photo) {
            $('#modifierPhotoPreviewAdherent').attr('src', photo).show();
        }
    });

    // Pré-remplir le formulaire de modification du partenaire
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

    // Pré-remplir le formulaire de modification d'album
    $('.bouton-modifier-album').click(function () {
        const idAlbums = $(this).data('idalbums');
        const date = $(this).data('date');
        const nom = $(this).data('nom');
        const photo = $(this).data('photo');

        $('#modifier-id-album').val(idAlbums);
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

// Sécurisation de la carte avec Leaflet
document.addEventListener("DOMContentLoaded", function () {
    let mapElement = document.getElementById('map');
    if (!mapElement) return;

    let mapContainer = document.getElementById('map-container');
    let initialLat = parseFloat(mapContainer.getAttribute('data-lat'));
    let initialLon = parseFloat(mapContainer.getAttribute('data-lon'));

    if (isNaN(initialLat) || isNaN(initialLon)) {
        console.error("Les coordonnées initiales sont invalides.");
        return;
    }

    let leafletMap = L.map('map').setView([initialLat, initialLon], 15);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(leafletMap);
    leafletMap.invalidateSize();

    let logoUrl = mapContainer.getAttribute('data-logo');

    function getPopupContent(logoUrl, adresse) {
        let encodedAdresse = encodeURIComponent(adresse);
        let googleMapsUrl = `https://www.google.com/maps/search/?api=1&query=${encodedAdresse}`;
        return `
            <a href="${googleMapsUrl}" target="_blank" style="text-decoration: none; color: inherit;">
                <div id="popup-content" style="text-align: center; font-family: Arial, sans-serif; padding: 10px; border-radius: 8px; transition: background-color 0.3s ease, color 0.3s ease;" onmouseover="this.style.backgroundColor='#1D2243'; this.style.color='white';" onmouseout="this.style.backgroundColor='white'; this.style.color='black';">
                    <img src="${logoUrl}" alt="Logo de l'association" style="width: 70px; height: auto;" />
                    <p style="margin: 5px 0; font-weight: bold;">La Plume Monclaraise</p>
                    <p style="margin: 0;">${adresse}</p>
                    <p style="font-size: 12px; color: gray;">(Cliquez pour voir sur Google Maps)</p>
                </div>
            </a>
        `;
    }

    let adresseInput = document.getElementById('adresse');
    let initialAdresse = adresseInput ? adresseInput.value.trim() : "Adresse non renseignée";

    let marker = L.marker([initialLat, initialLon]).addTo(leafletMap);
    marker.bindPopup(getPopupContent(logoUrl, initialAdresse)).openPopup();

    function debounce(func, delay) {
        let debounceTimer;
        return function () {
            const context = this;
            const args = arguments;
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => func.apply(context, args), delay);
        };
    }

    adresseInput.addEventListener('input', debounce(function () {
        let nouvelleAdresse = this.value.trim();
        let popupContent = nouvelleAdresse !== "" 
                           ? getPopupContent(logoUrl, nouvelleAdresse) 
                           : getPopupContent(logoUrl, "Adresse non renseignée");

        if (marker.getPopup()) {
            marker.getPopup().setContent(popupContent);
        } else {
            marker.bindPopup(popupContent);
        }
        marker.openPopup();

        if (nouvelleAdresse !== "") {
            let url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(nouvelleAdresse)}`;
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data && data.length > 0) {
                        let newLat = parseFloat(data[0].lat);
                        let newLon = parseFloat(data[0].lon);
                        leafletMap.setView([newLat, newLon], 15);
                        marker.setLatLng([newLat, newLon]);
                    }
                })
                .catch(error => console.error("Erreur lors du géocodage :", error));
        }
    }, 800));
});

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

// Carrousel Facebook sécurisé
document.addEventListener('DOMContentLoaded', function () {
    let currentIndex = 0;
    const posts = document.querySelectorAll('.carousel-cell');
    const totalPosts = posts.length;
    const wrapper = document.querySelector('.carousel-wrapper');

    wrapper.style.width = `${totalPosts * 100}%`;

    function showNext() {
        if (currentIndex < totalPosts - 1) {
            currentIndex++;
        } else {
            currentIndex = 0;
        }
        updateCarousel();
    }

    function showPrev() {
        if (currentIndex > 0) {
            currentIndex--;
        } else {
            currentIndex = totalPosts - 1;
        }
        updateCarousel();
    }

    function updateCarousel() {
        const offset = -currentIndex * (100 / totalPosts);
        wrapper.style.transform = `translateX(${offset}%)`;
    }

    document.getElementById('nextButton').addEventListener('click', showNext);
    document.getElementById('prevButton').addEventListener('click', showPrev);

    setInterval(showNext, 5000);
    updateCarousel();
});

const carouselWrapper = document.querySelector('.carousel-wrapper');
const cells = document.querySelectorAll('.carousel-cell');

let cellWidth = cells[0].offsetWidth + 20;
let index = 0;

function slideCarousel() {
    index++;
    if (index >= cells.length) {
        index = 0;
    }
    carouselWrapper.style.transform = `translateX(-${index * cellWidth}px)`;
}

setInterval(slideCarousel, 3000);

function addPost(postHTML) {
    const maxPosts = 10;
    const carouselCells = document.querySelectorAll('.carousel-cell');

    if (carouselCells.length >= maxPosts) {
        carouselWrapper.removeChild(carouselCells[0]);
    }

    const newPost = document.createElement('div');
    newPost.classList.add('carousel-cell');
    newPost.innerHTML = postHTML;
    carouselWrapper.appendChild(newPost);
}

// Sécurisation des boutons de modification
const buttons = document.querySelectorAll('.edit-button');

buttons.forEach(button => {
    button.addEventListener('mouseover', function () {
        const text = button.getAttribute('data-text');
        button.setAttribute('data-original-text', text);
    });
});
