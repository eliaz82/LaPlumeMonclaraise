<?= $this->extend('layout') ?>
<?= $this->section('contenu') ?>

<div class="container mt-4">
    <h1 class="text-center mb-4">Album : <?= esc($album['nom']) ?></h1>

    <div class="text-center mb-3">
        <a href="<?= route_to('albumsPhoto') ?>" class="btn btn-secondary">Retour aux albums</a>
    </div>

    <div class="text-center mb-4">
        <button id="bouton-ajouter-photo" class="btn btn-primary btn-lg shadow-sm" data-bs-toggle="modal"
            data-bs-target="#modalAjouter">
            <i class="fa fa-plus me-2"></i> Ajouter une Photo
        </button>
    </div>

    <div class="modal fade" id="modalAjouter" tabindex="-1" aria-labelledby="modalAjouterLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="post" action="<?= route_to('createPhoto', $album['idAlbums']) ?>"
                    enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title text-primary" id="modalAjouterLabel">Ajouter une Photo</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Champ caché pour envoyer l'ID de l'album -->
                        <input type="hidden" name="idAlbums" value="<?= $album['idAlbums'] ?>">

                        <!-- Champ pour la photo -->
                        <div class="mb-3">
                            <label for="photo" class="form-label">Photo</label>
                            <input type="file" class="form-control" id="photo" name="photo" accept="image/*"
                                onchange="previewImage(event, 'photoPreview')" required>
                        </div>
                        <!-- Prévisualisation de la photo -->
                        <div class="mb-3 d-flex justify-content-center align-items-center text-center">
                            <img id="photoPreview" src="#" alt="Prévisualisation"
                                style="max-width: 80%; max-height: 200px; display: none; border-radius: 8px; object-fit: cover; padding: 5px;">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Ajouter</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <div class="photo-gallery">
        <?php foreach ($photos as $photo): ?>
            <div class="photo-item">
                <!-- Image cliquable pour zoom -->
                <img src="<?= (filter_var($photo['photo'], FILTER_VALIDATE_URL)) ? $photo['photo'] : base_url($photo['photo']) ?>"
                    alt="Photo de l'album" class="photo-img"
                    onclick="zoomImage('<?= (filter_var($photo['photo'], FILTER_VALIDATE_URL)) ? $photo['photo'] : base_url($photo['photo']) ?>')">

                <!-- Formulaire de suppression sous l'image -->
                <div class="photo-actions text-center">
                    <form action="<?= route_to('photoDelete', $album['idAlbums']) ?>" method="post">
                        <input type="hidden" name="idPhoto" value="<?= $photo['idPhoto'] ?>">
                        <input type="hidden" name="idAlbums" value="<?= $album['idAlbums'] ?>">
                        <button type="submit" class="btn btn-danger btn-sm"
                            onclick="return confirm('Supprimer cette photo ?');">
                            Supprimer
                        </button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <style>
        /* --- Conteneur de la galerie avec effet mural d'art --- */
        .photo-gallery {
            column-count: 3;
            column-gap: 15px;
            padding: 20px;
            background: rgb(219, 219, 219);
            /* Gris clair avec blanc */
        }


        /* --- Chaque photo s’adapte naturellement en hauteur --- */
        .photo-item {
            display: inline-block;
            width: 100%;
            margin-bottom: 15px;
            /* Espacement naturel */
            position: relative;
            transition: transform 0.2s ease, filter 0.2s ease;
        }

        /* --- Effet artistique au survol --- */
        .photo-item:hover {
            transform: scale(1.03);
            filter: brightness(0.85);
        }

        /* --- Images avec tailles variées pour un effet dynamique --- */
        .photo-img {
            width: 100%;
            border-radius: 5px;
            /* Coins légèrement arrondis */
            display: block;
        }

        /* --- Apparition du bouton seulement au survol --- */
        .photo-actions {
            position: absolute;
            bottom: 10px;
            left: 50%;
            transform: translateX(-50%);
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
        }

        .photo-item:hover .photo-actions {
            opacity: 1;
        }

        /* --- Bouton de suppression moderne et discret --- */
        .photo-actions button {
            background: rgba(255, 69, 58, 0.9);
            border: none;
            color: #fff;
            padding: 8px 16px;
            border-radius: 50px;
            font-size: 0.9rem;
            font-weight: bold;
            text-transform: uppercase;
            transition: background 0.3s ease;
        }

        .photo-actions button:hover {
            background: #ff453a;
        }

        /* --- Zoom en mode galerie immersive --- */
        .zoom-container {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.9);
            justify-content: center;
            align-items: center;
            z-index: 1000;
            animation: fadeIn 0.3s ease-in-out;
        }

        /* --- Image zoomée en mode expo --- */
        .zoomed-image {
            max-width: 85%;
            max-height: 85%;
            border-radius: 5px;
            animation: zoomIn 0.3s ease-in-out;
        }

        /* --- Fermeture avec un effet subtil --- */
        #close-zoom {
            position: absolute;
            top: 15px;
            right: 20px;
            font-size: 40px;
            color: #fff;
            cursor: pointer;
            transition: opacity 0.3s ease;
        }

        #close-zoom:hover {
            opacity: 0.8;
        }

        /* --- Animations douces --- */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes zoomIn {
            from {
                transform: scale(0.8);
            }

            to {
                transform: scale(1);
            }
        }

        /* --- Responsive : Ajustement du nombre de colonnes selon la taille d’écran --- */
        @media (max-width: 1200px) {
            .photo-gallery {
                column-count: 2;
            }
        }

        @media (max-width: 768px) {
            .photo-gallery {
                column-count: 1;
            }
        }
    </style>

    <!-- Conteneur pour l'image agrandie (style simple) -->
    <div id="zoom-container" class="zoom-container">
        <span id="close-zoom" class="close" onclick="closeZoom()">X</span>
        <img id="zoomed-image" class="zoomed-image" />
    </div>


</div>

<?= $this->endSection() ?>