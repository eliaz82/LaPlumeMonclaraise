<?= $this->extend('layout') ?>
<?= $this->section('contenu') ?>
<div class="container">

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
                <a href="<?= base_url($fichierInscription) ?>" class="btn-download" download>
                    <button class="botao">
                        <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg" class="mysvg">
                            <g id="SVGRepo_iconCarrier">
                                <path id="Vector" d="M6 21H18M12 3V17M12 17L17 12M12 17L7 12" stroke="#f1f1f1"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            </g>
                        </svg>
                        <span class="texto">Télécharger</span>
                    </button>
                </a>
                <!-- Bouton pour ouvrir le modal -->
                <button id="bouton-modifier-inscription" class="btn btn-modify" data-bs-toggle="modal"
                    data-bs-target="#modalModifierInscription">
                    Modifier le fichier d'inscription
                </button>

            </div>


            <!-- Modal de modification du fichier d'inscription -->
            <div class="modal fade" id="modalModifierInscription" tabindex="-1"
                aria-labelledby="modalModifierInscriptionLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-primary" id="modalModifierInscriptionLabel">Modifier le fichier
                                d'inscription</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="<?= route_to('fichierInscriptionSubmit') ?>" method="post"
                                enctype="multipart/form-data" class="mt-4 p-4 border shadow-sm rounded-3">
                                <div class="text-center mb-3">
                                    <span class="h4 d-block">Téléversez votre fichier</span>
                                    <p class="text-muted">Le fichier doit être une image ou un document (PDF, Word,
                                        etc.). Il sera ensuite disponible pour les autres utilisateurs à télécharger.
                                    </p>
                                </div>
                                <div class="mb-3">
                                    <label for="fichier_inscription" class="form-label">Sélectionner le fichier
                                        d'inscription (PDF, Word, etc.) :</label>
                                    <div id="drop-area" class="drop-container p-4 border-dashed text-center rounded-3">
                                        <span class="drop-title d-block mb-2">Déposez les fichiers ici ou cliquez pour
                                            sélectionner</span>
                                        <input type="file" name="fichier_inscription" id="fichier_inscription"
                                            accept=".pdf,.doc,.docx,.jpg,.png" class="form-control d-none" required>
                                        <label for="fichier_inscription" class="btn btn-outline-primary">
                                            <i class="upload-icon">📁</i> Sélectionner un fichier
                                        </label>
                                        <div id="file-name" class="mt-2 text-muted"></div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success w-100">Téléverser le fichier</button>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>

<?= $this->endSection() ?>