<?= $this->extend('layout') ?>

<?= $this->section('contenu') ?>
<?= view('histoire',['association' => $association]) ?>
<?= view('equipes', ['equipes' => $equipes]) ?>
<?= view('partenaires', ['partenaire' => $partenaire]) ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<?= script_tag('js/form-modifications.js') ?>
<?= $this->endSection() ?>