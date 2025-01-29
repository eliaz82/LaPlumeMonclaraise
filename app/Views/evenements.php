<?= $this->extend('layout') ?>
<?= $this->section('contenu') ?>

<?php if (!empty($posts)): ?>
    <?php foreach ($posts as $post): ?>
        <div class="post">
            <div class="fb-post" data-href="<?php echo $post['permalink_url']; ?>" data-width="500"></div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p>Aucune publication trouvée</p>
<?php endif; ?>




<?= $this->endSection() ?>