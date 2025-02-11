<?= $this->extend('layout') ?>
<?= $this->section('css') ?>
<!-- Flickity CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flickity/2.2.2/flickity.min.css">
<?= $this->endSection() ?>

<?= $this->section('contenu') ?>

<div id="carousel-container" class="position-relative">
  <div id="carouselExampleAutoplaying" class="carousel slide" data-bs-interval="4500" data-bs-ride="carousel">
    <div class="carousel-inner">
      <div class="carousel-item active">
        <img src="<?= esc(base_url('image/test.jpg'), 'attr'); ?>" class="d-block w-100" alt="Image 1"
          style="height: 400px; object-fit: cover; transition: transform 1s ease-in-out;">
      </div>
      <div class="carousel-item">
        <img src="<?= esc(base_url('image/test1.jpg'), 'attr'); ?>" class="d-block w-100" alt="Image 2"
          style="height: 400px; object-fit: cover; transition: transform 1s ease-in-out;">
      </div>
      <div class="carousel-item">
        <img src="<?= esc(base_url('image/test2.jpg'), 'attr'); ?>" class="d-block w-100" alt="Image 3"
          style="height: 400px; object-fit: cover; transition: transform 1s ease-in-out;">
      </div>
    </div>
  </div>
  <div class="overlay"
    style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 1000; background-color: rgba(0, 0, 0, 0.5);"
    onmouseover="this.style.backgroundColor='rgba(0, 0, 0, 0.1)'"
    onmouseout="this.style.backgroundColor='rgba(0, 0, 0, 0.5)'">
    <div class="d-flex justify-content-between align-items-center h-100 text-white">
      <div class="ms-3">
        <h2 class="fw-bold" style="font-family: 'Playfair Display', serif; font-size: 45px; color: #fff;">
          LA PLUME MONCLARAISE
        </h2>

        <p class="font-size-18" style="font-family: Arial, sans-serif;">Nouveau club de badminton loisirs dans la
          commune de Monclar de Quercy.<br> Ouvert à tous, de 8 à 88 ans</p>
        <p class="font-size-18" style="font-family: Arial, sans-serif;">Sport · Terrain de badminton</p>
      </div>
      <div class="me-3 text-end">
        <img src="<?= esc(base_url(getAssociationLogo()), 'attr'); ?>"
          class="img-fluid img-thumbnail rounded-circle"
          alt="Logo"
          style="width: 200px; height: 200px; object-fit: cover; border: 2px solid black;">

        <!-- Icônes avec pop-up au survol -->
        <div class="mt-2 d-flex justify-content-center gap-4">

          <!-- Email -->
          <div class="icon-container">
            <i class="bi bi-envelope-fill fs-3 text-primary"></i>
            <div class="popup-info">
              <span class="emailDisplay"><?= esc($email ?? 'Email non défini'); ?></span>
            </div>
          </div>

          <!-- Adresse -->
          <div class="icon-container">
            <i class="bi bi-geo-alt-fill fs-3 text-primary"></i>
            <div class="popup-info">
              <span class="adresseDisplay"><?= esc($localisation['adresse'] ?? 'Adresse non définie'); ?></span>
            </div>
          </div>

          <!-- Téléphone -->
          <div class="icon-container">
            <i class="bi bi-telephone-fill fs-3 text-primary"></i>
            <div class="popup-info">
              <span class="telephoneDisplay"><?= esc($telephone ?? 'Téléphone non défini'); ?></span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<div class="container my-5" style="padding-bottom: 100px;">
  <div class="position-relative">
    <!-- Image de fond -->
    <img src="<?= esc(base_url('image/tee_shirt.jpg'), 'attr'); ?>" alt="Image" class="img-fluid rounded"
      style="max-width: 60%; height: auto; margin-left: 45%;" id="image">

    <!-- Texte superposé avec la même taille que l'image -->
    <div class="position-absolute top-50 start-0 bg-light p-4 rounded shadow"
      style="width: 60%; height: 100%; transform: translateX(-13%) translateY(-30%); display: flex; justify-content: center; align-items: center; opacity: 0.8; top: calc(50% + 5%);">
      <div class="text-center">
        <h1 class="fw-bold text-primary">Rejoignez l'aventure de La Plume Monclaraise</h1>
        <p>
          La Plume Monclaraise est bien plus qu'un simple club de badminton. C'est un lieu de rencontres, de défis et de
          passion pour tous ceux qui souhaitent pratiquer ce sport dans une ambiance conviviale et stimulante. Que vous
          soyez débutant ou confirmé, nous avons une place pour vous !
          Plongez dans l'univers du badminton avec nous et vivez des moments uniques, qu'ils soient compétitifs ou
          purement récréatifs.
        </p>
        <a href="<?= esc(url_to('FusionAssociation') . '#histoire', 'attr'); ?>" class="btn btn-primary">Lire plus</a>
      </div>
    </div>
  </div>
</div>

<button id="refreshButton" data-refresh-url="<?= esc(site_url('facebook/refresh'), 'attr'); ?>" class="btn btn-light">
  <i class="bi bi-arrow-clockwise" style="color: #007bff;"></i> Rafraîchir
</button>


<?php if (!empty($posts)): ?>

  <div class="carousel-container" style="background: linear-gradient(135deg, #0f4c75, #3282b8);padding: 1rem;">
    <div class="carousel" data-flickity='{ "wrapAround": true, "autoPlay": 3000 }'>
      <?php
      // Limiter le nombre de publications à 10
      $limited_posts = array_slice($posts, 0, 10);
      foreach ($limited_posts as $post): ?>
        <div class="carousel-cell">
          <div class="fb-post-container">
            <div class="fb-post" data-href="<?= esc($post['permalink_url'], 'attr'); ?>" data-width="500"></div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

<?php else: ?>
  <p>Aucune publication trouvée.</p>
<?php endif; ?>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<div id="fb-root"></div>
<script async defer crossorigin="anonymous"
  src="https://connect.facebook.net/fr_FR/sdk.js#xfbml=1&version=v22.0&appId=603470049247384"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flickity/2.2.2/flickity.pkgd.min.js"></script>
<?= $this->endSection() ?>
