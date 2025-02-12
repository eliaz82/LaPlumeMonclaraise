<?= $this->extend('layout') ?>
<?= $this->section('title') ?>
L'association
<?= $this->endSection() ?>
<?= $this->section('css') ?>
<link rel="stylesheet" type="text/css" href="<?= base_url('css/responsive.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('contenu') ?>
<?= view('histoire', ['association' => esc($association)]) ?>
<?= view('equipes', ['equipes' => esc($equipes)]) ?>
<?= view('partenaires', ['partenaire' => esc($partenaire)]) ?>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<?php if (auth()->loggedIn()): ?>
    <!-- Utilisation de jQuery avec intégrité pour assurer l'intégrité du fichier -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha384-KJ3o2DKtIkvYIKW2Jv8j0D1k2+fmv6KTJt2IH5tHqJQFpnHpN9sz9Rj6qpmJXCBF" crossorigin="anonymous"></script>
    <?= script_tag('js/form-modifications.js') ?>
<?php endif; ?>

<?= $this->endSection() ?>