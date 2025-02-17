<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" type="image/png" href="/favicon.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->renderSection('title') ?: 'LaPlumeMonclaraise' ?></title>

    <link rel="stylesheet" type="text/css" href="<?= base_url('css/main.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?= base_url('css/buttons.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?= base_url('css/footer.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?= base_url('css/layout.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?= base_url('css/responsive.css') ?>">
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
                    <a class="nav-link" href="<?= url_to('actualite') ?>">Faits marquants</a>
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
        data-login-url="<?= site_url('facebook/login') ?>"
        data-fichier-inscription-etat-url="<?= base_url('getFichierInscriptionEtat') ?>"
        data-update-fichier-inscription-etat-url="<?= base_url('updateFichierInscriptionEtat') ?>">
    </div>
<?php endif; ?>

<div id="asso-data" data-association-data-url="<?= route_to('getAssociationData') ?>">
</div>

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
      <!--modal de Bienvenue-->
    <?php
    // Chemin vers le fichier qui va contenir la date de début du modal
    $modalFile = WRITEPATH . 'cache/modal_start_date.txt';
    $modalActive = true; // Par défaut, le modal est actif
    $testDuration = 14 * 24 * 60 * 60; // 2 semaines en secondes
    
    // Si le fichier n'existe pas, c'est la première visite qui déclenche la période
    if (!file_exists($modalFile)) {
        file_put_contents($modalFile, time());
    } else {
        // Récupérer la date de lancement depuis le fichier
        $startTime = (int) file_get_contents($modalFile);
        // Si le délai est dépassé, désactiver le modal globalement
        if (time() - $startTime > $testDuration) {
            $modalActive = false;
        }
    }
    ?>

    <?php if ($modalActive): ?>
        <!-- Modal Bootstrap de Bienvenue -->
        <!-- Modal Bootstrap de Bienvenue -->
        <div class="modal fade" id="welcomeModal" tabindex="-1" aria-labelledby="welcomeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-small">
                <div class="modal-content custom-modal-content">
                    <div class="modal-header custom-modal-header">
                        <h5 class="modal-title" id="welcomeModalLabel">Bienvenue sur La Plume Monclaraise !</h5>
                        <button type="button" class="btn-close custom-btn-close" data-bs-dismiss="modal"
                            aria-label="Fermer"></button>
                    </div>
                    <div class="modal-body custom-modal-body">
                        <div class="emoji mb-3">🎉</div>
                        <p class="modal-text">Bienvenue sur notre nouveau site !</p>
                        <p class="modal-description">Découvrez notre communauté de badminton et profitez d'une expérience
                            fluide et agréable.</p>
                        <p class="modal-cta">Explorez nos contenus et restez informé des dernières actualités ! 🏸</p>
                    </div>
                    <div class="modal-footer custom-modal-footer">
                        <button type="button" class="btn btn-primary custom-btn-primary" data-bs-dismiss="modal">Merci
                            !</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ajouter des styles CSS personnalisés -->
        <style>
            /* Taille réduite du modal */
            .modal-small .modal-dialog {
                max-width: 300px;
                /* Taille plus petite pour le modal */
                width: 90%;
            }

            /* Effet de zoom au survol du modal */
            .custom-modal-content {
                transition: transform 0.3s ease-out, box-shadow 0.3s ease;
                background: #E6F2FF;
                border: none;
                overflow: hidden;
                border-radius: 10px;
            }

            .modal.fade.show .custom-modal-content {
                transform: scale(1.05);
                box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            }

            /* Design du header */
            .custom-modal-header {
                background-color: #0056b3;
                color: white;
                padding: 0.8rem 1.5rem;
                border-bottom: none;
                border-radius: 10px 10px 0 0;
            }

            .custom-modal-header h5 {
                font-size: 1.6rem;
                /* Augmenté la taille du texte ici */
                font-weight: bold;
                letter-spacing: 0.5px;
            }

            /* Design du corps du modal */
            .custom-modal-body {
                padding: 1.2rem;
                font-size: 1.2rem;
                /* Augmenté la taille du texte ici */
                color: #333;
                text-align: center;
            }

            .custom-modal-body .emoji {
                font-size: 2.2rem;
                /* L'emoji est légèrement plus grand */
                color: #0056b3;
            }

            .custom-modal-body .modal-text {
                font-size: 1.3rem;
                /* Taille du texte augmentée */
                color: #333;
                line-height: 1.5;
            }

            .custom-modal-body .modal-description {
                font-size: 1.2rem;
                /* Taille du texte augmentée */
                color: #555;
                line-height: 1.4;
                margin-bottom: 1rem;
            }

            .custom-modal-body .modal-cta {
                font-size: 1.4rem;
                /* Taille du texte augmentée */
                color: #0056b3;
                font-weight: bold;
            }

            /* Design du footer */
            .custom-modal-footer {
                background-color: #E6F2FF;
                border-top: none;
                padding: 1rem 1.5rem;
            }

            /* Boutons */
            .custom-btn-close {
                font-size: 1.3rem;
                /* Augmenté la taille du bouton */
                opacity: 0.9;
            }

            .custom-btn-close:hover {
                opacity: 1;
            }

            .custom-btn-primary {
                background-color: #0069d9;
                border: none;
                font-weight: bold;
                font-size: 1.1rem;
                /* Taille du bouton augmentée */
                padding: 8px 25px;
                border-radius: 30px;
                transition: background-color 0.3s;
            }

            .custom-btn-primary:hover {
                background-color: #0056b3;
            }

            /* Design responsif */
            @media (max-width: 768px) {
                .modal-small .modal-dialog {
                    max-width: 80%;
                    width: 100%;
                }

                .custom-modal-body {
                    font-size: 1.1rem;
                }
            }

            @media (max-width: 480px) {
                .custom-modal-header h5 {
                    font-size: 1.4rem;
                }

                .custom-modal-body .modal-text {
                    font-size: 1.1rem;
                }

                .custom-modal-body .modal-description {
                    font-size: 1.1rem;
                }

                .custom-modal-body .modal-cta {
                    font-size: 1.3rem;
                }

                .custom-btn-primary {
                    font-size: 1rem;
                    padding: 8px 20px;
                }
            }
        </style>



        <script>
            document.addEventListener("DOMContentLoaded", function () {
                // Vérifie si l'utilisateur a déjà vu le modal durant sa session
                if (!localStorage.getItem("welcomeModalShown")) {
                    var modalWelcome = new bootstrap.Modal(document.getElementById("welcomeModal"));
                    modalWelcome.show();
                    localStorage.setItem("welcomeModalShown", "true");
                }
            });
        </script>
    <?php endif; ?>
          <!--Animation aprés connexion-->
    <?php
    // Vérifier si la session est bien chargée dans CodeIgniter
    $session = session();

    // Vérifier si l'animation a déjà été affichée
    $showAnimation = false;
    if (!$session->has('animation_shown') && auth()->loggedIn()) {
        $session->set('animation_shown', true);
        $showAnimation = true;
    }
    ?>

    <?php if ($showAnimation): ?>
        <div class="floating-images">
            <img src="<?= base_url('image/jul.jpeg') ?>" class="image">
            <img src="<?= base_url('image/plk.jpeg') ?>" class="image">
            <img src="<?= base_url('image/jul.jpeg') ?>" class="image">
            <img src="<?= base_url('image/plk.jpeg') ?>" class="image">
            <img src="<?= base_url('image/jul.jpeg') ?>" class="image">
            <img src="<?= base_url('image/plk.jpeg') ?>" class="image">
            <img src="<?= base_url('image/jul.jpeg') ?>" class="image">
            <img src="<?= base_url('image/plk.jpeg') ?>" class="image">
            <img src="<?= base_url('image/jul.jpeg') ?>" class="image">
            <img src="<?= base_url('image/plk.jpeg') ?>" class="image">
        </div>

        <style>
            .floating-images {
                position: fixed;
                top: 0;
                left: 0;
                width: 100vw;
                height: 100vh;
                z-index: 9999;
                pointer-events: none;
                overflow: hidden;
            }

            .image {
                width: 120px;
                height: 120px;
                position: absolute;
            }

            /* Animations */
            .image:nth-child(1) {
                left: 5%;
                top: -10%;
                animation: meteorite1 5s ease-out infinite;
            }

            .image:nth-child(2) {
                left: 20%;
                top: -5%;
                animation: meteorite2 5s ease-out infinite;
            }

            .image:nth-child(3) {
                left: 40%;
                top: -15%;
                animation: meteorite3 5s ease-out infinite;
            }

            .image:nth-child(4) {
                left: 60%;
                top: -8%;
                animation: meteorite4 5s ease-out infinite;
            }

            .image:nth-child(5) {
                left: 80%;
                top: -12%;
                animation: meteorite5 5s ease-out infinite;
            }

            .image:nth-child(6) {
                left: 5%;
                top: -10%;
                animation: meteorite1b 5s ease-out infinite;
            }

            .image:nth-child(7) {
                left: 20%;
                top: -5%;
                animation: meteorite2b 5s ease-out infinite;
            }

            .image:nth-child(8) {
                left: 40%;
                top: -15%;
                animation: meteorite3b 5s ease-out infinite;
            }

            .image:nth-child(9) {
                left: 60%;
                top: -8%;
                animation: meteorite4b 5s ease-out infinite;
            }

            .image:nth-child(10) {
                left: 80%;
                top: -12%;
                animation: meteorite5b 5s ease-out infinite;
            }

            @keyframes meteorite1 {
                0% {
                    transform: translate(0, 0) rotate(0deg);
                    opacity: 1;
                }

                100% {
                    transform: translate(120vw, 120vh) rotate(720deg);
                    opacity: 0.5;
                }
            }

            @keyframes meteorite2 {
                0% {
                    transform: translate(0, 0) rotate(0deg);
                    opacity: 1;
                }

                100% {
                    transform: translate(90vw, 140vh) rotate(540deg);
                    opacity: 0.5;
                }
            }

            @keyframes meteorite3 {
                0% {
                    transform: translate(0, 0) rotate(0deg);
                    opacity: 1;
                }

                100% {
                    transform: translate(100vw, 110vh) rotate(360deg);
                    opacity: 0.5;
                }
            }

            @keyframes meteorite4 {
                0% {
                    transform: translate(0, 0) rotate(0deg);
                    opacity: 1;
                }

                100% {
                    transform: translate(130vw, 130vh) rotate(900deg);
                    opacity: 0.5;
                }
            }

            @keyframes meteorite5 {
                0% {
                    transform: translate(0, 0) rotate(0deg);
                    opacity: 1;
                }

                100% {
                    transform: translate(80vw, 150vh) rotate(1080deg);
                    opacity: 0.5;
                }
            }

            @keyframes meteorite1b {
                0% {
                    transform: translate(0, 0) rotate(0deg);
                    opacity: 1;
                }

                100% {
                    transform: translate(120vw, 180vh) rotate(720deg);
                    opacity: 0.5;
                }
            }

            @keyframes meteorite2b {
                0% {
                    transform: translate(0, 0) rotate(0deg);
                    opacity: 1;
                }

                100% {
                    transform: translate(90vw, 200vh) rotate(540deg);
                    opacity: 0.5;
                }
            }

            @keyframes meteorite3b {
                0% {
                    transform: translate(0, 0) rotate(0deg);
                    opacity: 1;
                }

                100% {
                    transform: translate(100vw, 170vh) rotate(360deg);
                    opacity: 0.5;
                }
            }

            @keyframes meteorite4b {
                0% {
                    transform: translate(0, 0) rotate(0deg);
                    opacity: 1;
                }

                100% {
                    transform: translate(130vw, 190vh) rotate(900deg);
                    opacity: 0.5;
                }
            }

            @keyframes meteorite5b {
                0% {
                    transform: translate(0, 0) rotate(0deg);
                    opacity: 1;
                }

                100% {
                    transform: translate(80vw, 210vh) rotate(1080deg);
                    opacity: 0.5;
                }
            }
        </style>

        <script>
            document.addEventListener("DOMContentLoaded", function () {
                setTimeout(function () {
                    let floatingImages = document.querySelector('.floating-images');
                    if (floatingImages) {
                        floatingImages.style.display = 'none';
                    }
                }, 5000);
            });
        </script>
    <?php endif; ?>


    <?= $this->renderSection('contenu') ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <?= script_tag('js/main.js') ?>
    <?= script_tag('js/image-preview.js') ?>
    <?= script_tag('js/associationData.js') ?>
    <?php if (auth()->loggedIn()): ?>
        <?= script_tag('js/menuSettings.js') ?>
    <?php endif; ?>

    <?= $this->renderSection('scripts') ?>
