<?= $this->extend('layout') ?>
<?= $this->section('contenu') ?>

<div>
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

    <!-- Titre principal -->
    <div class="text-center mb-4">
        <h1 class="display-5 fw-bold text-primary">Gestion des Adhérents</h1>
        <p class="text-muted">Ajoutez, modifiez ou supprimez les membres de votre association facilement.</p>
    </div>

    <!-- Bouton d'ajout -->
    <div class="text-center mb-4">
        <button id="bouton-ajouter" class="btn btn-primary btn-lg shadow-sm">
            <i class="fa fa-plus me-2"></i> Ajouter un adhérent
        </button>
    </div>

    <!-- Formulaire d'ajout -->
    <form id="formulaire" class="card shadow-lg border-0 mb-5 p-4" style="display:none;" method="post"
        action="<?= url_to('equipeSubmit') ?>" enctype="multipart/form-data">
        <h4 class="card-title text-center text-secondary mb-3">Formulaire d'ajout d'un adhérent</h4>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="nom" class="form-label">Nom</label>
                <input type="text" class="form-control" id="nom" name="nom" placeholder="Nom de l'adhérent" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="prenom" class="form-label">Prénom</label>
                <input type="text" class="form-control" id="prenom" name="prenom" placeholder="Prénom de l'adhérent"
                    required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="grade" class="form-label">Grade</label>
                <input type="text" class="form-control" id="grade" name="grade" placeholder="Grade de l'adhérent">
            </div>
            <div class="col-md-6 mb-3">
                <label for="photo" class="form-label">Photo</label>
                <input type="file" class="form-control" id="photo" name="photo" accept="image/*"
                    onchange="previewImage(event, 'photoPreview')">
            </div>
            <div class="col-12 text-center mb-3">
                <img id="photoPreview" src="#" alt="Prévisualisation" class="img-fluid shadow rounded-circle"
                    style="max-width: 150px; display: none;">
            </div>
        </div>
        <button type="submit" class="btn btn-success w-100">Soumettre</button>
    </form>

    <!-- Liste des adhérents -->
    <div class="row justify-content-center gy-4">
        <?php foreach ($equipes as $e): ?>
            <div class="col-lg-4 col-md-6">
                <div class="card shadow-lg border-0 h-100">
                    <div class="text-center mt-4">
                        <img src="<?= base_url($e['photo']) ?>" class="img-fluid rounded-circle shadow"
                            alt="<?= $e['prenom'] . ' ' . $e['nom'] ?>"
                            style="width: 150px; height: 150px; object-fit: cover;">
                    </div>
                    <div class="card-body text-center">
                        <h5 class="card-title fw-bold text-primary"><?= $e['prenom'] . ' ' . $e['nom'] ?></h5>
                        <span class="badge bg-secondary"><?= $e['grade'] ?></span>
                    </div>
                    <div class="card-footer text-center">
                        <button class="btn btn-warning btn-sm me-2 bouton-modifier" data-id="<?= $e['idAdherant'] ?>"
                            data-nom="<?= $e['nom'] ?>" data-prenom="<?= $e['prenom'] ?>" data-grade="<?= $e['grade'] ?>"
                            data-photo="<?= base_url($e['photo']) ?>" data-bs-toggle="modal"
                            data-bs-target="#modalModifier">Modifier</button>
                        <form action="<?= route_to('equipeDelete') ?>" method="post" class="d-inline">
                            <input type="hidden" name="idAdherant" value="<?= $e['idAdherant'] ?>">
                            <button type="submit" class="btn btn-danger btn-sm"
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

<!-- Modal de modification -->
<div class="modal fade" id="modalModifier" tabindex="-1" aria-labelledby="modalModifierLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="formulaire-modifier" method="post" action="<?= url_to('equipeUpdate') ?>"
                enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title text-primary" id="modalModifierLabel">Modifier un adhérent</h5>
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
                        <input type="file" class="form-control" id="modifier-photo" name="photo" accept="image/*"
                            onchange="previewImage(event, 'modifierPhotoPreview')">
                        <div class="text-center mt-3">
                            <img id="modifierPhotoPreview" src="#" alt="Prévisualisation"
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