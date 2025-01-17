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
<!-- Bouton et formulaire d'ajout -->
<div class="container mb-4">
    <div class="text-center">
        <button id="bouton-ajouter" class="btn btn-primary mb-3" style="font-size: 1.25rem; padding: 15px 30px;">
            <i class="fa fa-plus" aria-hidden="true"></i> Ajouter un partenaire
        </button>
    </div>
    <form id="formulaire" class="card mb-3 shadow" style="display:none;" method="post"
        action="<?= url_to('partenairesSubmit') ?>" enctype="multipart/form-data">
        <div class="card-body">
            <h5 class="card-title">Formulaire d'ajout partenaire</h5>
            <div class="mb-3">
                <label for="logo" class="form-label">Logo</label>
                <input type="file" class="form-control" id="logo" name="logo" accept="image/*"
                    onchange="previewImage(event, 'logoPreview')">
            </div>
            <img id="logoPreview" src="#" alt="Prévisualisation" class="img-fluid mb-3"
                style="max-width: 200px; height: auto; display: none;">

            <div class="mb-3">
                <label for="info" class="form-label">Informations de texte</label>
                <textarea class="form-control" id="info" name="info" rows="3"></textarea>
            </div>
            <div class="mb-3">
                <label for="lien" class="form-label">Lien</label>
                <input type="url" class="form-control" id="lien" name="lien" placeholder="https://example.com">
            </div>
            <button type="submit" class="btn btn-primary w-100">Soumettre</button>
        </div>
    </form>
</div>

<!-- Modal pour le formulaire de modification -->
<div class="modal fade" id="modalModifier" tabindex="-1" aria-labelledby="modalModifierLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formulaire-modifier" method="post" action="<?= url_to('partenairesUpdate') ?>"
                enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalModifierLabel">Modifier un partenaire</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="modifier-id" name="idPartenaire">
                    <div class="mb-3">
                        <label for="modifier-logo" class="form-label">Logo</label>
                        <img id="modifierLogoPreview" src="#" alt="Logo actuel" class="img-fluid"
                            style="max-width: 200px; height: auto; display: none;">
                        <input type="file" class="form-control" id="modifier-logo" name="logo" accept="image/*"
                            onchange="previewImage(event, 'modifierLogoPreview')">
                    </div>
                    <div class="mb-3">
                        <label for="modifier-info" class="form-label">Informations de texte</label>
                        <textarea class="form-control" id="modifier-info" name="info" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="modifier-lien" class="form-label">Lien</label>
                        <input type="url" class="form-control" id="modifier-lien" name="lien"
                            placeholder="https://example.com">
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

<!-- Liste des partenaires -->
<div class="container my-5">
    <h2 class="text-center mb-4">Nos partenaires</h2> <!-- Ajout du titre ici -->
    <div class="row justify-content-center gy-4">
        <?php foreach ($partenaire as $p): ?>
            <div class="col-lg-4 col-md-6">
                <div class="card shadow-lg border-0 text-dark h-100">
                    <?php if (!empty($p['lien'])): ?>
                        <a href="<?= $p['lien'] ?>" target="_blank" class="text-decoration-none text-dark">
                        <?php else: ?>
                            <div class="text-dark">
                            <?php endif; ?>
                            <div class="d-flex justify-content-center mt-4">
                                <img src="<?= base_url($p['logo']) ?>" class="card-img-top img-fluid rounded-circle shadow"
                                    alt="<?= $p['info'] ?>" style="height: 200px; width: 200px; object-fit: cover;">
                            </div>
                            <div class="card-body d-flex flex-column justify-content-center text-center">
                                <h5 class="card-title fw-bold"><?= $p['info'] ?></h5>
                            </div>

                            <?php if (!empty($p['lien'])): ?>
                        </a>
                    <?php else: ?>
                    </div>
                <?php endif; ?>
                <div class="card-footer text-center">
                    <!-- Bouton de modification -->
                    <button class="btn btn-secondary bouton-modifier" data-id="<?= $p['idPartenaire'] ?>"
                        data-info="<?= htmlspecialchars($p['info'], ENT_QUOTES) ?>"
                        data-lien="<?= htmlspecialchars($p['lien'], ENT_QUOTES) ?>" data-logo="<?= base_url($p['logo']) ?>"
                        data-bs-toggle="modal" data-bs-target="#modalModifier">
                        Modifier
                    </button>
                    <!-- Bouton de suppression -->
                    <form action="<?= route_to('partenairesDelete') ?>" method="post" style="display:inline;">
                        <input type="hidden" name="idPartenaire" value="<?= $p['idPartenaire'] ?>">
                        <button type="submit" class="btn btn-danger"
                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce partenaire ?');">
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
