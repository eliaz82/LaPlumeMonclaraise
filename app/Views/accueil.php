<?= $this->extend('layout') ?>
<?= $this->section('contenu') ?>
<div id="carouselExampleAutoplaying" class="carousel slide" data-bs-interval="2000" data-bs-ride="carousel">
  <style>
    .carousel-item {
      opacity: 0;
      transition: opacity 0.5s ease;
    }

    .carousel-item.active,
    .carousel-item-next,
    .carousel-item-prev {
      opacity: 1;
    }
  </style>
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
<?= $this->endSection() ?>