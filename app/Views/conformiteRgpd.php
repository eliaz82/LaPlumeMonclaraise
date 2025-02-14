<?= $this->extend('layout') ?>

<?= $this->section('title') ?>
Conformité RGPD
<?= $this->endSection() ?>

<?= $this->section('css') ?>
<!-- Feuilles de style spécifiques à la page RGPD -->
<link rel="stylesheet" type="text/css" href="<?= base_url('css/main.css') ?>">
<link rel="stylesheet" type="text/css" href="<?= base_url('css/responsive.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('contenu') ?>
<div class="container my-5">
  <header class="text-center mb-4">
    <h1>Conformité RGPD</h1>
    <p class="text-muted">Dernière mise à jour : 10/02/2025</p>
  </header>

  <article>
    <p>
      Conformément au Règlement Général sur la Protection des Données (RGPD), nous nous engageons à protéger la vie privée de nos utilisateurs.
    </p>

    <h2 class="mt-4">Traitement des données du formulaire de contact</h2>
    <p>
      Notre site ne conserve aucune donnée personnelle. Le formulaire de contact est utilisé uniquement pour transmettre votre message directement à l'administrateur par e-mail. Les informations que vous fournissez (nom, adresse e-mail, message, etc.) ne sont pas enregistrées sur nos serveurs et sont utilisées strictement pour permettre une communication efficace.
    </p>
    <p>
      En utilisant notre formulaire de contact, vous consentez à ce que les informations saisies soient transmises directement à l'administrateur. Aucune donnée n'est stockée après l'envoi du message.
    </p>

    <h2 class="mt-4">Sécurité et confidentialité</h2>
    <p>
      Nous avons mis en place des mesures de sécurité pour protéger les informations que vous nous transmettez. Cependant, comme pour toute communication par e-mail, il existe un risque inhérent à la transmission de données sur Internet.
    </p>

    <h2 class="mt-4">Vos droits</h2>
    <p>
      Conformément au RGPD, vous disposez d'un droit d'accès, de rectification et de suppression des données vous concernant. Étant donné que nous ne stockons aucune donnée issue du formulaire de contact, ces droits ne s'appliquent pas dans ce cas précis.
    </p>

    <h2 class="mt-4">Contact</h2>
    <p>
      Pour toute question relative à la protection de vos données ou pour exercer vos droits, vous pouvez nous contacter à l'adresse suivante : <a href="mailto:laplumemonclaraise@outlook.com">laplumemonclaraise@outlook.com</a>.
    </p>
  </article>
</div>
<?= $this->endSection() ?>
