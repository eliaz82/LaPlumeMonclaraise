<?= $this->extend('layout') ?>

<?= $this->section('css') ?>
<!-- Tu peux ajouter ici tes styles CSS personnalisés -->
<?= $this->endSection() ?>

<?= $this->section('contenu') ?>

<div class="container mt-4">
    <h1 class="text-center mb-4"><?= esc($album['nom'], 'html'); ?></h1>

    <div class="text-center mb-3">
        <a href="<?= esc(route_to('albumsPhoto'), 'attr'); ?>" class="btn btn-secondary">Retour aux albums</a>
    </div>

    <div class="text-center mb-4">
        <button id="bouton-ajouter-photo" class="btn btn-primary btn-lg shadow-sm" data-bs-toggle="modal"
            data-bs-target="#modalAjouter">
            <i class="fa fa-plus me-2"></i> Ajouter des Photos
        </button>
    </div>

    <div class="modal fade" id="modalAjouter" tabindex="-1" aria-labelledby="modalAjouterLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="post" action="<?= esc(route_to('createPhoto', $album['idAlbums']), 'attr'); ?>" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <div class="modal-header">
                        <h5 class="modal-title text-primary" id="modalAjouterLabel">Ajouter des Photos</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Champ caché pour envoyer l'ID de l'album -->
                        <input type="hidden" name="idAlbums" value="<?= esc($album['idAlbums'], 'attr'); ?>">

                        <!-- Champ pour les photos -->
                        <div class="mb-3">
                            <label for="photo" class="form-label">Photos</label>
                            <input type="file" class="form-control" id="photo" name="photo[]" accept="image/*"
                                onchange="previewImagesPhotos(event)" multiple required>
                        </div>

                        <!-- Prévisualisation des photos -->
                        <div class="mb-3 d-flex flex-wrap justify-content-center align-items-center text-center"
                            id="photoPreview">
                            <!-- Les images prévisualisées seront insérées ici -->
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
        <?php foreach ($photos as $photo): 
            // Détermine l'URL de la photo
            $photoUrl = (filter_var($photo['photo'], FILTER_VALIDATE_URL)) ? $photo['photo'] : base_url($photo['photo']);
        ?>
            <div class="photo-item">
                <!-- Image cliquable pour zoom -->
                <img src="<?= esc($photoUrl, 'attr'); ?>"
                    alt="Photo de l'album" class="photo-img"
                    onclick="zoomImage('<?= esc($photoUrl, 'js'); ?>')">

                <!-- Formulaire de suppression sous l'image -->
                <div class="photo-actions text-center">
                    <form action="<?= esc(route_to('photoDelete', $album['idAlbums']), 'attr'); ?>" method="post">
                        <?= csrf_field() ?>
                        <input type="hidden" name="idPhoto" value="<?= esc($photo['idPhoto'], 'attr'); ?>">
                        <input type="hidden" name="idAlbums" value="<?= esc($album['idAlbums'], 'attr'); ?>">
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Supprimer cette photo ?');">
                            Supprimer
                        </button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <style>
        .photo-gallery {
            width: 100vw;
            position: relative;
            left: 50%;
            transform: translateX(-50%);
            padding: 20px;
            column-count: 3;
            column-gap: 15px;
            box-sizing: border-box;
            background: rgb(240, 240, 240);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        /* --- Chaque photo s’adapte naturellement en hauteur --- */
        .photo-item {
            display: inline-block;
            width: 100%;
            margin-bottom: 15px;
            position: relative;
            transition: transform 0.2s ease, filter 0.2s ease;
        }
        .photo-item:hover {
            transform: scale(1.03);
            filter: brightness(0.85);
        }
        .photo-img {
            width: 100%;
            border-radius: 5px;
            display: block;
        }
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
        .zoomed-image {
            max-width: 85%;
            max-height: 85%;
            border-radius: 5px;
            animation: zoomIn 0.3s ease-in-out;
        }
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
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes zoomIn {
            from { transform: scale(0.8); }
            to { transform: scale(1); }
        }
        @media (max-width: 1200px) {
            .photo-gallery { column-count: 2; }
        }
        @media (max-width: 768px) {
            .photo-gallery { column-count: 1; }
        }
    </style>

    <!-- Conteneur pour l'image agrandie (mode zoom) -->
    <div id="zoom-container" class="zoom-container">
        <span id="close-zoom" class="close" onclick="closeZoom()">X</span>
        <img id="zoomed-image" class="zoomed-image" />
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Tes scripts personnalisés ici -->
<?= $this->endSection() ?>
