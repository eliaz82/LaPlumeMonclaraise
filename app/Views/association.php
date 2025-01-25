<?= $this->extend('layout') ?>

<?= $this->section('contenu') ?>
<?= view('partenaires', ['partenaire' => $partenaire]) ?>
    <?= view('equipes', ['equipes' => $equipes]) ?>
    <?= view('histoire') ?>
<?= $this->endSection() ?>
