"use strict";
document.addEventListener("DOMContentLoaded", function () {
    // Récupération des éléments du DOM
    const pageSelect = document.getElementById("pageSelect");
    const hashtagList = document.getElementById("hashtagList");
    const addHashtagBtn = document.getElementById("addHashtag");
    const hashtagInput = document.getElementById("hashtagInput");
    const tokenCountdown = document.getElementById("tokenCountdown");
    const resetTokenBtn = document.getElementById("resetTokenBtn");
    const configData = document.getElementById("config-data");

    const siteUrl = configData.dataset.siteUrl;
    const createUrl = configData.dataset.createUrl;
    const deleteUrl = configData.dataset.deleteUrl;
    const expirationUrl = configData.dataset.expirationUrl;
    const loginUrl = configData.dataset.loginUrl;
    const getEmailReceptionUrl = configData.dataset.emailReceptionUrl;
    const getAssociationDataUrl = configData.dataset.associationDataUrl;
    const getFichierInscriptionEtatUrl = configData.dataset.fichierInscriptionEtatUrl;
    const updateFichierInscriptionEtatUrl = configData.dataset.updateFichierInscriptionEtatUrl;

    // -------------------------------
    // Gestion des Hashtags
    // -------------------------------

    // Charge les hashtags pour la page sélectionnée
    function loadHashtags(pageName) {
        fetch(`${siteUrl}/facebook/hashtags/${pageName}`)
            .then(response => response.json())
            .then(data => {
                hashtagList.innerHTML = "";
                if (data.length > 0) {
                    data.forEach(hashtag => {
                        addHashtagToList(hashtag.idFacebook, hashtag.hashtag);
                    });
                } else {
                    hashtagList.innerHTML = `<li class='list-group-item text-muted' id='noHashtagMsg'>Aucun hashtag trouvé</li>`;
                }
            })
            .catch(error => console.error("Erreur lors du chargement des hashtags:", error));
    }

    // Ajoute un hashtag à la liste affichée
    function addHashtagToList(id, hashtag) {
        const noHashtagMsg = document.getElementById("noHashtagMsg");
        if (noHashtagMsg) {
            noHashtagMsg.remove();
        }
        const listItem = document.createElement("li");
        listItem.classList.add("list-group-item", "d-flex", "justify-content-between", "align-items-center");
        listItem.innerHTML = `
            ${hashtag}
            <button class="btn btn-danger btn-sm remove-hashtag" data-id="${id}">X</button>
        `;
        hashtagList.appendChild(listItem);
    }

    // Ajout d'un hashtag via AJAX
    addHashtagBtn.addEventListener("click", function () {
        const hashtag = hashtagInput.value.trim();
        const pageName = pageSelect.value;

        if (hashtag) {
            fetch(createUrl, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    hashtag: hashtag,
                    pageName: pageName
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        addHashtagToList(data.idFacebook, hashtag);
                        hashtagInput.value = "";
                    } else {
                        alert("Erreur lors de l'ajout du hashtag");
                    }
                })
                .catch(error => console.error("Erreur AJAX:", error));
        }
    });

    // Suppression d'un hashtag via AJAX
    hashtagList.addEventListener("click", function (event) {
        if (event.target.classList.contains("remove-hashtag")) {
            const id = event.target.getAttribute("data-id");

            if (!id) {
                console.error("ID non défini pour la suppression du hashtag.");
                return;
            }

            fetch(`${deleteUrl}/${id}`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({ id: id })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        event.target.closest("li").remove();
                        if (hashtagList.children.length === 0) {
                            hashtagList.innerHTML = "<li class='list-group-item text-muted'>Aucun hashtag trouvé</li>";
                        }
                    } else {
                        alert("Erreur lors de la suppression du hashtag : " + data.message);
                    }
                })
                .catch(error => console.error("Erreur AJAX:", error));
        }
    });

    // Chargement initial et changement de page
    loadHashtags(pageSelect.value);
    pageSelect.addEventListener("change", function () {
        loadHashtags(this.value);
    });

    // Ajout du préfixe "#" lors du focus sur l'input
    hashtagInput.addEventListener("focus", function () {
        if (!this.value.startsWith("#")) {
            this.value = "#";
        }
    });

    // -------------------------------
    // Gestion du Compte à Rebours pour le Token
    // -------------------------------

    function updateTokenCountdown(expirationDate) {
        const tokenExpiration = new Date(expirationDate);

        function calculateCountdown() {
            const now = new Date();
            const diff = tokenExpiration - now;

            if (diff <= 0) {
                tokenCountdown.innerHTML = "<span class='text-danger'>Token expiré</span>";
                return;
            }

            const days = Math.floor(diff / (1000 * 60 * 60 * 24));
            const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((diff % (1000 * 60)) / 1000);

            tokenCountdown.innerHTML = (days > 0)
                ? `${days}j ${hours}h ${minutes}m ${seconds}s`
                : `${hours}h ${minutes}m ${seconds}s`;
        }

        setInterval(calculateCountdown, 1000);
        calculateCountdown();
    }

    function fetchTokenExpirationDate() {
        fetch(expirationUrl)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateTokenCountdown(data.expiration_date);
                } else {
                    console.error(data.message);
                }
            })
            .catch(error => console.error("Erreur AJAX:", error));
    }

    fetchTokenExpirationDate();

    // Réinitialisation du token
    resetTokenBtn.addEventListener("click", function () {
        window.location.href = loginUrl;
    });

    // -------------------------------
    // Récupération des Données d'Email et d'Association
    // -------------------------------

    // Récupération de l'email de réception
    fetch(getEmailReceptionUrl)
        .then(response => response.json())
        .then(data => {
            if (data.emailContact) {
                // Pré-remplissage du champ input dans le formulaire
                const mailContactInput = document.getElementById("mailContact");
                if (mailContactInput) {
                    mailContactInput.value = data.emailContact;
                    mailContactInput.dispatchEvent(new Event("input")); // Déclenche un éventuel écouteur d'événements
                }

                // Mise à jour de tous les affichages de l'email
                document.querySelectorAll(".emailDisplay").forEach(el => {
                    el.textContent = data.emailContact;
                });
            }
        })
        .catch(error => console.error("Erreur AJAX:", error));


    // Récupération des données de l'association
    fetch(getAssociationDataUrl)
        .then(response => {
            if (!response.ok) {
                throw new Error("Erreur lors de la récupération des données.");
            }
            return response.json();
        })
        .then(data => {
            if (data.adresse) {
                // Mise à jour du champ input dans le formulaire
                const adresseInput = document.getElementById("adresse");
                if (adresseInput) {
                    adresseInput.value = data.adresse;
                    adresseInput.dispatchEvent(new Event("input"));
                }

                // Mise à jour de tous les éléments affichant l'adresse
                document.querySelectorAll(".adresseDisplay").forEach(el => {
                    el.textContent = data.adresse;
                });
            }
            if (data.latitude) {
                document.getElementById("latitude").value = data.latitude;
            }
            if (data.longitude) {
                document.getElementById("longitude").value = data.longitude;
            }
        })
        .catch(error => console.error("Erreur lors de la récupération des données de localisation :", error));

    // ----------------------------------------------
    // Gestion du bouton on/off fichierInscription
    // ----------------------------------------------
    fetch(getFichierInscriptionEtatUrl)
        .then(response => response.json())
        .then(data => {
            let switchInput = document.getElementById("switchFichierInscription");
            let switchLabel = document.getElementById("switchLabel");

            if (switchInput && switchLabel) {
                switchInput.checked = (data.etat == 1);
                switchLabel.textContent = (data.etat == 1) ? "Activé" : "Désactivé";
            }
        })
        .catch(error => console.error("Erreur lors de la récupération de l'état :", error));

    // Modification du switch avec AJAX
    document.getElementById("switchFichierInscription")?.addEventListener("change", function () {
        let etat = this.checked ? 1 : 0;
        let switchLabel = document.getElementById("switchLabel");

        if (switchLabel) {
            switchLabel.textContent = this.checked ? "Activé" : "Désactivé";
        }

        fetch(updateFichierInscriptionEtatUrl, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ etat: etat })
        }).catch(error => console.error("Erreur lors de la mise à jour :", error));
    });
});