</body>

<footer class="footer-section py-4" style="background-color: rgb(29, 34, 67); color: white;">
    <div class="footer-container container">
        <div class="footer-row row">
            <!-- Section À propos -->
            <div class="footer-about col-md-3">
                <h5>À propos de nous</h5>
                <p>
                    Bienvenue sur le site officiel de notre club ! Nous sommes engagés à
                    promouvoir nos activités et à rassembler nos membres dans un esprit
                    de convivialité et de partage.
                </p>
            </div>

            <!-- Liens rapides -->
            <div class="footer-links col-md-3">
                <h5>Liens rapides</h5>
                <ul class="footer-links-list list-unstyled">
                    <li><a href="<?= url_to('accueil') ?>" class="footer-link text-white">Accueil</a></li>
                    <li><a href="<?= url_to('FusionAssociation') ?>#histoire" class="footer-link text-white">À
                            propos</a></li>
                    <li><a href="<?= url_to('evenement') ?>" class="footer-link text-white">Événements</a></li>
                    <li><a href="<?= url_to('contact') ?>" class="footer-link text-white">Contact</a></li>
                </ul>
            </div>

            <!-- Liens légaux -->
            <div class="footer-links col-md-3">
                <h5>Liens légaux</h5>
                <ul class="footer-links-list list-unstyled">
                    <li><a href="<?= url_to('cgu') ?>" class="footer-link text-white">Conditions générales
                            d'utilisation</a></li>
                    <li><a href="<?= url_to('mentionsLegale') ?>#histoire" class="footer-link text-white">Mentions
                            légales</a></li>
                    <li><a href="<?= url_to('politiqueConfidentialite') ?>" class="footer-link text-white">Politique de
                            confidentialité</a></li>
                    <li><a href="<?= url_to('conformiteRgpd') ?>" class="footer-link text-white">Conformité RGPD</a>
                    </li>
                </ul>
            </div>

            <!-- Contact -->
            <div class="footer-contact col-md-3">
                <h5>Contact</h5>
                <p>
                    <i class="bi bi-geo-alt"></i> Adresse : <span
                        class="adresseDisplay"><?= esc($adresse ?? 'Adresse non définie'); ?></span><br>
                    <i class="bi bi-envelope"></i> Email : <span
                        class="emailDisplay"><?= esc($emailContact ?? 'Email non définie'); ?></span><br>
                    <i class="bi bi-telephone"></i> Téléphone : <span
                        class="telephoneDisplay"><?= esc($tel ?? 'Téléphone non défini'); ?></span>
                </p>

                <!-- Boutons réseaux sociaux -->
                <div class="footer-socials">
                    <a href="https://www.facebook.com/profile.php?id=61562422197352" class="social-link" target="_blank"
                        rel="noopener noreferrer">
                        <svg viewBox="0 0 16 16" fill="currentColor" class="social-icon" id="facebook">
                            <path
                                d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z">
                            </path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <hr style="border-color: white;">

    <p class="footer-copyright text-center">© 2025 La plume Monclaraise. Tous droits réservés.</p>

    <!-- Bouton retour en haut (version originale) -->
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