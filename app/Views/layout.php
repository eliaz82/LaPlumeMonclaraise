<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" type="image/png" href="/favicon.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="<?= base_url('css/main.css') ?>">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/flickity@2/dist/flickity.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">


    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <!-- Flickity CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flickity@2.2.2/dist/flickity.min.css">

    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css" rel="stylesheet">



</head>

<style>
</style>
<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= url_to('accueil') ?>">
            <img src="<?= base_url(getAssociationLogo()); ?>" alt="Logo" class="logo">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?= url_to('accueil') ?>">Accueil</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        L'Association
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="<?= url_to('FusionAssociation') ?>#equipe">L'Équipe</a></li>
                        <li><a class="dropdown-item" href="<?= url_to('FusionAssociation') ?>#histoire">L'Histoire</a>
                        </li>
                        <li><a class="dropdown-item"
                                href="<?= url_to('FusionAssociation') ?>#partenaire">Partenaires</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= url_to('actualite') ?>">Fait marquant</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= url_to('calendrier') ?>">Calendrier</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Vie du club
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="<?= url_to('evenement') ?>">Evenement</a></li>
                        <li><a class="dropdown-item" href="<?= url_to('albumsPhoto') ?>">Albums photo</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= url_to('fichierInscription') ?>">Inscription</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= url_to('contact') ?>">Contact</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Réglages
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal"
                                data-bs-target="#profilModal">Profil</a></li>
                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal"
                                data-bs-target="#settingsModal">Paramètres</a></li>
                        <li><a class="dropdown-item" href="#">Déconnexion</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
