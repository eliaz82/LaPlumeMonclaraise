<?= $this->extend('layout') ?>
<?= $this->section('contenu') ?>

<div id="carousel-container" class="position-relative">
  <div id="carouselExampleAutoplaying" class="carousel slide" data-bs-interval="4500" data-bs-ride="carousel">
    <div class="carousel-inner">
      <div class="carousel-item active">
        <img src="image/test.jpg" class="d-block w-100" alt="..."
          style="height: 400px; object-fit: cover; transition: transform 1s ease-in-out;">
      </div>
      <div class="carousel-item">
        <img src="image/test1.jpg" class="d-block w-100" alt="..."
          style="height: 400px; object-fit: cover; transition: transform 1s ease-in-out;">
      </div>
      <div class="carousel-item">
        <img src="image/test2.jpg" class="d-block w-100" alt="..."
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
          La Plume Monclaraise
        </h2>

        <p class="font-size-18" style="font-family: Arial, sans-serif;">Nouveau club de badminton loisirs dans la
          commune de Monclar de Quercy.<br> Ouvert à tous, de 8 à 88 ans</p>
        <p class="font-size-18" style="font-family: Arial, sans-serif;">Sport · Terrain de badminton</p>
      </div>
      <div class="me-3 text-end">
        <img src="<?= base_url(getAssociationLogo()); ?>" class="img-fluid img-thumbnail rounded-circle" alt="..."
          style="width: 200px; height: 200px; object-fit: cover; border: 2px solid black;">

        <div class="col-md-4" style="width: 200px; text-align: center;">
          <p>
            <i class="bi bi-geo-alt"></i> Adresse : esplanade du lac 82230 Monclar-de-Quercy<br>
            <i class="bi bi-envelope"></i> Email : contact@club.fr<br>
            <i class="bi bi-telephone"></i> Téléphone : 07 82 17 69 70
          </p>
          <div>
            <a href="#" class="text-white me-2"><i class="bi bi-facebook"></i></a>
            <a href="#" class="text-white me-2"><i class="bi bi-twitter"></i></a>
            <a href="#" class="text-white"><i class="bi bi-instagram"></i></a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="container my-5" style="padding-bottom: 100px;">
  <div class="position-relative">
    <!-- Image de fond -->
    <img src="image/tee_shirt.jpg" alt="Image" class="img-fluid rounded"
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
        <a href="<?= url_to('FusionAssociation') ?>#histoire" class="btn btn-primary">Lire plus</a>
      </div>
    </div>
  </div>
</div>


<button id="refreshButton" data-refresh-url="<?= site_url('facebook/refresh'); ?>">Rafraîchir</button>
<?php if (!empty($posts)): ?>
  <div class="carousel">
    <div class="carousel-wrapper">
      <?php
      // Limiter le nombre de publications à 10
      $limited_posts = array_slice($posts, 0, 10); // Récupérer les 10 premières publications
      foreach ($limited_posts as $post): ?>
        <div class="carousel-cell">
          <div class="fb-post" data-href="<?php echo $post['permalink_url']; ?>" data-width="500"></div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
<?php else: ?>
  <p>Aucune publication trouvée.</p>
<?php endif; ?>

<?= $this->endSection() ?>