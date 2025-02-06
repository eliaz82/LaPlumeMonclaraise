"use strict";
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