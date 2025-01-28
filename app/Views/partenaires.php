<section id="partenaire">
    <div>

        <!-- Titre principal -->
        <div class="text-center mb-5">
            <h1 class="display-5 fw-bold text-primary">Gestion des Partenaires</h1>
            <p class="text-muted">Ajoutez, modifiez ou supprimez vos partenaires en toute simplicité.</p>
        </div>

        <!-- Bouton d'ouverture du modal pour ajouter un partenaire -->
        <div class="text-center mb-4">
            <button id="bouton-ajouter-partenaire" class="btn btn-primary btn-lg shadow-sm" data-bs-toggle="modal"
                data-bs-target="#modalAjouter">
                <i class="fa fa-plus me-2"></i> Ajouter un partenaire
            </button>
        </div>

        <!-- Liste des partenaires -->
        <div class="my-5">
            <h2 class="text-center mb-4">Nos partenaires</h2>
            <div class="row justify-content-center gy-4">
                <?php foreach ($partenaire as $p): ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="card shadow-lg border-0 h-100">
                            <?php if (!empty($p['lien'])): ?>
                                <a href="<?= $p['lien'] ?>" target="_blank" class="text-decoration-none text-dark">
                                <?php endif; ?>
                                <div class="text-center mt-4">
                                    <img src="<?= base_url($p['logo']) ?>" class="img-fluid rounded-circle shadow"
                                        alt="<?= $p['info'] ?>" style="width: 150px; height: 150px; object-fit: cover;">
                                </div>
                                <div class="card-body text-center">
                                    <h5 class="card-title fw-bold"><?= $p['info'] ?></h5>
                                </div>
                                <?php if (!empty($p['lien'])): ?>
                                </a>
                            <?php endif; ?>
                            <div class="card-footer text-center">
                                <button class="btn btn-warning btn-sm me-2 bouton-modifier-partenaire"
                                    data-id="<?= $p['idPartenaire'] ?>"
                                    data-info="<?= htmlspecialchars($p['info'], ENT_QUOTES) ?>"
                                    data-lien="<?= htmlspecialchars($p['lien'], ENT_QUOTES) ?>"
                                    data-logo="<?= base_url($p['logo']) ?>" data-bs-toggle="modal"
                                    data-bs-target="#modalModifier">
                                    Modifier
                                </button>
                                <form action="<?= route_to('partenairesDelete') ?>" method="post" class="d-inline">
                                    <input type="hidden" name="idPartenaire" value="<?= $p['idPartenaire'] ?>">
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce partenaire ?');">Supprimer</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Modal d'ajout de partenaire -->
        <div class="modal fade" id="modalAjouter" tabindex="-1" aria-labelledby="modalAjouterLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form id="formulaire-ajouter" method="post" action="<?= url_to('partenairesSubmit') ?>"
                        enctype="multipart/form-data">
                        <div class="modal-header">
                            <h5 class="modal-title text-primary" id="modalAjouterLabel">Ajouter un partenaire</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="logo" class="form-label">Logo</label>
                                <input type="file" class="form-control" id="logo" name="logo" accept="image/*"
                                    onchange="previewImage(event, 'logoPreview')">
                            </div>
                            <div class="text-center mb-3">
                                <img id="logoPreview" src="#" alt="Prévisualisation"
                                    class="img-fluid rounded-circle shadow" style="max-width: 150px; display: none;">
                            </div>
                            <div class="mb-3">
                                <label for="info" class="form-label">Informations de texte</label>
                                <textarea class="form-control" id="info" name="info" rows="3"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="lien" class="form-label">Lien</label>
                                <input type="url" class="form-control" id="lien" name="lien"
                                    placeholder="https://example.com">
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

        <!-- Modal de modification (existant) -->
        <div class="modal fade" id="modalModifier" tabindex="-1" aria-labelledby="modalModifierLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form id="formulaire-modifier" method="post" action="<?= url_to('partenairesUpdate') ?>"
                        enctype="multipart/form-data">
                        <div class="modal-header">
                            <h5 class="modal-title text-primary" id="modalModifierLabel">Modifier un partenaire</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="modifier-id" name="idPartenaire">
                            <div class="mb-3">
                                <label for="modifier-logo" class="form-label">Logo</label>
                                <input type="file" class="form-control" id="modifier-logo" name="logo" accept="image/*"
                                    onchange="previewImage(event, 'modifierLogoPreview')">
                                <div class="text-center mt-3">
                                    <img id="modifierLogoPreview" src="#" alt="Logo actuel"
                                        class="img-fluid rounded-circle shadow"
                                        style="max-width: 150px; display: none;">
                                </div>
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
    </div>
</section>