<?= $this->extend('layout') ?>
<?= $this->section('contenu') ?>

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
        /* Fond blanc pour les cartes */
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
        color: #555;
        font-size: 1rem;
        margin-bottom: 20px;
    }

    .btn-primary {
        background-color: #2980b9;
        border-color: #2980b9;
    }

    .btn-primary:hover {
        background-color: #3498db;
        border-color: #3498db;
    }

    .container {
        padding: 20px;
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

    /* Pour améliorer l'apparence de la carte dans une colonne */
    .col-md-4 {
        display: flex;
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
</style>

<div class="container mt-5">
    <div class="row">
        <?php if (!empty($posts)): ?>
            <?php foreach ($posts as $post): ?>
                <?php
                // Vérifier si l'événement doit être mis en valeur
                $isHighlighted = (isset($highlightId) && $highlightId === $post['id']);
                ?>
                <div class="col-md-4 col-12 mb-4">
                    <div class="card shadow-sm <?= $isHighlighted ? 'highlight' : '' ?>">
                        <?php if (!empty($post['image'])): ?>
                            <img src="<?= esc($post['image']) ?>" alt="Image de l'événement" class="card-img-top">
                        <?php else: ?>
                            <div class="no-image">Pas d'image disponible</div>
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?= esc($post['message']) ?></h5>
                            <p class="card-text"><?= isset($post['message']) ? substr(esc($post['message']), 0, 100) . '...' : '' ?></p>
                            <a href="<?= site_url('evenement/' . $post['id']) ?>" class="btn btn-primary">
                                Voir l'événement
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



<?= $this->endSection() ?>