"use strict";
document.addEventListener("DOMContentLoaded", function () {
    // Vérifie que l'élément de la carte existe
    let mapElement = document.getElementById('map');
    if (!mapElement) return;

    // Récupère les données depuis #map-container
    let mapContainer = document.getElementById('map-container');
    let initialLat = parseFloat(mapContainer.getAttribute('data-lat'));
    let initialLon = parseFloat(mapContainer.getAttribute('data-lon'));
    let logoUrl = mapContainer.getAttribute('data-logo');
    // Récupère l'adresse directement depuis l'attribut data-adresse
    let initialAdresse = mapContainer.getAttribute('data-adresse') || "Adresse non renseignée";

    if (isNaN(initialLat) || isNaN(initialLon)) {
        console.error("Les coordonnées initiales sont invalides.");
        return;
    }

    // Initialise la carte centrée sur les coordonnées initiales
    let leafletMap = L.map('map').setView([initialLat, initialLon], 15);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    }).addTo(leafletMap);
    leafletMap.invalidateSize();

    // Fonction pour générer le contenu de la popup
    function getPopupContent(logoUrl, adresse) {
        let encodedAdresse = encodeURIComponent(adresse);
        let googleMapsUrl = `https://www.google.com/maps/search/?api=1&query=${encodedAdresse}`;
        return `
            <a href="${googleMapsUrl}" target="_blank" style="text-decoration: none; color: inherit;">
                <div id="popup-content" style="text-align: center; font-family: Arial, sans-serif; padding: 10px; border-radius: 8px; transition: background-color 0.3s ease, color 0.3s ease;" 
                     onmouseover="this.style.backgroundColor='#1D2243'; this.style.color='white';" 
                     onmouseout="this.style.backgroundColor='white'; this.style.color='black';">
                    <img src="${logoUrl}" alt="Logo de l'association" style="width: 70px; height: auto;" />
                    <p style="margin: 5px 0; font-weight: bold;">La Plume Monclaraise</p>
                    <p style="margin: 0;">${adresse}</p>
                    <p style="font-size: 12px; color: gray;">(Cliquez pour voir sur Google Maps)</p>
                </div>
            </a>
        `;
    }

    // Crée le marqueur initial à la position donnée et lie la popup avec l'adresse
    let marker = L.marker([initialLat, initialLon]).addTo(leafletMap);
    marker.bindPopup(getPopupContent(logoUrl, initialAdresse)).openPopup();

    // Géocode l'adresse récupérée pour mettre à jour la localisation sur la carte
    if (initialAdresse !== "Adresse non renseignée") {
        let url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(initialAdresse)}`;
        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data && data.length > 0) {
                    let newLat = parseFloat(data[0].lat);
                    let newLon = parseFloat(data[0].lon);
                    // Met à jour la vue de la carte et déplace le marqueur
                    leafletMap.setView([newLat, newLon], 15);
                    marker.setLatLng([newLat, newLon]);
                    // Met à jour le contenu de la popup avec la même adresse
                    marker.getPopup().setContent(getPopupContent(logoUrl, initialAdresse));
                }
            })
            .catch(error => console.error("Erreur lors du géocodage :", error));
    }
});
