<?= $this->extend('layout') ?>

<?= $this->section('css') ?>

<?= $this->endSection() ?>

<?= $this->section('contenu') ?>

<!-- Vos styles personnalisés -->
<style>
    /* Style général de la carte */
    .card {
        transition: transform 0.3s ease;
        border: none;
        border-radius: 10px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        height: 100%;
        background-color: #fff;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.1);
    }

    .card-title {
        font-weight: bold;
        color: #333;
        font-size: 1.2rem;
        margin-bottom: 15px;
    }

    .card-text {
        overflow: hidden;
    }

    .short-text {
        display: inline;
    }

    .full-text {
        display: none;
    }

    /* Bouton par défaut */
    .btn-primary {
        background-color: #2980b9;
        border-color: #2980b9;
    }

    .btn-primary:hover {
        background-color: #3498db;
        border-color: #3498db;
    }

    /* Conteneur des boutons d'action */
    .action-buttons {
        display: flex;
        gap: 10px;
        /* Espace entre les boutons */
        margin-top: 10px;
    }

    /* Assurer que tous les boutons ont la même taille */
    .action-buttons>* {
        flex: 1;
    }

    .btn-primary,
    .btn-warning,
    .btn-danger {
        transition: background-color 0.3s ease, transform 0.2s ease;
    }

    .btn-primary:hover,
    .btn-warning:hover,
    .btn-danger:hover {
        background-color: #0056b3;
        transform: scale(1.1);
    }

    .btn-primary {
        background-color: #007bff;
    }

    .btn-warning {
        background-color: #ffc107;
    }

    .btn-danger {
        background-color: #dc3545;
    }

    /* Bouton "Ouvrir sur Facebook" */
    .btn-facebook {
        background-color: #3b5998;
        color: white;
        border: none;
    }

    .btn-facebook:hover {
        background-color: #2d4373;
    }


    /* Cadrage de l'image dans la carte */
    .card-img-top {
        width: 100%;
        height: 200px;
        object-fit: cover;
        object-position: center center;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
    }

    .modal-image {
        max-height: 150px;
        object-fit: cover;
        width: 100%;
    }

    /* Divers styles */
    .col-md-4 {
        justify-content: center;
        margin-bottom: 30px;
    }

    .no-image {
        text-align: center;
        font-style: italic;
        color: #777;
        padding: 20px 0;
    }

    @media (max-width: 768px) {
        .col-md-4 {
            flex: 0 0 50%;
        }
    }

    @media (max-width: 576px) {
        .col-md-4 {
            flex: 0 0 100%;
        }
    }

    .card-body {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .highlight {
        border: 2px solid #ff9800;
        box-shadow: 0 0 10px rgba(255, 152, 0, 0.8);
    }
</style>

<!-- Bouton pour ouvrir le modal d'ajout d'événement -->
<?php if (auth()->loggedIn()): ?>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEventModal">
        Ajouter un événement
    </button>

    <!-- Modal d'ajout d'événement -->
    <div class="modal fade" id="addEventModal" tabindex="-1" aria-labelledby="addEventModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-primary" id="addEventModalLabel">Ajouter un événement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <form action="<?= esc(url_to('createEvenement')) ?>" method="POST" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label for="titre" class="form-label">Titre de l'événement</label>
                            <input type="text" class="form-control" id="titre" name="titre" required>
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="date" class="form-label">Date de l'événement</label>
                            <input type="date" class="form-control" id="date" name="date" required
                                value="<?= date('Y-m-d') ?>">
                        </div>
                        <div class="mb-3">
                            <label for="createImage" class="form-label">Image (optionnel)</label>
                            <input type="file" class="form-control" id="createImage" name="image" accept=".jpg, .jpeg, .png, .gif, .webp, .svg"
                                onchange="previewImage(event, 'createImagePreview')">
                        </div>
                        <!-- Prévisualisation de l'image -->
                        <div class="mb-3 d-flex justify-content-center align-items-center text-center">
                            <img id="createImagePreview" src="" alt="Aperçu de l'image"
                                style="max-width: 80%; max-height: 200px; display: none; border-radius: 8px; object-fit: cover; padding: 5px;">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-primary">Ajouter</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal de modification d'événement -->
    <div class="modal fade" id="editEventModal" tabindex="-1" aria-labelledby="editEventModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-primary" id="editEventModalLabel">Modifier l'événement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <form action="<?= esc(url_to('updateEvenement')) ?>" method="POST" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        <input type="hidden" name="idEvenement" id="editEventId">
                        <div class="mb-3">
                            <label for="editTitre" class="form-label">Titre</label>
                            <input type="text" class="form-control" id="editTitre" name="titre" required>
                        </div>
                        <div class="mb-3">
                            <label for="editMessage" class="form-label">Message</label>
                            <textarea class="form-control" id="editMessage" name="message" rows="4" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="editDate" class="form-label">Date de l'événement</label>
                            <input type="date" class="form-control" id="editDate" name="date" required>
                        </div>
                        <div class="mb-3">
                            <label for="editImage" class="form-label">Image (optionnel)</label>
                            <input type="file" class="form-control" id="editImage" name="image"
                                onchange="previewImage(event, 'editImagePreview')">
                        </div>
                        <!-- Prévisualisation de l'image -->

                        <div class="mb-3 d-flex justify-content-center align-items-center text-center">
                            <img id="editImagePreview" src="" alt="Aperçu de l'image"
                                style="max-width: 80%; max-height: 200px; display: none; border-radius: 8px; object-fit: cover; padding: 5px;">
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
<?php endif; ?>

<!-- Affichage des événements -->
<div class="container mt-5">
    <div class="row">
        <?php if (!empty($posts)): ?>
            <?php foreach ($posts as $index => $post): ?>
                <?php
                // Identification et mise en valeur d'un événement particulier
                $postId = $post['id'] ?? $post['idEvenement'] ?? null;
                $isHighlighted = (isset($highlightId) && $highlightId == $postId);

                // Détermination du texte court et complet
                $message = $post['message'];
                if (mb_strlen($message) > 100) {
                    $shortText = mb_substr($message, 0, 100);
                    $displayToggle = true;
                } else {
                    $shortText = $message;
                    $displayToggle = false;
                }
                ?>
                <div class="col-md-4 col-12 mb-4">
                    <div class="card shadow-sm <?= $isHighlighted ? 'highlight' : '' ?>" <?= $isHighlighted ? 'id="highlightedEvent"' : '' ?>>
                        <?php
                        $imageUrl = isset($post['image']) ? $post['image'] : null;
                        if ($imageUrl && !str_starts_with($imageUrl, 'http')) {
                            $imageUrl = base_url($imageUrl);
                        }
                        ?>
                        <?php if (!empty($imageUrl)): ?>
                            <img src="<?= esc($imageUrl) ?>" alt="Image de l'événement" class="card-img-top">
                        <?php else: ?>
                            <div class="no-image">Pas d'image disponible</div>
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?= esc($post['titre']) ?></h5>
                            <p
                                class="event-status <?= esc($post['status'] === 'Événement passé' ? 'text-danger' : 'text-success') ?>">
                                <?= esc($post['status']) ?>
                            </p>
                            <div class="card-text">
                                <span class="short-text">
                                    <?= nl2br(esc($shortText)) ?>
                                    <?= ($displayToggle ? '...' : '') ?>
                                </span>
                                <?php if ($displayToggle): ?>
                                    <span class="full-text">
                                        <?= nl2br(esc($message)) ?>
                                    </span>
                                    <button class="toggle-text btn btn-link p-0">Voir plus</button>
                                <?php endif; ?>
                            </div>
                            <div class="action-buttons">
                                <?php if (isset($post['permalink_url'])): ?>
                                    <a href="<?= esc($post['permalink_url']) ?>" class="btn btn-facebook" target="_blank">
                                        Ouvrir sur Facebook
                                    </a>
                                <?php else: ?>
                                    <?php if (auth()->loggedIn()): ?>
                                        <button class="btn btn-warning bouton-modifier-evenement" data-bs-toggle="modal"
                                            data-bs-target="#editEventModal" data-id="<?= esc($post['idEvenement']) ?>"
                                            data-titre="<?= esc($post['titre']) ?>" data-message="<?= esc($post['message']) ?>"
                                            data-date="<?= esc($post['date']) ?>" data-image="<?= esc($post['image']) ?>">
                                            Modifier
                                        </button>
                                        <form action="<?= esc(url_to('evenementDelete')) ?>" method="POST">
                                            <input type="hidden" name="idEvenement" value="<?= esc($post['idEvenement']) ?>">
                                            <button type="submit" class="btn btn-danger"
                                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet événement ?')">
                                                Supprimer
                                            </button>
                                        </form>
                                    <?php endif; ?>

                                <?php endif; ?>
                            </div>

                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <p class="text-center">Aucun événement trouvé</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal d'affichage du message complet -->
<div class="modal fade" id="fullMessageModal" tabindex="-1" aria-labelledby="fullMessageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="fullMessageModalLabel">Message complet</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <!-- Le contenu complet et l'image seront insérés ici via JavaScript -->
            </div>
        </div>
    </div>
</div>



<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<?php if (auth()->loggedIn()): ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <?= script_tag('js/form-modifications.js') ?>
<?php endif; ?>
<?= script_tag('js/events-ui.js') ?>


<?= $this->endSection() ?>