<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" type="image/png" href="/favicon.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="<?= base_url('css/main.css') ?>">
</head>

<style>
</style>
<nav>
  <ul>
    <li><a href="#">Accueil</a></li>
    <li>
      <a href="#">L'Association</a>
      <ul>
        <li><a href="#">L'Équipe</a></li>
        <li><a href="#">L'Histoire</a></li>
        <li><a href="#">Partenaires</a></li>
      </ul>
    </li>
    <li><a href="#">Calendrier</a></li>
    <li><a href="#">Inscription</a></li>
    <li><a href="#">Contact</a></li>
  </ul>
</nav>
<?= $this->renderSection('contenu') ?>