<section id="equipe">
    <div>


        <!-- Bouton d'ajout -->
        <div class="text-center mb-4">
            <button id="bouton-ajouter-adherent" class="btn btn-primary btn-lg shadow-sm" data-bs-toggle="modal"
                data-bs-target="#modalAjouterAdherent">
                <i class="fa fa-plus me-2"></i> Ajouter un adhérent
            </button>
        </div>

        <!-- Modal d'ajout -->
        <h2 class="text-center mb-4">Nos adhérents</h2>
        <div class="modal fade" id="modalAjouterAdherent" tabindex="-1" aria-labelledby="modalAjouterAdherentLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form id="formulaire-ajouter-adherent" method="post" action="<?= url_to('equipeSubmit') ?>"
                        enctype="multipart/form-data">
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
                                <div class="text-center mt-3">
                                    <img id="photoPreviewAdherent" src="#" alt="Prévisualisation"
                                        class="img-fluid shadow rounded-circle"
                                        style="max-width: 150px; display: none;">
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
                            <button class="btn btn-warning btn-sm me-2 bouton-modifier-adherent"
                                data-id="<?= $e['idAdherants'] ?>" data-nom="<?= $e['nom'] ?>"
                                data-prenom="<?= $e['prenom'] ?>" data-grade="<?= $e['grade'] ?>"
                                data-photo="<?= base_url($e['photo']) ?>" data-bs-toggle="modal"
                                data-bs-target="#modalModifierAdherent">Modifier</button>
                            <form action="<?= route_to('equipeDelete') ?>" method="post" class="d-inline">
                                <input type="hidden" name="idAdherant" value="<?= $e['idAdherants'] ?>">
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

    <!-- Modal de modification (inchangé) -->
    <div class="modal fade" id="modalModifierAdherent" tabindex="-1" aria-labelledby="modalModifierAdherentLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="formulaire-modifier-adherent" method="post" action="<?= url_to('equipeUpdate') ?>"
                    enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title text-primary" id="modalModifierAdherentLabel">Modifier un adhérent</h5>
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
                            <div class="text-center mt-3">
                                <img id="modifierPhotoPreviewAdherent" src="#" alt="Prévisualisation"
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
</section>