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
    const getFichierInscriptionEtatUrl = configData.dataset.fichierInscriptionEtatUrl;
    const updateFichierInscriptionEtatUrl = configData.dataset.updateFichierInscriptionEtatUrl;


    const csrfName = configData.dataset.csrfName;
    const csrfHash = configData.dataset.csrfHash;

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

        // Récupérer le token CSRF actuel à chaque clic
        const currentCsrfHash = configData.dataset.csrfHash;

        if (hashtag) {
            fetch(createUrl, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    [csrfName]: currentCsrfHash, // Utilisation de la valeur actuelle du token
                    hashtag: hashtag,
                    pageName: pageName
                })
            })
                .then(response => response.json())
                .then(data => {
                    // Mise à jour du token CSRF pour la prochaine requête
                    if (data.csrfHash) {
                        configData.dataset.csrfHash = data.csrfHash;
                    }
                    if (data.success) {
                        addHashtagToList(data.idFacebook, hashtag);
                        hashtagInput.value = "";
                    } else {
                        alert("Erreur lors de l'ajout du hashtag : " + data.message);
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

            // Récupération du token CSRF actuel
            const currentCsrfHash = configData.dataset.csrfHash;

            fetch(`${deleteUrl}/${id}`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    id: id,
                    [csrfName]: currentCsrfHash // Utilisation du token CSRF actuel
                })
            })
                .then(response => response.json())
                .then(data => {
                    // Mise à jour du token CSRF pour la prochaine requête
                    if (data.csrfHash) {
                        configData.dataset.csrfHash = data.csrfHash;
                    }
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

        // Récupération dynamique du token CSRF depuis l'élément de configuration
        const currentCsrfHash = configData.dataset.csrfHash;

        fetch(updateFichierInscriptionEtatUrl, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                etat: etat,
                [csrfName]: currentCsrfHash  // Ajout du token CSRF dans la requête
            })
        })
            .then(response => response.json())
            .then(data => {
                // Mise à jour du token CSRF pour la prochaine requête
                if (data.csrfHash) {
                    configData.dataset.csrfHash = data.csrfHash;
                }
            })
            .catch(error => console.error("Erreur lors de la mise à jour :", error));
    });

});




