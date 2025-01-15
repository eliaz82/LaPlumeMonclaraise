<?= $this->extend('layout') ?>
<?= $this->section('contenu') ?>
<a href="<?= base_url('fichier-inscription?downloadPdf=true');?>">Télécharger le modèle d'inscription</a>
<?= $this->endSection() ?>