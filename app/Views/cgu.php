<?= $this->extend('layout') ?>

<?= $this->section('title') ?>
CGU - Conditions Générales d'Utilisation
<?= $this->endSection() ?>

<?= $this->section('css') ?>
<!-- Tu peux ajouter ici des feuilles de style spécifiques à la page CGU si nécessaire -->
<link rel="stylesheet" type="text/css" href="<?= base_url('css/main.css') ?>">
<link rel="stylesheet" type="text/css" href="<?= base_url('css/responsive.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('contenu') ?>
<div class="container my-5">
    <header class="text-center mb-4">
        <h1>Conditions Générales d'Utilisation (CGU)</h1>
        <p class="text-muted">Dernière mise à jour : 10/02/2025</p>
    </header>

    <article>
        <p>
            En accédant au site <strong>Laplumemonclaraise.fr</strong> , vous acceptez sans réserve les présentes Conditions Générales d'Utilisation (CGU) ainsi que toutes les lois et réglementations applicables. Si vous n'acceptez pas ces conditions, veuillez ne pas utiliser ce Site.
        </p>

        <h2 class="mt-4">1. Objet du Site</h2>
        <p>
            Le Site permet à l'administrateur d'afficher des publications publiques issues de son propre compte Facebook via l'API Graph de Facebook. Ces publications sont affichées en temps réel sur le Site.
        </p>

        <h2 class="mt-4">2. Acceptation et modification des CGU</h2>
        <p>
            Les présentes CGU sont susceptibles d’être modifiées à tout moment par l’administrateur du Site. Toute modification sera publiée sur cette page et entrera en vigueur dès sa mise en ligne. L’utilisation continue du Site après ces modifications vaut acceptation des nouvelles CGU.
        </p>

        <h2 class="mt-4">3. Accès au Site</h2>
        <p>
            L'accès au Site est gratuit et accessible à toute personne disposant d'une connexion Internet. L’administrateur se réserve le droit de suspendre, interrompre ou limiter l'accès au Site à tout moment, sans préavis, notamment pour des raisons de maintenance ou en cas de force majeure.
        </p>

        <h2 class="mt-4">4. Utilisation de l'API Graph de Facebook</h2>
        <p>
            Le Site utilise l'API Graph de Facebook pour récupérer et afficher uniquement les publications publiques de l'administrateur.
        </p>
        <ul>
            <li><strong>Données collectées :</strong> Seules les publications publiques de l’administrateur sur Facebook sont utilisées.</li>
            <li><strong>Aucune collecte de données personnelles :</strong> Le Site ne collecte aucune donnée personnelle d'autres utilisateurs de Facebook ni des visiteurs du Site.</li>
        </ul>

        <h2 class="mt-4">5. Obligations des utilisateurs</h2>
        <p>
            Les utilisateurs du Site s'engagent à respecter les lois et réglementations en vigueur et à ne pas utiliser le Site à des fins frauduleuses, malveillantes ou portant atteinte aux droits de tiers.
        </p>

        <h2 class="mt-4">6. Propriété intellectuelle</h2>
        <p>
            Tous les contenus présents sur le Site (textes, images, vidéos, publications Facebook, etc.) sont protégés par le droit d’auteur et restent la propriété exclusive de l'administrateur, sauf mention contraire.
        </p>
        <ul>
            <li><strong>Usage personnel et non commercial :</strong> Les utilisateurs peuvent partager ou reproduire les contenus du Site uniquement à des fins personnelles et non commerciales, sous réserve de mentionner la source et de ne pas altérer les informations.</li>
            <li><strong>Interdiction d’exploitation commerciale :</strong> Toute utilisation à des fins commerciales ou toute modification du contenu sans autorisation écrite préalable de l'administrateur est strictement interdite.</li>
        </ul>

        <h2 class="mt-4">7. Responsabilité</h2>
        <p>
            L’administrateur met tout en œuvre pour assurer l’exactitude des informations fournies, mais ne peut garantir l’absence d’erreurs ou d’interruptions.
        </p>
        <ul>
            <li>L’administrateur ne pourra être tenu responsable des dommages directs ou indirects liés à l’utilisation du Site, notamment en cas de perte de données, de bénéfices ou d’impossibilité d’accéder au Site.</li>
            <li>Le Site peut contenir des liens hypertextes vers des sites tiers. L’administrateur décline toute responsabilité quant au contenu de ces sites.</li>
        </ul>

        <h2 class="mt-4">8. Signalement de contenu illicite</h2>
        <p>
            Si un utilisateur estime qu’un contenu présent sur le Site porte atteinte à ses droits (atteinte à la vie privée, diffamation, contrefaçon, etc.), il peut contacter l’administrateur à l’adresse suivante : <a href="mailto:laplumemonclaraise@outlook.com">laplumemonclaraise@outlook.com</a>.
        </p>

        <h2 class="mt-4">9. Contact</h2>
        <p>
            Pour toute question relative aux CGU, veuillez contacter l'administrateur à l'adresse suivante : <a href="mailto:laplumemonclaraise@outlook.com">laplumemonclaraise@outlook.com</a>.
        </p>
    </article>
</div>
<?= $this->endSection() ?>