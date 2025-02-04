<?= $this->extend('layout') ?>
<?= $this->section('contenu') ?>

<!-- Vos styles personnalisés -->
<style>
    .card {
        transition: transform 0.3s ease;
        border: none;
        /* Supprimer la bordure de la carte pour un rendu plus moderne */
        border-radius: 10px;
        /* Bordures arrondies pour la carte */
        overflow: hidden;
        /* Assure que l'image ne déborde pas */
        display: flex;
        flex-direction: column;
        height: 100%;
        /* Assurer que les cartes aient une hauteur flexible */
        background-color: #fff;
    }

    .card:hover {
        transform: translateY(-5px);
        /* Effet de survol */
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
        /* Caché par défaut */
    }

    .btn-primary {
        background-color: #2980b9;
        border-color: #2980b9;
    }

    .btn-primary:hover {
        background-color: #3498db;
        border-color: #3498db;
    }

    /* Cadrage de l'image dans la carte */
    .card-img-top {
        width: 100%;
        /* S'assurer que l'image prend toute la largeur */
        height: 200px;
        /* Hauteur fixe */
        object-fit: cover;
        /* Couvre toute la surface sans déformer l'image */
        object-position: center center;
        /* Centrer l'image */
        border-top-left-radius: 10px;
        /* Bordures arrondies en haut à gauche */
        border-top-right-radius: 10px;
        /* Bordures arrondies en haut à droite */
    }

    /* Style pour limiter la taille de l'image dans le modal */
    .modal-image {
        max-height: 150px;
        /* Ajustez cette valeur selon vos besoins */
        object-fit: cover;
        width: 100%;
    }

    /* Pour améliorer l'apparence de la carte dans une colonne */
    .col-md-4 {
        justify-content: center;
        margin-bottom: 30px;
        /* Espacement entre les cartes */
    }

    /* Style pour les messages sans image */
    .no-image {
        text-align: center;
        font-style: italic;
        color: #777;
        padding: 20px 0;
    }

    /* Pour la grille responsive */
    @media (max-width: 768px) {
        .col-md-4 {
            flex: 0 0 50%;
            /* 2 cartes par ligne sur les écrans plus petits */
        }
    }

    @media (max-width: 576px) {
        .col-md-4 {
            flex: 0 0 100%;
            /* 1 carte par ligne sur les petits écrans */
        }
    }

    /* Ajustement pour les cartes avec plus de contenu */
    .card-body {
        flex-grow: 1;
        /* Permet à la carte de grandir pour remplir l'espace */
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .highlight {
        border: 2px solid #ff9800;
        box-shadow: 0 0 10px rgba(255, 152, 0, 0.8);
    }

    /* (Optionnel) Pour forcer une hauteur uniforme dans la rangée,
       vous pouvez garder align-items: stretch sur le conteneur .row.
       Sinon, retirez ou adaptez cette règle. */
    .row {
        /* Par défaut Bootstrap aligne en stretch, donc on peut omettre ou ajuster cette règle */
    }
</style>

<div class="container mt-5">
    <div class="row">
        <?php if (!empty($posts)): ?>
            <?php foreach ($posts as $index => $post): ?>
                <?php
                // Vérifier si l'événement doit être mis en valeur
                $isHighlighted = (isset($highlightId) && $highlightId === $post['id']);

                // Récupérer le message et déterminer s'il dépasse 100 caractères
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
                        <?php if (!empty($post['image'])): ?>
                            <img src="<?= esc($post['image']) ?>" alt="Image de l'événement" class="card-img-top">
                        <?php else: ?>
                            <div class="no-image">Pas d'image disponible</div>
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?= esc($post['titre']) ?></h5>

                            <div class="card-text">
                                <!-- Affichage du texte court -->
                                <span class="short-text">
                                    <?= nl2br(esc($shortText)) ?>
                                    <?= ($displayToggle ? '...' : '') ?>
                                </span>
                                <?php if ($displayToggle): ?>
                                    <!-- Texte complet caché, qui servira à alimenter le modal -->
                                    <span class="full-text">
                                        <?= nl2br(esc($message)) ?>
                                    </span>
                                    <!-- Bouton pour ouvrir le modal -->
                                    <button class="toggle-text btn btn-link p-0">Voir plus</button>
                                <?php endif; ?>
                            </div>

                            <a href="<?= esc($post['permalink_url']) ?>" class="btn btn-primary" target="_blank">
                                Ouvrir sur Facebook
                            </a>
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

<!-- Modal Bootstrap pour afficher l'image et le contenu complet -->
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

<!-- Script pour ouvrir le modal au clic sur "Voir plus" -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Créer une instance du modal (Bootstrap 5)
        const modalElement = document.getElementById('fullMessageModal');
        const modal = new bootstrap.Modal(modalElement);

        // Ajouter un écouteur d'événement sur chaque bouton "Voir plus"
        document.querySelectorAll(".toggle-text").forEach(button => {
            button.addEventListener("click", function () {
                // Récupérer le conteneur parent .card-text
                let cardText = this.closest(".card-text");
                // Récupérer le contenu complet depuis la span .full-text
                let fullText = cardText.querySelector(".full-text").innerHTML;

                // Récupérer la carte parente pour extraire l'image
                let card = this.closest('.card');
                let cardImageElement = card.querySelector('img.card-img-top');
                let modalContent = '';

                // Si l'image existe, l'ajouter en haut du contenu du modal
                if (cardImageElement) {
                    modalContent += `<img src="${cardImageElement.src}" alt="${cardImageElement.alt}" class="img-fluid mb-3 modal-image">`;
                }

                // Ajouter ensuite le texte complet
                modalContent += fullText;

                // Insérer le contenu dans le body du modal
                modalElement.querySelector(".modal-body").innerHTML = modalContent;

                // Afficher le modal
                modal.show();
            });
        });
        // Si un événement est mis en valeur, défiler jusqu'à lui
        const highlighted = document.getElementById('highlightedEvent');
        if (highlighted) {
            highlighted.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });

</script>

<?= $this->endSection() ?>