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
                <button class="botao">
                    <a href="<?= base_url($fichierInscription) ?>" download>
                        <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg" class="mysvg">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <g id="Interface / Download">
                                    <path id="Vector" d="M6 21H18M12 3V17M12 17L17 12M12 17L7 12" stroke="#f1f1f1"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                </g>
                            </g>
                        </svg>
                        <span class="texto">Télécharger</span>
                    </a>
                </button>
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