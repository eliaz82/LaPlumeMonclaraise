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
                        <li><a class="dropdown-item" href="<?= url_to('FusionAssociation') ?>#histoire">Histoire</a>
                        </li>
                        <li><a class="dropdown-item" href="<?= url_to('FusionAssociation') ?>#equipe">Équipe</a></li>

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
                        <i class="fas fa-cog settings-icon"></i>
                    </a>

                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#profilModal">
                                <i class="fas fa-user"></i> Profil
                            </a>
                        </li>
                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#settingsModal">
                                <i class="fas fa-tools"></i> Paramètres
                            </a>
                        </li>
                        <li><a class="dropdown-item" href="#">
                                <i class="fas fa-sign-out-alt"></i> Déconnexion
                            </a>
                        </li>
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
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="hashtags-tab" data-bs-toggle="tab" data-bs-target="#hashtags"
                            type="button" role="tab" aria-controls="hashtags" aria-selected="false">
                            <i class="bi bi-hash"></i> Hashtags
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


                    <!-- Gestion des Hashtags -->
                    <div class="tab-pane fade" id="hashtags" role="tabpanel" aria-labelledby="hashtags-tab">
                        <h4><i class="bi bi-tags"></i> Gestion des Hashtags</h4>

                        <!-- Sélection de la page -->
                        <label for="pageSelect" class="form-label">Choisir une page :</label>
                        <select id="pageSelect" class="form-select mb-3">
                            <option value="evenementCalendrier">Événement+Calendrier</option>
                            <option value="albumsphoto">Albums Photo</option>
                            <option value="faitmarquant">Fait Marquant</option>
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



<!-- Modal Profil -->
<!-- Style personnalisé -->
<style>
    .custom-modal {
        max-width: 75vw;
    }

    .settings-icon {
        transition: transform 0.3s ease-in-out;
    }

    .nav-link:hover .settings-icon {
        transform: rotate(180deg);
    }
</style>

<!-- Modal Profil -->
<div class="modal fade" id="profilModal" tabindex="-1" aria-labelledby="profilModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg"> <!-- Utilisation de modal-lg pour un modal de taille réduite -->
        <div class="modal-content">
            <!-- En-tête du modal -->
            <div class="modal-header">
                <h5 class="modal-title" id="profilModalLabel">
                    <i class="bi bi-person"></i> Profil de l'association
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>

            <!-- Corps du modal avec navigation par onglets -->
            <div class="modal-body">
                <!-- Onglets de navigation -->
                <ul class="nav nav-tabs" id="profilTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="localisation-tab" data-bs-toggle="tab"
                            data-bs-target="#localisation" type="button" role="tab" aria-controls="localisation"
                            aria-selected="true">
                            <i class="bi bi-geo-alt"></i> Localisation
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="logo-tab" data-bs-toggle="tab" data-bs-target="#logo" type="button"
                            role="tab" aria-controls="logo" aria-selected="false">
                            <i class="bi bi-image"></i> Logo
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="email-tab" data-bs-toggle="tab" data-bs-target="#email"
                            type="button" role="tab" aria-controls="email" aria-selected="false">
                            <i class="bi bi-envelope"></i> Email
                        </button>
                    </li>
                </ul>

                <!-- Contenu des onglets -->
                <div class="tab-content mt-3" id="profilTabContent">
                    <!-- Onglet Localisation -->
                    <div class="tab-pane fade show active" id="localisation" role="tabpanel"
                        aria-labelledby="localisation-tab">
                        <h5 class="text-primary"><i class="bi bi-geo-alt"></i> Modifier la localisation</h5>
                        <form id="localisationForm" action="<?= route_to('localisation'); ?>" method="post">
                            <?= csrf_field() ?>
                            <div class="mb-3">
                                <label for="adresse" class="form-label">Adresse</label>
                                <input type="text" id="adresse" name="adresse" class="form-control" required>
                            </div>
                            <!-- Champs cachés pour latitude et longitude -->
                            <input type="hidden" id="latitude" name="latitude">
                            <input type="hidden" id="longitude" name="longitude">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Modifier la localisation
                            </button>
                        </form>
                    </div>

                    <!-- Onglet Logo -->
                    <div class="tab-pane fade" id="logo" role="tabpanel" aria-labelledby="logo-tab">
                        <h5 class="text-primary"><i class="bi bi-image"></i> Gestion du Logo</h5>
                        <!-- Aperçu du logo -->
                        <div class="mb-3 text-center">
                            <img id="logoPreview" src="<?= base_url(getAssociationLogo()); ?>"
                                class="img-fluid rounded shadow" alt="Logo actuel" style="max-width: 200px;">
                        </div>
                        <!-- Formulaire d'upload -->
                        <form id="logoForm" method="post" action="<?= url_to('logoUpdate') ?>"
                            enctype="multipart/form-data">
                            <?= csrf_field() ?>
                            <div class="mb-3">
                                <input type="file" class="form-control" id="logoUpload" name="logo" accept="image/*"
                                    onchange="previewImage(event, 'logoPreview')">
                            </div>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-upload"></i> Mettre à jour le Logo
                            </button>
                        </form>
                    </div>

                    <!-- Onglet Email -->
                    <div class="tab-pane fade" id="email" role="tabpanel" aria-labelledby="email-tab">
                        <h5 class="text-primary"><i class="bi bi-envelope-at"></i> Modifier l'Email de Réception</h5>
                        <form action="<?= route_to('contactUpdate'); ?>" method="post">
                            <?= csrf_field() ?>
                            <div class="mb-3">
                                <label for="mailContact" class="form-label">E-mail de réception :</label>
                                <input type="email" id="mailContact" name="mailContact" class="form-control" required>
                            </div>
                            <!-- Champ caché pour l'id de l'association -->
                            <input type="hidden" id="idAssociation" name="idAssociation">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Modifier
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Footer du modal -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg"></i> Fermer
                </button>
            </div>
        </div>
    </div>
