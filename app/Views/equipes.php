<?= $this->extend('layout') ?>
<?= $this->section('contenu') ?>
<div class="container">
    <!-- Success Message -->
    <?php if (session('success')): ?>
        <div class="alert alert-success">
            <?= session('success'); ?>
        </div>
    <?php endif; ?>


    <!-- Error Message -->
    <?php if (session('error')): ?>
        <div class="alert alert-danger">
            <?= session('error'); ?>
        </div>
    <?php endif; ?>

    <!-- Validation Errors -->
    <?php if (session('validation')): ?>
        <div class="alert alert-danger">
            <?= session('validation')->listErrors() ?>
        </div>
    <?php endif; ?>
<div class="container mb-4">
    <div class="text-center">
        <button id="bouton-ajouter" class="btn btn-primary mb-3" style="font-size: 1.25rem; padding: 15px 30px;">
            <i class="fa fa-plus" aria-hidden="true"></i> Ajouter un adhérent
        </button>
    </div>
    <form id="formulaire" class="card mb-3 shadow" style="display:none;" method="post"
        action="<?= url_to('equipeSubmit') ?>" enctype="multipart/form-data">
        <div class="card-body">
            <h5 class="card-title">Formulaire d'ajout d'un adhérent</h5>
            <div class="mb-3">
                <label for="nom" class="form-label">Nom</label>
                <input type="text" class="form-control" id="nom" name="nom" placeholder="Nom de l'adhérent" required>
            </div>
            <div class="mb-3">
                <label for="prenom" class="form-label">Prénom</label>
                <input type="text" class="form-control" id="prenom" name="prenom" placeholder="Prénom de l'adhérent"
                    required>
            </div>
            <div class="mb-3">
                <label for="grade" class="form-label">Grade</label>
                <input type="text" class="form-control" id="grade" name="grade" placeholder="Grade de l'adhérent">
            </div>
            <div class="mb-3">
                <label for="photo" class="form-label">Photo</label>
                <input type="file" class="form-control" id="photo" name="photo" accept="image/*"
                    onchange="previewImage(event, 'photoPreview')">
            </div>
            <img id="photoPreview" src="#" alt="Prévisualisation" class="img-fluid mb-3"
                style="max-width: 200px; height: auto; display: none;">
            <button type="submit" class="btn btn-primary w-100">Soumettre</button>
        </div>
    </form>
</div>

<!-- Modal pour le formulaire de modification -->
<div class="modal fade" id="modalModifier" tabindex="-1" aria-labelledby="modalModifierLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formulaire-modifier" method="post" action="<?= url_to('equipeUpdate') ?>"
                enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalModifierLabel">Modifier un adhérent</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="modifier-id" name="idAdherant">
                    <div class="mb-3">
                        <label for="modifier-nom" class="form-label">Nom</label>
                        <input type="text" class="form-control" id="modifier-nom" name="nom" required>
                    </div>
                    <div class="mb-3">
                        <label for="modifier-prenom" class="form-label">Prénom</label>
                        <input type="text" class="form-control" id="modifier-prenom" name="prenom" required>
                    </div>
                    <div class="mb-3">
                        <label for="modifier-grade" class="form-label">Grade</label>
                        <input type="text" class="form-control" id="modifier-grade" name="grade">
                    </div>
                    <div class="mb-3">
                        <label for="modifier-photo" class="form-label">Photo</label>
                        <img id="modifierPhotoPreview" src="#" alt="Photo actuelle" class="img-fluid"
                            style="max-width: 200px; height: auto; display: none;">
                        <input type="file" class="form-control" id="modifier-photo" name="photo" accept="image/*"
                            onchange="previewImage(event, 'modifierPhotoPreview')">
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

<!-- Liste des adhérents -->
<div class="container my-5">
    <h2 class="text-center mb-4">Nos adhérents</h2> <!-- Titre principal -->
    <div class="row justify-content-center gy-4">
        <?php foreach ($equipes as $e): ?>
            <div class="col-lg-4 col-md-6">
                <div class="card shadow-lg border-0 text-dark h-100">
                    <div class="d-flex justify-content-center mt-4">
                        <img src="<?= base_url($e['photo']) ?>" class="card-img-top img-fluid rounded-circle shadow"
                            alt="<?= $e['prenom'] . ' ' . $e['nom'] ?>"
                            style="height: 200px; width: 200px; object-fit: cover;">
                    </div>
                    <div class="card-body d-flex flex-column justify-content-center text-center">
                        <h5 class="card-title fw-bold"><?= $e['prenom'] . ' ' . $e['nom'] ?></h5>
                        <p class="card-text text-muted"><?= $e['grade'] ?></p>
                    </div>
                    <div class="card-footer text-center">
                        <!-- Bouton de modification -->
                        <button class="btn btn-secondary bouton-modifier" data-id="<?= $e['idAdherant'] ?>"
                        data-nom="<?= $e['nom'] ?>" data-prenom="<?= $e['prenom'] ?>"
                        data-grade="<?= $e['grade'] ?>" data-photo="<?= base_url($e['photo']) ?>"
                        data-bs-toggle="modal" data-bs-target="#modalModifier">Modifier</button>
                        <!-- Bouton de suppression -->
                        <form action="<?= route_to('equipeDelete') ?>" method="post" style="display:inline;">
                            <input type="hidden" name="idAdherant" value="<?= $e['idAdherant'] ?>">
                            <button type="submit" class="btn btn-danger"
                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet adhérent ?');">
                                Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?= $this->endSection() ?>