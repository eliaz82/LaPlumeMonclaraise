<?= $this->extend('layout') ?>
<?= $this->section('contenu') ?>

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
                    <div class="text-center mb-3">
                        <img id="photoPreview" src="#" alt="Prévisualisation" class="img-fluid rounded-circle shadow"
                            style="max-width: 150px; display: none;">
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



<div class="container mt-4">
    <h1 class="text-center mb-4">Albums Photos</h1>

    <!-- Grille des albums -->
    <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4">
        <?php foreach ($albumsPhotos as $album): ?>
            <div class="col">
                <div class="card h-100">
                    <!-- Photo de l'album -->
                    <img src="<?= base_url($album['photo']) ?>" class="card-img-top" alt="Photo de l'album"
                        style="object-fit: cover; height: 200px;">
                    <div class="card-body">
                        <h5 class="card-title"><?= $album['nom'] ?></h5>
                        <p class="card-text">
                            Date de l'album : <?= $album['dateAlbums'] ?>
                        </p>
                    </div>
                    <div class="card-footer text-end">
                    <a href="<?= base_url('albums-photo/' . $album['dateAlbums']) ?>" class="btn btn-primary">Voir l'album</a>
                        <button class="btn btn-warning btn-sm me-2 bouton-modifier-album"
                            data-date="<?= $album['dateAlbums'] ?>" data-nom="<?= $album['nom'] ?>"
                            data-photo="<?= base_url($album['photo']) ?>" data-bs-toggle="modal"
                            data-bs-target="#modalModifierAlbum">
                            Modifier
                        </button>
                        <form action="<?= route_to('albumsPhotoDelete') ?>" method="post" class="d-inline">
                            <input type="hidden" name="dateAlbums" value="<?= $album['dateAlbums'] ?>">
                            <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce album photo ?');">Supprimer</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Modal de modification pour les albums photos -->
<div class="modal fade" id="modalModifierAlbum" tabindex="-1" aria-labelledby="modalModifierAlbumLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="formulaire-modifier-album" method="post" action="<?= url_to('updateAlbumsPhoto') ?>"
                enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title text-primary" id="modalModifierAlbumLabel">Modifier un Album Photo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Champ pour la dateAlbums (clé primaire) -->
                    <div class="mb-3">
                        <label for="modifier-date-album" class="form-label">Date de l'album</label>
                        <input type="date" class="form-control" id="modifier-date-album" name="dateAlbums" required>
                    </div>
                    <!-- Nom de l'album -->
                    <div class="mb-3">
                        <label for="modifier-nom-album" class="form-label">Nom de l'album</label>
                        <input type="text" class="form-control" id="modifier-nom-album" name="nom" required>
                    </div>
                    <!-- Champ pour la photo -->
                    <div class="mb-3">
                        <label for="modifier-photo-album" class="form-label">Photo</label>
                        <input type="file" class="form-control" id="modifier-photo-album" name="photo" accept="image/*"
                            onchange="previewImage(event, 'modifierPhotoPreviewAlbum')">
                        <div class="text-center mt-3">
                            <img id="modifierPhotoPreviewAlbum" src="#" alt="Prévisualisation"
                                class="img-fluid shadow rounded-circle" style="max-width: 150px; display: none;">
                        </div>
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







<?= $this->endSection() ?>