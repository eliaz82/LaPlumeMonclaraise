<?= $this->extend('layout') ?>
<?= $this->section('title') ?>
albums photos
<?= $this->endSection() ?>
<?= $this->section('css') ?>
<link rel="stylesheet" type="text/css" href="<?= base_url('css/photo-gallery.css') ?>">
<link rel="stylesheet" type="text/css" href="<?= base_url('css/responsive.css') ?>">

<?= $this->endSection() ?>

<?= $this->section('contenu') ?>

<?php if (auth()->loggedIn()): ?>

    <div class="text-center mb-4">
        <button id="bouton-ajouter-partenaire" class="btn btn-primary btn-lg shadow-sm" data-bs-toggle="modal"
            data-bs-target="#modalAjouter">
            <i class="fa fa-plus me-2"></i> Ajouter un Album photo
        </button>
    </div>
    <div class="modal fade" id="modalAjouter" tabindex="-1" aria-labelledby="modalAjouterLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="formulaire-ajouter" method="post" action="<?= url_to('createAlbumsPhoto') ?>"
                    enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <div class="modal-header">
                        <h5 class="modal-title text-primary" id="modalAjouterLabel">Ajouter un Album photo</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Champ pour la dateAlbums -->
                        <div class="mb-3">
                            <label for="dateAlbums" class="form-label">Date de l'album</label>
                            <input type="date" value="<?= date('Y-m-d'); ?>" class="form-control" id="dateAlbums"
                                name="dateAlbums" required>
                        </div>

                        <!-- Champ pour le nom -->
                        <div class="mb-3">
                            <label for="nom" class="form-label">Nom de l'album</label>
                            <input type="text" class="form-control" id="nom" name="nom" required>
                        </div>

                        <!-- Champ pour le logo (photo) -->
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
    <!-- Modal de modification pour les albums photos -->
    <div class="modal fade" id="modalModifierAlbum" tabindex="-1" aria-labelledby="modalModifierAlbumLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="formulaire-modifier-album" method="post" action="<?= url_to('updateAlbumsPhoto') ?>"
                    enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <div class="modal-header">
                        <h5 class="modal-title text-primary" id="modalModifierAlbumLabel">Modifier un Album Photo</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="modifier-id-album" name="idAlbums">
                        <div class="mb-3">
                            <label for="modifier-date-album" class="form-label">Date de l'album</label>
                            <input type="date" class="form-control" id="modifier-date-album" name="dateAlbums" required>
                        </div>
                        <div class="mb-3">
                            <label for="modifier-nom-album" class="form-label">Nom de l'album</label>
                            <input type="text" class="form-control" id="modifier-nom-album" name="nom" required>
                        </div>
                        <div class="mb-3">
                            <label for="modifier-photo-album" class="form-label">Photo</label>
                            <input type="file" class="form-control" id="modifier-photo-album" name="photo" accept="image/*"
                                onchange="previewImage(event, 'modifierPhotoPreviewAlbum')">
                        </div>
                        <div class="mb-3 d-flex justify-content-center align-items-center text-center">
                            <img id="modifierPhotoPreviewAlbum" src="#" alt="Prévisualisation"
                                style="max-width: 80%; max-height: 200px; display: none; border-radius: 8px; object-fit: cover; padding: 5px;">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Mettre à jour</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>

<form method="get" action="<?= site_url('albums-photo') ?>" class="filter-form mb-3">
    <div class="d-flex align-items-center justify-content-start">
        <div class="dropdown">
            <button class="btn btn-outline-primary dropdown-toggle" type="button" id="triDropdown"
                data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-sort-down me-2"></i> Trier les albums
            </button>
            <ul class="dropdown-menu" aria-labelledby="triDropdown">
                <li>
                    <a class="dropdown-item <?= (isset($_GET['tri']) && $_GET['tri'] == 'desc') ? 'active' : '' ?>"
                        href="<?= site_url('albums-photo?tri=desc') ?>">
                        <i class="bi bi-sort-down me-2"></i> Du plus récent au plus ancien
                    </a>
                </li>
                <li>
                    <a class="dropdown-item <?= (isset($_GET['tri']) && $_GET['tri'] == 'asc') ? 'active' : '' ?>"
                        href="<?= site_url('albums-photo?tri=asc') ?>">
                        <i class="bi bi-sort-up me-2"></i> Du plus ancien au plus récent
                    </a>
                </li>
            </ul>
        </div>
    </div>
</form>
<?php if (auth()->loggedIn()): ?>
<button id="refreshButton"
        data-refresh-url="<?= esc(site_url('facebook/refresh'), 'attr'); ?>"
        class="btn btn-light">
    <i class="bi bi-arrow-clockwise" style="color: #007bff;"></i> Rafraîchir
</button>
<?php endif; ?>


<div class="container mt-4">
    <?php if (empty($albumsPhotos)): ?>
        <div class="col-12 text-center">
            <p>Aucun album à afficher</p>
        </div>
    <?php else: ?>
        <!-- Grille des albums -->
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php foreach ($albumsPhotos as $album): ?>
                <div class="col">
                    <div class="custom-image-container position-relative">
                        <!-- Image de l'album -->
                        <?php
                        $imageSrc = (filter_var($album['photo'], FILTER_VALIDATE_URL)) ? $album['photo'] : base_url($album['photo']);
                        ?>
                        <img src="<?= esc($imageSrc) ?>" class="img-fluid rounded album-photo" alt="Photo de l'album">

                        <!-- Overlay au survol -->
                        <div class="custom-overlay d-flex justify-content-center align-items-center">
                            <?php if (filter_var($album['photo'], FILTER_VALIDATE_URL)): ?>
                                <div class="facebook-overlay-icon">
                                    <svg viewBox="0 0 16 16" fill="currentColor" class="facebook-icon">
                                        <path
                                            d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z">
                                        </path>
                                    </svg>
                                </div>
                            <?php endif; ?>
                            <div class="overlay-text text-center">
                                <h5 class="text-white fw-bold"><?= esc($album['nom']) ?></h5>
                                <p class="text-white"><?= (new DateTime($album['dateAlbums']))->format('d/m/Y') ?></p>
                                <div class="mt-3">
                                    <a href="<?= site_url('albums-photos/' . esc($album['idAlbums'])) ?>"
                                        class="btn btn-primary btn-sm">Voir Album</a>
                                    <?php if (auth()->loggedIn()): ?>
                                        <button class="btn btn-warning btn-sm me-2 bouton-modifier-album"
                                            data-idalbums="<?= esc($album['idAlbums']) ?>"
                                            data-date="<?= esc($album['dateAlbums']) ?>" data-nom="<?= esc($album['nom']) ?>"
                                            data-photo="<?= esc(base_url($album['photo'])) ?>" data-bs-toggle="modal"
                                            data-facebook="<?= filter_var($album['photo'], FILTER_VALIDATE_URL) ? '1' : '0' ?>"
                                            data-bs-target="#modalModifierAlbum">
                                            Modifier
                                        </button>

                                        <?php
                                        $isFacebookAlbum = filter_var($album['photo'], FILTER_VALIDATE_URL);
                                        ?>

                                        <form action="<?= url_to('albumsPhotoDelete') ?>" method="post" class="d-inline"
                                            onsubmit="return confirmerSuppression('<?= esc($album['postFacebookUrl']) ?>');">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="idAlbums" value="<?= esc($album['idAlbums']) ?>">
                                            <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
        </div>
</div>


<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<?= script_tag('js/form-modifications.js') ?>
<?= script_tag('js/albumsConfirm.js') ?>
<?= $this->endSection() ?>