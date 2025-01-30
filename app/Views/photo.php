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
                <img src="<?= base_url($photo['photo']) ?>" alt="Photo de l'album" class="photo-img"
                    onclick="zoomImage('<?= base_url($photo['photo']) ?>')">

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




    <!-- Conteneur pour l'image agrandie (style simple) -->
    <div id="zoom-container" class="zoom-container">
        <span id="close-zoom" class="close" onclick="closeZoom()">X</span>
        <img id="zoomed-image" class="zoomed-image" />
    </div>


</div>

<?= $this->endSection() ?>