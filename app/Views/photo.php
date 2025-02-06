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
                        <div class="text-center mb-3">
                            <img id="photoPreview" src="#" alt="Prévisualisation"
                                class="img-fluid rounded-circle shadow" style="max-width: 150px; display: none;">
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
/* Galerie de photos */
.photo-gallery {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
    padding: 20px;
    margin-top: 40px;
    border-radius: 10px;
    background-color: #f5f5f5;
}

/* Élément de chaque photo */
.photo-item {
    position: relative;
    overflow: hidden;
    border-radius: 15px;
    background-color: #fff;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    cursor: pointer;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

/* Effet au survol de l'image */
.photo-item:hover {
    transform: scale(1.05);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
}

/* Image dans l'élément photo */
.photo-img {
    width: 100%;
    height: 100%;
    object-fit: cover; /* Remplissage de l'image sans déformation */
    border-radius: 15px;
    image-rendering: auto; /* Utilisation du meilleur algorithme pour afficher l'image */
}

/* Assurer que l'image garde une bonne qualité (éviter l'étirement) */
.photo-img {
    display: block;
    max-width: 100%;
    height: auto; /* Maintien du ratio de l'image */
    object-fit: cover; /* Garde la couverture de l'espace sans déformation */
}

/* Prévenir le flou pour les zooms importants */
.photo-img {
    image-rendering: -webkit-optimize-contrast; /* Meilleur rendu d'image sur Chrome */
    image-rendering: crisp-edges; /* Meilleur rendu d'image pour autres navigateurs */
}

/* Effet de zoom sur l'image au survol (éviter un zoom trop grand) */
.photo-img:hover {
    transform: scale(1.1);
}

/* Actions sous l'image */
.photo-actions {
    position: absolute;
    bottom: 15px;
    left: 50%;
    transform: translateX(-50%);
    opacity: 0;
    transition: opacity 0.3s ease-in-out;
}

.photo-item:hover .photo-actions {
    opacity: 1;
}

/* Bouton de suppression */
.photo-actions button {
    background-color: #3498db;
    border: none;
    color: #fff;
    padding: 8px 20px;
    border-radius: 25px;
    font-size: 0.95rem;
    font-weight: 600;
    text-transform: uppercase;
    transition: background-color 0.3s ease, transform 0.3s ease;
}

.photo-actions button:hover {
    background-color: #2980b9;
    transform: translateY(-3px);
}

/* Conteneur pour l'image agrandie */
.zoom-container {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.75);
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

/* Image agrandie avec haute qualité */
.zoomed-image {
    max-width: 90%;
    max-height: 90%;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
    transition: transform 0.3s ease-in-out;
}

/* Fermeture du zoom */
#close-zoom {
    position: absolute;
    top: 20px;
    right: 30px;
    font-size: 36px;
    color: #fff;
    font-weight: bold;
    cursor: pointer;
    opacity: 0.8;
    transition: opacity 0.3s ease;
}

#close-zoom:hover {
    opacity: 1;
}
</style>



    <!-- Conteneur pour l'image agrandie (style simple) -->
    <div id="zoom-container" class="zoom-container">
        <span id="close-zoom" class="close" onclick="closeZoom()">X</span>
        <img id="zoomed-image" class="zoomed-image" />
    </div>


</div>

<?= $this->endSection() ?>