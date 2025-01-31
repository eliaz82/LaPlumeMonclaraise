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
<!-- Modal de modification -->
<!-- Bouton "Modifier le logo" avec nouveau style -->
<button class="edit-button" data-bs-toggle="modal" data-bs-target="#modalModifierLogo" data-text="Modifier Logo">
    <svg class="edit-svgIcon" viewBox="0 0 512 512">
        <path d="M410.3 231l11.3-11.3-33.9-33.9-62.1-62.1L291.7 89.8l-11.3 11.3-22.6 22.6L58.6 322.9c-10.4 10.4-18 23.3-22.2 37.4L1 480.7c-2.5 8.4-.2 17.5 6.1 23.7s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L387.7 253.7 410.3 231zM160 399.4l-9.1 22.7c-4 3.1-8.5 5.4-13.3 6.9L59.4 452l23-78.1c1.4-4.9 3.8-9.4 6.9-13.3l22.7-9.1v32c0 8.8 7.2 16 16 16h32zM362.7 18.7L348.3 33.2 325.7 55.8 314.3 67.1l33.9 33.9 62.1 62.1 33.9 33.9 11.3-11.3 22.6-22.6 14.5-14.5c25-25 25-65.5 0-90.5L453.3 18.7c-25-25-65.5-25-90.5 0zm-47.4 168l-144 144c-6.2 6.2-16.4 6.2-22.6 0s-6.2-16.4 0-22.6l144-144c6.2-6.2 16.4-6.2 22.6 0s6.2 16.4 0 22.6z"></path>
    </svg>
</button>

  
<div class="modal fade" id="modalModifierLogo" tabindex="-1" aria-labelledby="modalModifierLogoLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form id="formulaire-modifier-logo" method="post" action="<?= url_to('logoUpdate') ?>"
        enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title text-primary" id="modalModifierLogoLabel">Modifier le logo</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="modifier-logo" class="form-label">Logo</label>
            <input type="file" class="form-control" id="modifier-logo" name="logo" accept="image/*"
              onchange="previewImage(event,'modifierLogoPreview')">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
          <button type="submit" class="btn btn-primary">Mettre à jour</button>
        </div>
      </form>
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
