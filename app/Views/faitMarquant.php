<?= $this->extend('layout') ?>
<?= $this->section('contenu') ?>

<style>
    /* Réinitialisation de base */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
        background-color: #eef2f3;
        color: #333;

    }

    /* En-tête de la page */
    .header {
        background: linear-gradient(135deg, #0f4c75, #3282b8);
        color: #fff;
        padding: 40px 20px;
        text-align: center;
    }

    .header h1 {
        font-size: 2.5rem;
        margin-bottom: 10px;
    }

    .header p {
        font-size: 1.2rem;
        font-weight: 300;
    }

    /* Conteneur global */
    .page-container {
        max-width: 1200px;
        margin: 30px auto;
        padding: 20px;
    }

    /* Conteneur de chaque post */
    .post {
        display: flex;
        align-items: center;
        margin-bottom: 30px;
        background: #fff;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
        padding: 20px;
    }

    .post:hover {
        transform: translateY(-5px);
    }

    /* Inverse l'ordre pour les posts pairs */
    .post:nth-child(even) {
        flex-direction: row-reverse;
    }

    /* Zone d'affichage de l'image */
    .post-image {
        width: 300px;
        height: 300px;
        overflow: hidden;
        background-size: cover;
        background-position: center;
        flex-shrink: 0;
        border-radius: 8px;
    }

    /* Contenu du post */
    .content-container {
        flex: 1;
        padding: 20px;
        text-align: center;
    }

    .content-container p {
        font-size: 1.2rem;
        line-height: 1.8;
        color: #555;
        margin-bottom: 10px;
        white-space: pre-line;
    }

    /* Bouton "Lire plus" */
    .read-more {
        color: #0f4c75;
        cursor: pointer;
        font-weight: bold;
        font-size: 1rem;
        margin-top: 10px;
    }

    /* Message en cas d'absence de faits marquants */
    .no-posts {
        text-align: center;
        font-size: 1.4rem;
        margin-top: 40px;
    }
</style>

<!-- En-tête de la page -->
<div class="header">
    <h1>La Plume MonClaraise </h1>
    <p>Découvrez nos faits marquants et moments forts de la saison</p>
</div>

<div class="page-container">
    <?php if (!empty($posts)): ?>
        <?php foreach ($posts as $index => $post): ?>
            <div class="post">
                <?php if (!empty($post['image'])): ?>
                    <div class="post-image" style="background-image: url('<?= esc($post['image']) ?>');"></div>
                <?php endif; ?>
                <div class="content-container">
                    <?php
                    $message = esc($post['message']);
                    $message = trim($message); // Supprime les espaces en début et fin de chaîne
                    ?>
                    <p class="post-message"><?= nl2br($message) ?></p>
                    <span class="read-more">Lire plus</span>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="no-posts">Aucun fait marquant trouvé pour le moment.</p>
    <?php endif; ?>
</div>

<!-- JavaScript pour gérer le bouton "Lire plus" -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var posts = document.querySelectorAll('.post-message');

        posts.forEach(function(post) {
            var words = post.innerText.split(/\s+/); // Divise le texte en mots
            var maxWords = 25; // Nombre maximum de mots avant la coupure

            if (words.length > maxWords) {
                var truncated = words.slice(0, maxWords).join(' ') + '...'; // Tronque le texte
                var fullText = post.innerText.trim().replace(/\s+/g, ' '); // Texte complet nettoyé

                post.innerText = truncated; // Affiche la version tronquée
                post.dataset.fullText = fullText; // Stocke le texte complet
                post.dataset.truncatedText = truncated; // Stocke le texte tronqué

                // Crée le bouton "Lire plus"
                var readMore = document.createElement("span");
                readMore.classList.add("read-more");
                readMore.textContent = "Lire plus";
                post.parentNode.appendChild(readMore); // Ajoute le bouton après le texte

                // Gère l'affichage du texte complet / tronqué
                readMore.addEventListener("click", function() {
                    if (post.classList.contains("expanded")) {
                        post.innerText = truncated; // Revenir au texte tronqué
                        this.textContent = "Lire plus";
                    } else {
                        post.innerText = fullText; // Afficher le texte complet
                        this.textContent = "Lire moins";
                    }
                    post.classList.toggle("expanded");
                });
            }
        });
    });
</script>

<?= $this->endSection() ?>