<?= $this->extend('layout') ?>

<?= $this->section('css') ?>
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
        background-color: #eef2f3;
        color: #333;
        background: linear-gradient(135deg, #0f4c75, #3282b8);
    }
</style>
<link rel="stylesheet" type="text/css" href="<?= base_url('css/responsive.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('contenu') ?>

<!-- En-tête de la page -->
<div class="header">
    <h1>La Plume Monclaraise</h1>
    <p>Découvrez nos faits marquants et moments forts de la saison</p>
</div>

<div class="page-container">
    <?php if (!empty($posts)): ?>
        <?php foreach ($posts as $index => $post): ?>
            <div class="post">
                <?php if (!empty($post['image'])): ?>
                    <div class="post-image" style="background-image: url('<?= esc($post['image'], 'attr'); ?>');"></div>
                <?php endif; ?>
                <div class="content-container">
                    <?php
                    // Récupère le message brut et le traite pour afficher les retours à la ligne
                    $rawMessage = $post['message'];
                    // Échapper le message pour éviter les attaques XSS
                    $message = esc($rawMessage);
                    $message = trim($message);
                    // Normalise les retours à la ligne en un seul "\n"
                    $message = str_replace(["\r\n", "\r"], "\n", $message);
                    // Remplace les espaces multiples par un seul espace
                    $message = preg_replace('/\s{2,}/', ' ', $message);
                    // Ajoute un <br> après chaque point suivi d'éventuels espaces
                    $message = preg_replace('/([.])\s*/', '$1<br>', $message);
                    // Applique nl2br pour convertir les retours à la ligne en balises <br>
                    $message = nl2br($message);
                    ?>
                    <p class="post-message"><?= $message; ?></p>
                    <span class="read-more">Lire plus</span>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="no-posts">Aucun fait marquant trouvé pour le moment.</p>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<?= script_tag('js/readMore.js') ?>
<?= $this->endSection() ?>