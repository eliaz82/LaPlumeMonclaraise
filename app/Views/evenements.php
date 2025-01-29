<?= $this->extend('layout') ?>
<?= $this->section('contenu') ?>

<div class="facebook-posts">
    <h2>Publications Facebook pour <?= isset($pageName) ? esc($pageName) : 'Page inconnue' ?></h2>

    <?php if (!empty($posts)) : ?>
        <?php foreach ($posts as $post) : ?>
            <div class="post">
                <p><?= esc($post['message'] ?? 'Message non disponible') ?></p>
                <small>Publié le : <?= isset($post['created_time']) ? date('d/m/Y H:i', strtotime($post['created_time'])) : 'Date inconnue' ?></small>
                <br>
                <a href="<?= esc($post['permalink_url'] ?? '#') ?>" target="_blank">Voir le post</a>
            </div>
        <?php endforeach; ?>
    <?php else : ?>
        <p>Aucune publication trouvée pour ce hashtag.</p>
    <?php endif; ?>
</div>

<h1>Bienvenue sur la page de l'événement de <?= esc($pageName) ?></h1>




<?= $this->endSection() ?>