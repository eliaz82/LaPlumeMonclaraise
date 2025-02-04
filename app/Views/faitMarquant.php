<?= $this->extend('layout') ?>
<?= $this->section('contenu') ?>

<!-- On ne réintroduit pas de balises HTML ou HEAD ici si votre layout s'en charge déjà -->
<style>
    /* Réinitialisation de base */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    body {
        font-family: Arial, sans-serif;
        background-color: #f7f7f7;
    }
    /* Conteneur global de la page */
    .page-container {
        max-width: 1000px;
        margin: 20px auto;
        padding: 10px;
    }
    /* Conteneur de chaque post */
    .post {
        display: flex;
        flex-direction: row;
        align-items: stretch; /* Les deux colonnes auront la même hauteur */
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 8px;
        overflow: hidden; /* Empêche tout débordement */
        margin-bottom: 20px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    /* Colonne pour l'image */
    .image-container {
        flex: 0 0 200px; /* Largeur fixe de 200px */
        height: 200px;   /* Hauteur fixe */
        background-color: #eee;
        overflow: hidden; /* On cache le débordement */
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .image-container img {
        width: 100%;
        height: 100%;
        object-fit: cover; /* L'image remplit le conteneur sans être déformée */
        display: block;
    }
    /* Colonne pour le contenu (texte et lien) */
    .content-container {
        flex: 1;
        padding: 15px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .content-container p {
        color: #333;
        font-size: 1rem;
        margin-bottom: 15px;
        line-height: 1.4;
    }
    .content-container a {
        align-self: flex-start;
        text-decoration: none;
        background-color: #2980b9;
        color: #fff;
        padding: 8px 12px;
        border-radius: 4px;
        transition: background-color 0.3s ease;
    }
    .content-container a:hover {
        background-color: #3498db;
    }
    /* Style pour le cas d'absence d'image */
    .no-image {
        color: #777;
        font-size: 0.9rem;
        text-align: center;
        padding: 10px;
    }
</style>

<div class="page-container">
    <?php if (!empty($posts)): ?>
        <?php foreach ($posts as $post): ?>
            <div class="post">
                <!-- Colonne pour l'image -->
                <div class="image-container">
              
                    <?php if (!empty($post['image'])): ?>
                        <img src="<?= esc($post['image']) ?>" alt="Image de l'événement">
                    <?php else: ?>
                        <div class="no-image">Pas d'image disponible</div>
                    <?php endif; ?>
                </div>
                <!-- Colonne pour le contenu -->
                <div class="content-container">
                    <p><?= esc($post['message']) ?></p>
                    <?php if (!empty($post['permalink_url'])): ?>
                        <a href="<?= esc($post['permalink_url']) ?>" target="_blank">Voir l'événement</a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p style="text-align: center;">Aucun événement trouvé</p>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
