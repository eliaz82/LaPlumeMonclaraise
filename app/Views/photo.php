<?= $this->extend('layout') ?>
<?= $this->section('title') ?>
Photos
<?= $this->endSection() ?>
<?= $this->section('css') ?>
<!-- Tu peux ajouter ici tes styles CSS personnalisés -->
<link rel="stylesheet" type="text/css" href="<?= base_url('css/photo-gallery.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('contenu') ?>

<div class="container mt-4">
    <h1 class="text-center mb-4"><?= esc($album['nom'], 'html'); ?></h1>

    <div class="text-center mb-3">
        <a href="<?= esc(route_to('albumsPhoto'), 'attr'); ?>" class="btn btn-secondary">Retour aux albums</a>
    </div>
    <?php if (auth()->loggedIn()): ?>
        <div class="text-center mb-4">
            <button id="bouton-ajouter-photo" class="btn btn-primary btn-lg shadow-sm" data-bs-toggle="modal"
                data-bs-target="#modalAjouter">
                <i class="fa fa-plus me-2"></i> Ajouter des Photos
            </button>
        </div>

        <div class="modal fade" id="modalAjouter" tabindex="-1" aria-labelledby="modalAjouterLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form method="post" action="<?= esc(route_to('createPhoto', $album['idAlbums']), 'attr'); ?>"
                        enctype="multipart/form-data">
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
    <?php endif; ?>

    <div class="photo-gallery">
        <?php foreach ($photos as $photo):
            // Détermine l'URL de la photo
            $photoUrl = (filter_var($photo['photo'], FILTER_VALIDATE_URL)) ? $photo['photo'] : base_url($photo['photo']);
            ?>
            <div class="photo-item">
                <!-- Image cliquable pour zoom -->
                <img src="<?= esc($photoUrl, 'attr'); ?>" alt="Photo de l'album" class="photo-img"
                    onclick="zoomImage('<?= esc($photoUrl, 'js'); ?>')">

                <!-- Formulaire de suppression sous l'image -->
                <?php if (auth()->loggedIn()): ?>
                    <div class="photo-actions text-center">
                        <form action="<?= esc(route_to('photoDelete', $album['idAlbums']), 'attr'); ?>" method="post">
                            <?= csrf_field() ?>
                            <input type="hidden" name="idPhoto" value="<?= esc($photo['idPhoto'], 'attr'); ?>">
                            <input type="hidden" name="idAlbums" value="<?= esc($album['idAlbums'], 'attr'); ?>">
                            <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('Supprimer cette photo ?');">
                                Supprimer
                            </button>
                        </form>
                    </div>
                <?php endif; ?>

            </div>
        <?php endforeach; ?>
    </div>

    <!-- Conteneur pour l'image agrandie (mode zoom) -->
    <div id="zoom-container" class="zoom-container">
        <span id="close-zoom" class="close" onclick="closeZoom()">X</span>
        <div class="zoom-controls">
            <button id="zoom-in" onclick="zoomIn()"><i class="fas fa-search-plus"></i></button>
            <button id="zoom-out" onclick="zoomOut()"><i class="fas fa-search-minus"></i></button>
        </div>
        <img id="zoomed-image" class="zoomed-image" loading="lazy" />
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Tes scripts personnalisés ici -->
<?= $this->endSection() ?>