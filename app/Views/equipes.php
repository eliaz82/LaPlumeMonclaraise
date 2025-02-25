<section id="equipe">
    <div>
        <?php if (auth()->loggedIn()): ?>
            <!-- Bouton d'ajout -->
            <div class="text-center mb-4">
                <button id="bouton-ajouter-adherent" class="btn btn-primary btn-lg shadow-sm" data-bs-toggle="modal"
                    data-bs-target="#modalAjouterAdherent">
                    <i class="fa fa-plus me-2"></i> Ajouter un membre du bureau
                </button>
            </div>
            <!-- Modal d'ajout -->
            <div class="modal fade" id="modalAjouterAdherent" tabindex="-1" aria-labelledby="modalAjouterAdherentLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form id="formulaire-ajouter-adherent" method="post"
                            action="<?= esc(url_to('equipeSubmit'), 'attr') ?>" enctype="multipart/form-data">
                            <?= csrf_field() ?>
                            <div class="modal-header">
                                <h5 class="modal-title text-primary" id="modalAjouterAdherentLabel">Ajouter un adhérent</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="nom-adherent" class="form-label">Nom</label>
                                    <input type="text" class="form-control" id="nom-adherent" name="nom"
                                        placeholder="Nom de l'adhérent" required>
                                </div>
                                <div class="mb-3">
                                    <label for="prenom-adherent" class="form-label">Prénom</label>
                                    <input type="text" class="form-control" id="prenom-adherent" name="prenom"
                                        placeholder="Prénom de l'adhérent" required>
                                </div>
                                <div class="mb-3">
                                    <label for="grade-adherent" class="form-label">Grade</label>
                                    <input type="text" class="form-control" id="grade-adherent" name="grade"
                                        placeholder="Grade de l'adhérent">
                                </div>
                                <div class="mb-3">
                                    <label for="photo-adherent" class="form-label">Photo</label>
                                    <input type="file" class="form-control" id="photo-adherent" name="photo"
                                        accept="image/*" onchange="previewImage(event, 'photoPreviewAdherent')">
                                    <div class="mb-3 d-flex justify-content-center align-items-center text-center">
                                        <img id="photoPreviewAdherent" src="#" alt="Prévisualisation"
                                            style="max-width: 80%; max-height: 200px; display: none; border-radius: 8px; object-fit: cover; padding: 5px;">
                                    </div>
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
            <!-- Modal de modification (inchangé) -->
            <div class="modal fade" id="modalModifierAdherent" tabindex="-1" aria-labelledby="modalModifierAdherentLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form id="formulaire-modifier-adherent" method="post"
                            action="<?= esc(url_to('equipeUpdate'), 'attr') ?>" enctype="multipart/form-data">
                            <?= csrf_field() ?>
                            <div class="modal-header">
                                <h5 class="modal-title text-primary" id="modalModifierAdherentLabel">Modifier un adhérent
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" id="modifier-id-adherent" name="idAdherant">
                                <div class="mb-3">
                                    <label for="modifier-nom-adherent" class="form-label">Nom</label>
                                    <input type="text" class="form-control" id="modifier-nom-adherent" name="nom" required>
                                </div>
                                <div class="mb-3">
                                    <label for="modifier-prenom-adherent" class="form-label">Prénom</label>
                                    <input type="text" class="form-control" id="modifier-prenom-adherent" name="prenom"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label for="modifier-grade-adherent" class="form-label">Grade</label>
                                    <input type="text" class="form-control" id="modifier-grade-adherent" name="grade">
                                </div>
                                <div class="mb-3">
                                    <label for="modifier-photo-adherent" class="form-label">Photo</label>
                                    <input type="file" class="form-control" id="modifier-photo-adherent" name="photo"
                                        accept="image/*" onchange="previewImage(event, 'modifierPhotoPreviewAdherent')">
                                    <div class="mb-3 d-flex justify-content-center align-items-center text-center"> <img
                                            id="modifierPhotoPreviewAdherent" src="#" alt="Prévisualisation"
                                            style="max-width: 80%; max-height: 200px; display: none; border-radius: 8px; object-fit: cover; padding: 5px;">
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
        <?php endif; ?>


        <!-- Liste des adhérents -->
        <div class="row justify-content-center gy-4">
            <h2 class="text-center mb-4">Notre bureau</h2>
            <?php foreach ($equipes as $e): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="card shadow-lg border-0 h-100">
                        <div class="text-center mt-4">
                            <img src="<?= esc(base_url($e['photo']), 'attr') ?>" class="img-fluid rounded-circle shadow"
                                alt="<?= esc($e['prenom'] . ' ' . $e['nom'], 'attr') ?>"
                                style="width: 150px; height: 150px; object-fit: cover;">
                        </div>
                        <div class="card-body text-center">
                            <h5 class="card-title fw-bold text-primary"><?= esc($e['prenom'] . ' ' . $e['nom'], 'attr') ?>
                            </h5>
                            <span class="badge bg-secondary"><?= esc($e['grade'], 'attr') ?></span>
                        </div>
                        <div class="card-footer text-center">
                            <?php if (auth()->loggedIn()): ?>
                                <button class="btn btn-warning btn-sm me-2 bouton-modifier-adherent"
                                    data-id="<?= esc($e['idAdherants'], 'attr') ?>" data-nom="<?= esc($e['nom'], 'attr') ?>"
                                    data-prenom="<?= esc($e['prenom'], 'attr') ?>" data-grade="<?= esc($e['grade'], 'attr') ?>"
                                    data-photo="<?= esc(base_url($e['photo']), 'attr') ?>" data-bs-toggle="modal"
                                    data-bs-target="#modalModifierAdherent">Modifier</button>
                                <form action="<?= esc(route_to('equipeDelete'), 'attr') ?>" method="post" class="d-inline">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="idAdherant" value="<?= esc($e['idAdherants'], 'attr') ?>">
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet adhérent ?');">
                                        Supprimer
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>


</section>