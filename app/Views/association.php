<?= $this->extend('layout') ?>

<?= $this->section('contenu') ?>
<?= view('histoire') ?>
<?= view('equipes', ['equipes' => $equipes]) ?>
<?= view('partenaires', ['partenaire' => $partenaire]) ?>
<?= $this->endSection() ?>