</div>
<script>
    const siteUrl = "<?= site_url() ?>";
    const createUrl = "<?= site_url('facebook/create') ?>";
    const deleteUrl = "<?= site_url('facebook/delete') ?>";
    const expirationUrl = "<?= site_url('facebook/expiration') ?>";
    const loginUrl = "<?= site_url('facebook/login') ?>";
    const getEmailReceptionUrl = "<?= route_to('getEmailReception') ?>";
    const getAssociationDataUrl = "<?= route_to('getAssociationData') ?>";
</script>




<style>
    .custom-modal {
        max-width: 75vw;
    }

    body {
        cursor: url('/image/cursor.cur') 16 16, auto;

    }

    .button {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background-color: rgb(20, 20, 20);
        border: none;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0px 0px 0px 4px rgba(112, 177, 238, 0.25);
        cursor: pointer;
        transition-duration: 0.3s;
        overflow: hidden;
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 1000;
    }

    .svgIcon {
        width: 12px;
        transition-duration: 0.3s;
    }

    .svgIcon path {
        fill: white;
    }

    /* Nouvelle couleur et texte au survol */
    .button:hover {
        width: 140px;
        border-radius: 50px;
        transition-duration: 0.3s;
        background-color: rgba(129, 161, 208, 1);
        align-items: center;
    }

    .button:hover .svgIcon {
        transform: translateY(-200%);
        transition-duration: 0.3s;
    }

    /* Texte affiché au survol */
    .button::before {
        position: absolute;
        bottom: -20px;
        content: "Haut de page";
        color: white;
        font-size: 0px;
        transition-duration: 0.3s;
    }

    .button:hover::before {
        font-size: 13px;
        opacity: 1;
        bottom: unset;
        transition-duration: 0.3s;
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <?= script_tag('js/main.js') ?>
    <?= script_tag('js/image-preview.js') ?>
    <?= script_tag('js/facebook.js') ?>

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
                    <i class="bi bi-geo-alt"></i> Adresse : <span class="adresseDisplay"><?= esc($localisation['adresse'] ?? 'Adresse non définie'); ?></span><br>
                    <i class="bi bi-envelope"></i> Email : contact@club.fr<br>
                    <i class="bi bi-telephone"></i> Téléphone : 07 82 17 69 70
                </p>
                <!-- Boutons réseaux sociaux -->
                <div class="footer-socials">
                    <!-- Bouton Facebook -->
                    <button class="social-btn">
                        <a href="https://www.facebook.com/profile.php?id=61562422197352" class="social-link"
                            target="_blank" rel="noopener noreferrer">
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
    <a href="#top">
        <button class="button" id="back-to-top">
            <svg class="svgIcon" viewBox="0 0 384 512">
                <path
                    d="M214.6 41.4c-12.5-12.5-32.8-12.5-45.3 0l-160 160c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L160 141.2V448c0 17.7 14.3 32 32 32s32-14.3 32-32V141.2L329.4 246.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3l-160-160z">
                </path>
            </svg>
        </button>
    </a>




</footer>