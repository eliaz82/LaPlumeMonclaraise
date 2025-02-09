<?= $this->extend('layout') ?>
<?= $this->section('css') ?>

<?= $this->endSection() ?>

<?= $this->section('contenu') ?>
<?= view('histoire',['association' => $association]) ?>
<?= view('equipes', ['equipes' => $equipes]) ?>
<?= view('partenaires', ['partenaire' => $partenaire]) ?>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<?= script_tag('js/form-modifications.js') ?>
<?= $this->endSection() ?>