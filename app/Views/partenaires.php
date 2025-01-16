<?= $this->extend('layout') ?>
<?= $this->section('contenu') ?>

<form id="formulaire" class="card mb-3" style="display:none;" method="post" action="<?= url_to('partenairesSubmit')?>" enctype="multipart/form-data">
    <div class="card-body">
        <h5 class="card-title">Formulaire d'ajout partenaire</h5>
        <div class="mb-3">
            <label for="logo" class="form-label">Logo</label>
            <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
        </div>
        <div class="mb-3">
            <label for="informations" class="form-label">Informations de texte</label>
            <textarea class="form-control" id="informations" name="informations" rows="3"></textarea>
        </div>

        <div class="mb-3">
            <label for="lien" class="form-label">Lien</label>
            <input type="url" class="form-control" id="lien" name="lien" placeholder="https://example.com">
        </div>

        <button type="submit" class="btn btn-primary">Soumettre</button>
    </div>
</form>

<button id="bouton-ajouter" class="btn btn-primary mb-3">
    <i class="fa fa-plus" aria-hidden="true"></i> Ajouter
</button>

<?= $this->endSection() ?>