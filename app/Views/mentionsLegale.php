<?= $this->extend('layout') ?>

<?= $this->section('title') ?>
Mentions Légales
<?= $this->endSection() ?>

<?= $this->section('css') ?>
<!-- Feuilles de style spécifiques à la page (si nécessaire) -->
<link rel="stylesheet" type="text/css" href="<?= base_url('css/main.css') ?>">
<link rel="stylesheet" type="text/css" href="<?= base_url('css/responsive.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('contenu') ?>
<div class="container my-5">
  <header class="text-center mb-4">
    <h1>Mentions Légales</h1>
    <p class="text-muted">Dernière mise à jour : 10/02/2025</p>
  </header>

  <article>
    <p>
      Conformément à l’article 6 de la loi n° 2004-575 du 21 juin 2004 pour la confiance dans l'économie numérique, voici les informations légales du Site.
    </p>

    <h2 class="mt-4">1. Informations légales</h2>
    <ul>
      <li>
        <strong>Propriétaire du site :</strong>
        <ul>
          <li>Nom : Mourgues</li>
          <li>Email : <a href="mailto:laplumemonclaraise@outlook.com">laplumemonclaraise@outlook.com</a></li>
          <li>Téléphone : 07 82 17 69 70</li>
        </ul>
      </li>
      <li>
        <strong>Responsable de la publication :</strong>
        <ul>
          <li>Nom : Mourgues</li>
          <li>Email : <a href="mailto:laplumemonclaraise@outlook.com">laplumemonclaraise@outlook.com</a></li>
        </ul>
      </li>
      <li>
        <strong>Hébergeur :</strong>
        <ul>
          <li>Nom : OVH</li>
          <li>Adresse : 2, rue Kellermann, 59100 Roubaix</li>
          <li>Téléphone : 1007</li>
          <li>Site Web : <a href="https://www.ovhcloud.com/fr/" target="_blank">https://www.ovhcloud.com/fr/</a></li>
        </ul>
      </li>
    </ul>

    <h2 class="mt-4">2. Description du service</h2>
    <p>
      Le Site permet l’affichage des publications publiques de l’administrateur via l’API Graph de Facebook.
    </p>

    <h2 class="mt-4">3. Propriété intellectuelle</h2>
    <p>
      Tout le contenu du Site est protégé par le droit d’auteur. Toute reproduction ou diffusion non autorisée est interdite.
    </p>

    <h2 class="mt-4">4. Responsabilité</h2>
    <p>
      L’administrateur ne pourra être tenu responsable en cas de dysfonctionnement du Site ou de dommages causés par son utilisation.
    </p>

    <h2 class="mt-4">5. Liens hypertextes</h2>
    <p>
      Le Site peut contenir des liens vers des sites tiers. L’administrateur décline toute responsabilité quant à leur contenu.
    </p>

    <h2 class="mt-4">6. Loi applicable</h2>
    <p>
      Les présentes mentions légales sont régies par la loi française. En cas de litige, les tribunaux français seront compétents.
    </p>

    <h2 class="mt-4">7. Contact</h2>
    <p>
      Pour toute question relative aux mentions légales, contactez : <a href="mailto:laplumemonclaraise@outlook.com">laplumemonclaraise@outlook.com</a>.
    </p>
  </article>
</div>
<?= $this->endSection() ?>
