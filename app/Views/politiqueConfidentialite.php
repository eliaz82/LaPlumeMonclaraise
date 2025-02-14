<?= $this->extend('layout') ?>

<?= $this->section('title') ?>
Politique de Confidentialité
<?= $this->endSection() ?>

<?= $this->section('css') ?>
<!-- Feuilles de style spécifiques à la page (si nécessaire) -->
<link rel="stylesheet" type="text/css" href="<?= base_url('css/main.css') ?>">
<link rel="stylesheet" type="text/css" href="<?= base_url('css/responsive.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('contenu') ?>
<div class="container my-5">
    <header class="text-center mb-4">
        <h1>Politique de Confidentialité</h1>
        <p class="text-muted">Dernière mise à jour : 10/02/2025</p>
    </header>

    <article>
        <p>
            Cette Politique de Confidentialité a pour objectif d’informer les utilisateurs sur la gestion des données collectées, leur utilisation et leur protection.
        </p>

        <h2 class="mt-4">1. Données collectées</h2>
        <p>
            Le Site ne collecte aucune donnée personnelle des utilisateurs. Les seules données collectées sont :
        </p>
        <ul>
            <li>Les publications publiques de l’administrateur sur Facebook, affichées sur le Site via l’API Graph.</li>
            <li>Les cookies, si activés par l’utilisateur, pour améliorer l’expérience de navigation.</li>
        </ul>

        <h2 class="mt-4">2. Utilisation des données</h2>
        <p>
            Les données collectées sont utilisées uniquement pour afficher les publications Facebook de l’administrateur sur le Site. Aucune donnée personnelle n’est utilisée à d’autres fins.
        </p>

        <h2 class="mt-4">3. Cookies</h2>
        <p>
            Le Site utilise des cookies pour améliorer l'expérience utilisateur. Les cookies peuvent être désactivés via les paramètres du navigateur.
        </p>

        <h2 class="mt-4">4. Sécurité des données</h2>
        <p>
            Le Site met en place des mesures de sécurité pour protéger les données affichées contre toute modification, perte ou accès non autorisé.
        </p>

        <h2 class="mt-4">5. Conformité au RGPD</h2>
        <p>
            Le Site respecte les principes du Règlement Général sur la Protection des Données (RGPD). Toutefois, n’ayant pas de collecte de données personnelles, les droits d’accès, de rectification ou de suppression ne s’appliquent qu’aux éventuelles données collectées via les cookies.
        </p>

        <h2 class="mt-4">6. Contact</h2>
        <p>
            Pour toute question concernant la Politique de Confidentialité, veuillez contacter <a href="mailto:laplumemonclaraise@outlook.com">laplumemonclaraise@outlook.com</a>.
        </p>
    </article>
</div>
<?= $this->endSection() ?>