<!-- Modal Paramètres -->
<div class="modal fade" id="settingsModal" tabindex="-1" aria-labelledby="settingsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="settingsModalLabel"><i class="bi bi-gear"></i> Paramètres</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <!-- Onglets de navigation -->
                <ul class="nav nav-tabs" id="settingsTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="token-tab" data-bs-toggle="tab" data-bs-target="#token"
                            type="button" role="tab" aria-controls="token" aria-selected="true">
                            <i class="bi bi-key"></i> Token d'Accès
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="logo-tab" data-bs-toggle="tab" data-bs-target="#logo" type="button"
                            role="tab" aria-controls="logo" aria-selected="false">
                            <i class="bi bi-image"></i> Logo
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="hashtags-tab" data-bs-toggle="tab" data-bs-target="#hashtags"
                            type="button" role="tab" aria-controls="hashtags" aria-selected="false">
                            <i class="bi bi-hash"></i> Hashtags
                        </button>
                    </li>
                    <!-- Onglet Email de Réception -->
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="email-tab" data-bs-toggle="tab" data-bs-target="#email"
                            type="button" role="tab" aria-controls="email" aria-selected="false">
                            <i class="bi bi-envelope"></i> Email de Réception
                        </button>
                    </li>
                </ul>

                <!-- Contenu des onglets -->
                <div class="tab-content mt-3" id="settingsTabContent">
                    <!-- Gestion du Token -->
                    <div class="tab-pane fade show active" id="token" role="tabpanel" aria-labelledby="token-tab">
                        <h4><i class="bi bi-shield-lock"></i> Gestion du Token d'Accès</h4>
                        <div class="alert alert-info">
                            <strong>Temps restant avant expiration :</strong>
                            <span id="tokenCountdown">Calcul en cours...</span>
                        </div>
                        <button class="btn btn-warning mt-2" id="resetTokenBtn">
                            <i class="bi bi-arrow-clockwise"></i> Réinitialiser le Token
                        </button>
                    </div>
                    <!-- Gestion du Logo -->
                    <div class="tab-pane fade" id="logo" role="tabpanel" aria-labelledby="logo-tab">
                        <h4><i class="bi bi-image"></i> Gestion du Logo</h4>

                        <!-- Aperçu du logo -->
                        <div class="mb-3 text-center">
                            <img id="logoPreview" src="<?= base_url(getAssociationLogo()); ?>"
                                class="img-fluid rounded shadow" alt="Logo actuel" style="max-width: 200px;">
                        </div>

                        <!-- Formulaire d'upload -->
                        <form id="logoForm" method="post" action="<?= url_to('logoUpdate') ?>"
                            enctype="multipart/form-data">
                            <div class="mb-3">
                                <input type="file" class="form-control" id="logoUpload" name="logo" accept="image/*"
                                    onchange="previewImage(event, 'logoPreview')">
                            </div>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-upload"></i> Mettre à jour le Logo
                            </button>
                        </form>
                    </div>


                    <!-- Gestion des Hashtags -->
                    <div class="tab-pane fade" id="hashtags" role="tabpanel" aria-labelledby="hashtags-tab">
                        <h4><i class="bi bi-tags"></i> Gestion des Hashtags</h4>

                        <!-- Sélection de la page -->
                        <label for="pageSelect" class="form-label">Choisir une page :</label>
                        <select id="pageSelect" class="form-select mb-3">
                            <option value="evenement">Événement</option>
                            <option value="albumsphoto">Albums Photo</option>
                            <option value="faitmarquant">Fait Marquant</option>
                            <option value="calendrier">Calendrier</option>
                        </select>

                        <!-- Ajout de hashtag -->
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" id="hashtagInput" placeholder="Ex: #Sport"
                                value="#">
                            <button class="btn btn-primary" id="addHashtag">
                                <i class="bi bi-plus-lg"></i> Ajouter
                            </button>
                        </div>

                        <!-- Liste des hashtags -->
                        <ul class="list-group" id="hashtagList">
                            <?php if (isset($hashtags)): ?>
                                <?php foreach ($hashtags as $hashtag): ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <?= $hashtag['hashtag'] ?>
                                        <button class="btn btn-danger btn-sm remove-hashtag"
                                            data-id="<?= $hashtag['idFacebook'] ?>">
                                            <i class="bi bi-x-circle"></i>
                                        </button>
                                    </li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ul>
                    </div>
                    <!-- Contenu de l'onglet "Email de Réception" -->
                    <div class="tab-pane fade" id="email" role="tabpanel" aria-labelledby="email-tab">
                        <h4><i class="bi bi-envelope-at"></i> Modifier l'Email de Réception</h4>

                        <!-- Formulaire pour modifier l'email de contact -->
                        <form action="<?= route_to('contactUpdate'); ?>" method="post">
                            <div class="mb-3">
                                <label for="mailContact" class="form-label">E-mail de réception :</label>
                                <input type="email" id="mailContact" name="mailContact" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Modifier</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg"></i> Fermer
                </button>
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="profilModal" tabindex="-1" aria-labelledby="profilModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xxl custom-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="settingsModalLabel">Profil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <button class="edit-button" data-bs-toggle="modal" data-bs-target="#modalModifierContact"
                data-text="Modifier votre e-mail">
                <svg class="edit-svgIcon" viewBox="0 0 512 512">
                    <path
                        d="M410.3 231l11.3-11.3-33.9-33.9-62.1-62.1L291.7 89.8l-11.3 11.3-22.6 22.6L58.6 322.9c-10.4 10.4-18 23.3-22.2 37.4L1 480.7c-2.5 8.4-.2 17.5 6.1 23.7s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L387.7 253.7 410.3 231zM160 399.4l-9.1 22.7c-4 3.1-8.5 5.4-13.3 6.9L59.4 452l23-78.1c1.4-4.9 3.8-9.4 6.9-13.3l22.7-9.1v32c0 8.8 7.2 16 16 16h32zM362.7 18.7L348.3 33.2 325.7 55.8 314.3 67.1l33.9 33.9 62.1 62.1 33.9 33.9 11.3-11.3 22.6-22.6 14.5-14.5c25-25 25-65.5 0-90.5L453.3 18.7c-25-25-65.5-25-90.5 0zm-47.4 168l-144 144c-6.2 6.2-16.4 6.2-22.6 0s-6.2-16.4 0-22.6l144-144c6.2-6.2 16.4-6.2 22.6 0s6.2 16.4 0 22.6z">
                    </path>
                </svg>
            </button>



        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const pageSelect = document.getElementById("pageSelect");
        const hashtagList = document.getElementById("hashtagList");
        const addHashtagBtn = document.getElementById("addHashtag");
        const hashtagInput = document.getElementById("hashtagInput");
        const tokenCountdown = document.getElementById("tokenCountdown");


        // Fonction pour charger les hashtags d'une page
        function loadHashtags(pageName) {
            fetch(`<?= site_url('facebook/hashtags') ?>/${pageName}`)
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

        // Fonction pour ajouter un hashtag à la liste
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

        // Fonction pour ajouter un hashtag via AJAX
        addHashtagBtn.addEventListener("click", function() {
            const hashtag = hashtagInput.value.trim();
            const pageName = pageSelect.value;

            if (hashtag) {
                fetch("<?= site_url('facebook/create') ?>", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            'hashtag': hashtag,
                            'pageName': pageName
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
        document.getElementById("hashtagInput").addEventListener("focus", function() {
            if (!this.value.startsWith("#")) {
                this.value = "#";
            }
        });

        // Fonction pour supprimer un hashtag via AJAX
        hashtagList.addEventListener("click", function(event) {
            if (event.target.classList.contains("remove-hashtag")) {
                const id = event.target.getAttribute("data-id");

                if (!id) {
                    console.error("ID non défini pour la suppression du hashtag.");
                    return; // Si l'ID est manquant, ne fais pas la requête
                }

                fetch(`<?= site_url('facebook/delete') ?>/${id}`, {
                        method: "POST", // Utilisation uniquement de POST
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            id: id // Envoyer l'ID dans le corps de la requête
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            event.target.closest("li").remove(); // Supprimer l'élément de la liste
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
        // Charger les hashtags au changement de la page sélectionnée
        pageSelect.addEventListener("change", function() {
            loadHashtags(this.value);
        });

        // Charger les hashtags de la première page sélectionnée au démarrage
        loadHashtags(pageSelect.value);

    });

    // Gestion du compte à rebours pour l'expiration du token
    const tokenCountdown = document.getElementById("tokenCountdown");

    // Fonction pour récupérer la date d'expiration via AJAX
    function fetchTokenExpirationDate() {
        fetch("<?= site_url('facebook/expiration') ?>") // La route que tu as définie
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const tokenExpirationDate = data.expiration_date;
                    updateTokenCountdown(tokenExpirationDate);
                } else {
                    console.error(data.message);
                }
            })
            .catch(error => console.error("Erreur AJAX:", error));
    }

    // Fonction de mise à jour du compte à rebours
    function updateTokenCountdown(expirationDate) {
        const tokenExpiration = new Date(expirationDate); // Créer un objet Date à partir de la chaîne

        // Fonction de calcul du temps restant
        function calculateCountdown() {
            const now = new Date();
            const diff = tokenExpiration - now;

            if (diff <= 0) {
                tokenCountdown.innerHTML = "<span class='text-danger'>Token expiré</span>";
                return;
            }
            const days = Math.floor(diff / (1000 * 60 * 60 * 24)); // Calcul des jours restants
            const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)); // Calcul des heures
            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60)); // Calcul des minutes
            const seconds = Math.floor((diff % (1000 * 60)) / 1000); // Calcul des secondes


            if (days > 0) {
                tokenCountdown.innerHTML = `${days}j ${hours}h ${minutes}m ${seconds}s`;
            } else {
                tokenCountdown.innerHTML = `${hours}h ${minutes}m ${seconds}s`;
            }
        }

        // Mettre à jour le compte à rebours toutes les secondes
        setInterval(calculateCountdown, 1000);
        calculateCountdown();
    }

    // Charger la date d'expiration au chargement de la page
    fetchTokenExpirationDate();
    const resetTokenBtn = document.getElementById("resetTokenBtn");

    // Gestion du clic sur le bouton de réinitialisation du token
    resetTokenBtn.addEventListener("click", function() {
        // Rediriger vers la méthode login() de ton contrôleur Facebook
        window.location.href = "<?= site_url('facebook/login') ?>";
    });
    const emailReceptionInput = document.getElementById("mailContact");
    const idAssociationInput = document.getElementById("idAssociation");

    // Requête AJAX pour récupérer l'email de réception
    fetch('<?= route_to("getEmailReception") ?>')
        .then(response => response.json())
        .then(data => {
            if (data.emailContact) {
                emailReceptionInput.value = data.emailContact;
                idAssociationInput.value = data.idAssociation;
            }
        })
        .catch(error => console.error("Erreur AJAX:", error));
</script>

<style>
    .custom-modal {
        max-width: 75vw;
    }
</style>


<body>
    <!-- Messages de notification -->
    <?php if (session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session('success'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session('error'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (session('validation')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul>
                <?php foreach (session('validation') as $field => $error): ?>
                    <li><strong><?= esc($field) ?>:</strong> <?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?= $this->renderSection('contenu') ?>
    <div id="fb-root"></div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script async defer crossorigin="anonymous"
        src="https://connect.facebook.net/fr_FR/sdk.js#xfbml=1&version=v22.0&appId=603470049247384"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src="https://unpkg.com/flickity@2/dist/flickity.pkgd.min.js"></script>
    <script src="<?= base_url('js/main.js') ?>"></script>
    <script src="<?= base_url('js/facebook.js') ?>"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/locales/fr.js"></script>
</body>

<footer class="footer-section py-4" style="background-color:rgb(29, 34, 67); color: white;">
    <div class="footer-container container">
        <div class="footer-row row">
            <!-- Section À propos -->
            <div class="footer-about col-md-4">
                <h5>À propos de nous</h5>
                <p>
                    Bienvenue sur le site officiel de notre club ! Nous sommes engagés à
                    promouvoir nos activités et à rassembler nos membres dans un esprit
                    de convivialité et de partage.
                </p>
            </div>
            <!-- Liens rapides -->
            <div class="footer-links col-md-4">
                <h5>Liens rapides</h5>
                <ul class="footer-links-list list-unstyled">
                    <li><a href="<?= url_to('accueil') ?>" class="footer-link text-white">Accueil</a></li>
                    <li><a href="<?= url_to('FusionAssociation') ?>#histoire" class="footer-link text-white">À
                            propos</a></li>
                    <li><a href="<?= url_to('evenement') ?>" class="footer-link text-white">Événements</a></li>
                    <li><a href="<?= url_to('contact') ?>" class="footer-link text-white">Contact</a></li>
                </ul>
            </div>
            <!-- Contact -->
            <div class="footer-contact col-md-4">
                <h5>Contact</h5>
                <p>
                    <i class="bi bi-geo-alt"></i> Adresse : Esplanade du Lac, 82230 Monclar-de-Quercy<br>
                    <i class="bi bi-envelope"></i> Email : laplumemonclaraise.outlook.com<br>
                    <i class="bi bi-telephone"></i> Téléphone : 07 82 17 69 70
                </p>
                <!-- Boutons réseaux sociaux -->
                <div class="footer-socials">
                    <!-- Bouton Facebook -->
                    <button class="social-btn">
                        <a href="https://www.facebook.com/profile.php?id=61562422197352" class="social-link">
                            <svg viewBox="0 0 16 16" fill="currentColor" class="social-icon" id="facebook">
                                <path
                                    d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z">
                                </path>
                            </svg>
                        </a>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <hr style="border-color: white;">
    <p class="footer-copyright text-center">© 2025 La plume Monclaraise. Tous droits réservés.</p>
</footer>