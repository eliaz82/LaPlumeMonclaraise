document.addEventListener("DOMContentLoaded", function () {
    const configData = document.getElementById("asso-data");
    const getAssociationDataUrl = configData.dataset.associationDataUrl;
    // -------------------------------
    // Récupération des Données d'Email et d'Association
    // -------------------------------

    // Récupération des données de l'association
    fetch(getAssociationDataUrl)
        .then(response => {
            if (!response.ok) {
                throw new Error("Erreur lors de la récupération des données.");
            }
            return response.json();
        })
        .then(data => {
            // Mise à jour de l'adresse
            if (data.adresse) {
                const adresseInput = document.getElementById("adresse");
                if (adresseInput) {
                    adresseInput.value = data.adresse;
                    adresseInput.dispatchEvent(new Event("input"));
                }
                document.querySelectorAll(".adresseDisplay").forEach(el => {
                    el.textContent = data.adresse;
                });
            }

            // Mise à jour de la latitude
            if (data.latitude) {
                const latitudeInput = document.getElementById("latitude");
                if (latitudeInput) {
                    latitudeInput.value = data.latitude;
                }
            }

            // Mise à jour de la longitude
            if (data.longitude) {
                const longitudeInput = document.getElementById("longitude");
                if (longitudeInput) {
                    longitudeInput.value = data.longitude;
                }
            }

            // Mise à jour du téléphone
            if (data.tel) {
                const telephoneInput = document.getElementById("telephoneInput");
                if (telephoneInput) {
                    telephoneInput.value = data.tel;
                    telephoneInput.dispatchEvent(new Event("input"));
                }
                document.querySelectorAll(".telephoneDisplay").forEach(el => {
                    el.textContent = data.tel;
                });
            }

            // Mise à jour de l'email
            if (data.email) {
                const emailInput = document.getElementById("mailContact"); // Correctement fait référence à l'ID 'mailContact'
                if (emailInput) {
                    emailInput.value = data.email;
                    emailInput.dispatchEvent(new Event("input"));
                }
                document.querySelectorAll(".emailDisplay").forEach(el => {
                    el.textContent = data.email;
                });
            }

        })
        .catch(error => console.error("Erreur lors de la récupération des données de localisation :", error));

});