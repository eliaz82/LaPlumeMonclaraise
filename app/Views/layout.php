<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" type="image/png" href="/favicon.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" type="text/css" href="<?= base_url('css/main.css') ?>">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <?= $this->renderSection('css') ?>
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
                        <li><a class="dropdown-item" href="<?= url_to('FusionAssociation') ?>#equipe">Bureau</a></li>

                        <li><a class="dropdown-item"
                                href="<?= url_to('FusionAssociation') ?>#partenaire">Partenaires</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= url_to('actualite') ?>">Fait marquants</a>
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
                        <li><a class="dropdown-item" href="<?= url_to('evenement') ?>">Événements</a></li>
                        <li><a class="dropdown-item" href="<?= url_to('albumsPhoto') ?>">Albums photos</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= url_to('contact') ?>">Contact</a>
                </li>
                <?php if (auth()->loggedIn()): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
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
                            <li><a class="dropdown-item" href="<?= url_to('logout') ?>">
                                    <i class="fas fa-sign-out-alt"></i> Déconnexion
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>

            </ul>
        </div>
    </div>
</nav>
<?php if (auth()->loggedIn()): ?>
    <!-- Modal Paramètres -->
    <div class="modal fade" id="settingsModal" tabindex="-1" aria-labelledby="settingsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!-- En-tête de la modal -->
                <div class="modal-header">
                    <h5 class="modal-title" id="settingsModalLabel">
                        <i class="bi bi-gear"></i> Paramètres
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <!-- Corps de la modal -->
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
                            <button class="nav-link" id="hashtags-tab" data-bs-toggle="tab" data-bs-target="#hashtags"
                                type="button" role="tab" aria-controls="hashtags" aria-selected="false">
                                <i class="bi bi-hash"></i> Hashtags
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="fichier-inscription-tab" data-bs-toggle="tab"
                                data-bs-target="#fichier-inscription" type="button" role="tab"
                                aria-controls="fichier-inscription" aria-selected="false">
                                <i class="bi bi-file-earmark-text"></i> Fichier Inscription
                            </button>
                        </li>
                    </ul>

                    <!-- Contenu des onglets -->
                    <div class="tab-content mt-3" id="settingsTabContent">
                        <!-- Gestion du Token -->
                        <div class="tab-pane fade show active" id="token" role="tabpanel" aria-labelledby="token-tab">
                            <h4>
                                <i class="bi bi-shield-lock"></i> Gestion du Token d'Accès
                            </h4>
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
                            <h4>
                                <i class="bi bi-tags"></i> Gestion des Hashtags
                            </h4>
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
                        <!-- Gestion du Fichier d'Inscription -->
                        <div class="tab-pane fade" id="fichier-inscription" role="tabpanel"
                            aria-labelledby="fichier-inscription-tab">
                            <h4>
                                <i class="bi bi-file-earmark-check"></i> Fichier d'Inscription
                            </h4>
                            <!-- Switch ON/OFF pour activer/désactiver l'affichage du fichier d'inscription -->
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="switchFichierInscription">
                                <label class="form-check-label" for="switchFichierInscription" id="switchLabel">
                                    Chargement...
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Footer de la modal -->
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
                        <!-- Nouvel onglet Téléphone -->
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="telephone-tab" data-bs-toggle="tab" data-bs-target="#telephone"
                                type="button" role="tab" aria-controls="telephone" aria-selected="false">
                                <i class="bi bi-telephone"></i> Téléphone
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
                            <div class="mb-3 d-flex justify-content-center align-items-center text-center">
                                <img id="logoPreview" src="<?= base_url(getAssociationLogo()); ?>" alt="Logo actuel"
                                    style="max-width: 80%; max-height: 200px; border-radius: 8px; object-fit: cover; padding: 5px;">
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

                        <!-- Nouvel Onglet Téléphone -->
                        <div class="tab-pane fade" id="telephone" role="tabpanel" aria-labelledby="telephone-tab">
                            <h5 class="text-primary"><i class="bi bi-telephone"></i> Modifier le numéro de téléphone</h5>
                            <form action="<?= route_to('tel'); ?>" method="post">
                                <?= csrf_field() ?>
                                <div class="mb-3">
                                    <label for="telephoneInput" class="form-label">Numéro de téléphone :</label>
                                    <input type="tel" id="telephoneInput" name="telephone" class="form-control" required>
                                </div>
                                <!-- Champ caché pour l'id de l'association (ici, on suppose l'id 1, à adapter si besoin) -->
                                <input type="hidden" id="idAssociationTelephone" name="idAssociation" value="1">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Modifier le numéro
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

    <div id="config-data" data-site-url="<?= site_url() ?>" data-create-url="<?= site_url('facebook/create') ?>"
        data-delete-url="<?= site_url('facebook/delete') ?>" data-expiration-url="<?= site_url('facebook/expiration') ?>"
        data-login-url="<?= site_url('facebook/login') ?>" data-email-reception-url="<?= route_to('getEmailReception') ?>"
        data-association-data-url="<?= route_to('getAssociationData') ?>"
        data-fichier-inscription-etat-url="<?= base_url('getFichierInscriptionEtat') ?>"
        data-update-fichier-inscription-etat-url="<?= base_url('updateFichierInscriptionEtat') ?>">
    </div>
<?php endif; ?>



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
    <?php if (auth()->loggedIn()): ?>
        <?= script_tag('js/menuSettings.js') ?>
    <?php endif; ?>

    <?= $this->renderSection('scripts') ?>
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
                    <i class="bi bi-envelope"></i> Email : <span class="emailDisplay"><?= esc($emailContact ?? 'contact@club.fr'); ?></span><br>
                    <i class="bi bi-telephone"></i> Téléphone : <span class="telephoneDisplay"><?= esc($localisation['tel'] ?? 'Téléphone non défini'); ?></span>
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