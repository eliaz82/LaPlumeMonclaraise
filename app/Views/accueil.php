<?= $this->extend('layout') ?>
<?= $this->section('contenu') ?>
<div id="carouselExampleAutoplaying" class="carousel slide carousel-fade" data-bs-interval="3000"
  data-bs-ride="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="image/noel.jpg" class="d-block w-100" alt="..." style="height: 400px; object-fit: cover;">
    </div>
    <div class="carousel-item">
      <img src="image/terrain.jpg" class="d-block w-100" alt="..." style="height: 400px; object-fit: cover;">
    </div>
    <div class="carousel-item">
      <img src="image/terrain2.jpg" class="d-block w-100" alt="..." style="height: 400px; object-fit: cover;">
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
        <a href="#" class="btn btn-primary">Lire plus</a>
      </div>
    </div>
  </div>
</div>






<?= $this->endSection() ?>