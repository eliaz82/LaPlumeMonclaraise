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
    <h2 class="text-center mb-4">Téléchargement du fichier d'inscription</h2>

    <p class="lead text-center mb-4">
        Téléchargez le fichier d'inscription ci-dessous. Une fois le fichier téléchargé, vous devez l'imprimer, le
        remplir et le remettre au gymnase soit en main propre, soit par e-mail. Merci de suivre ces instructions afin de
        compléter votre inscription pour la saison à venir.
    </p>

    <div class="row align-items-center">
        <div class="col-md-12">
            <div class="d-flex flex-column align-items-center">
                <?php if (isset($fichierInscription)): ?>
                    <?php if (strpos($fichierInscription, '.pdf') !== false): ?>
                        <h3 class="text-center">Prévisualisation du fichier d'inscription :</h3>
                        <div class="d-flex justify-content-center mb-4" style="width: 100%; overflow: hidden;">
                            <embed src="<?= base_url($fichierInscription) ?>" type="application/pdf"
                                style="width: 80%; height: 500px;">
                        </div>
                    <?php elseif (in_array(pathinfo($fichierInscription, PATHINFO_EXTENSION), ['jpg', 'png', 'jpeg'])): ?>
                        <h3 class="text-center">Prévisualisation de l'image :</h3>
                        <div class="d-flex justify-content-center mb-4">
                            <img src="<?= base_url($fichierInscription) ?>" alt="Image d'inscription" class="img-fluid"
                                style="max-width: 80%; height: auto;">
                        </div>
                    <?php else: ?>
                        <p>Aucune prévisualisation disponible pour ce type de fichier.</p>
                    <?php endif; ?>
                <?php else: ?>
                    <p>Aucun fichier d'inscription disponible pour le moment.</p>
                <?php endif; ?>
            </div>

            <div class="text-center mt-4">
                <a href="<?= base_url($fichierInscription) ?>" class="btn btn-primary" download>
                    Télécharger le fichier d'inscription
                </a>
            </div>

            <form action="<?= route_to('fichierInscriptionSubmit') ?>" method="post" enctype="multipart/form-data"
                class="mt-4">
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label for="fichier_inscription" class="form-label">Sélectionner le fichier d'inscription (PDF,
                        Word, etc.) :</label>
                    <input type="file" name="fichier_inscription" id="fichier_inscription"
                        accept=".pdf,.doc,.docx,.jpg,.png" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-success">Télécharger le fichier</button>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>