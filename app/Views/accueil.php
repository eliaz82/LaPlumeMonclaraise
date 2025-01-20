<?= $this->extend('layout') ?>
<?= $this->section('contenu') ?>
<div id="carousel-container" class="position-relative">
  <div id="carouselExampleAutoplaying" class="carousel slide" data-bs-interval="4500" data-bs-ride="carousel">
    <div class="carousel-inner">
      <div class="carousel-item active">
        <img src="image/noel.jpg" class="d-block w-100" alt="..." style="height: 400px; object-fit: cover; transition: transform 1s ease-in-out;">
      </div>
      <div class="carousel-item">
        <img src="image/terrain.jpg" class="d-block w-100" alt="..." style="height: 400px; object-fit: cover; transition: transform 1s ease-in-out;">
      </div>
      <div class="carousel-item">
        <img src="image/terrain2.jpg" class="d-block w-100" alt="..." style="height: 400px; object-fit: cover; transition: transform 1s ease-in-out;">
      </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Next</span>
    </button>
  </div>
  <div class="overlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 1000; background-color: rgba(0, 0, 0, 0.5);" onmouseover="this.style.backgroundColor='rgba(0, 0, 0, 0.1)'" onmouseout="this.style.backgroundColor='rgba(0, 0, 0, 0.5)'">
    <div class="d-flex justify-content-between align-items-center h-100 text-white">
      <div class="ms-3">
        <h2 class="fw-bold font-size-24" style="font-family: Arial, sans-serif;">La Plume Monclaraise</h2>
        <p class="font-size-18" style="font-family: Arial, sans-serif;">Un slogan de con</p>
      </div>
      <div class="me-3 text-end">
        <img src="<?= base_url($logo['logo']) ?>" class="img-fluid img-thumbnail rounded-circle" alt="..." style="width: 200px; height: 200px; object-fit: cover;">
        <div style="width: 200px; text-align: center;">
          <p class="font-size-18" style="font-family: Arial, sans-serif; word-wrap: break-word;">esplanade du lac 82230 Monclar-de-Quercy</p>
          <p class="font-size-18" style="font-family: Arial, sans-serif; word-wrap: break-word;">07 82 17 69 70</p>
          <p class="font-size-18" style="font-family: Arial, sans-serif; word-wrap: break-word;">Sport · Terrain de badminton</p>
        </div>




      </div>
    </div>
  </div>
</div>
<!-- Modal de modification -->
<button class="btn btn-warning btn-sm me-2 bouton-modifier-logo" data-bs-toggle="modal" data-bs-target="#modalModifierLogo">Modifier le logo</button>
<div class="modal fade" id="modalModifierLogo" tabindex="-1" aria-labelledby="modalModifierLogoLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form id="formulaire-modifier-logo" method="post" action="<?= url_to('logoUpdate') ?>" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title text-primary" id="modalModifierLogoLabel">Modifier le logo</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="modifier-logo" class="form-label">Logo</label>
            <input type="file" class="form-control" id="modifier-logo" name="logo" accept="image/*" onchange="previewImage(event,'modifierLogoPreview')">
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

<div class="container my-5">
  <div class="position-relative">
    <!-- Image de fond -->
    <img src="image/tee_shirt.jpg" alt="Image" class="img-fluid rounded"
      style="max-width: 60%; height: auto; margin-left: 30%;" id="image">

    <!-- Texte superposé avec la même taille que l'image -->
    <div class="position-absolute top-50 start-0 translate-middle-y bg-light p-4 rounded shadow"
      style="width: 60%; height: 100%; transform: translateX(30%) translateY(10%); display: flex; justify-content: center; align-items: center; opacity: 0.8;">
      <div class="text-center">
        <h1 class="fw-bold text-primary">Rejoignez l'aventure de La Plume Monclaraise</h1>
        <p>
          La Plume Monclaraise est bien plus qu'un simple club de badminton. C'est un lieu de rencontres, de défis et de
          passion pour tous ceux qui souhaitent pratiquer ce sport dans une ambiance conviviale et stimulante. Que vous
          soyez débutant ou confirmé, nous avons une place pour vous !
          Plongez dans l'univers du badminton avec nous et vivez des moments uniques, qu'ils soient compétitifs ou
          purement récréatifs.
        </p>
        <a href="<?= url_to('histoire') ?>" class="btn btn-primary">Lire plus</a>
      </div>
    </div>
  </div>
</div>






<?= $this->endSection() ?>