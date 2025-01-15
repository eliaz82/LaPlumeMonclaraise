<?= $this->extend('layout') ?>
<?= $this->section('contenu') ?>
<div id="carouselExampleAutoplaying" class="carousel slide carousel-fade" data-bs-interval="3000" data-bs-ride="carousel">
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

<div class="container">
  <div class="row">
    <div class="col-md-6">
      <img src="image/tee_shirt.jpg" class="img-fluid">
    </div>
    <div class="col-md-6">
      <div class="bg-gray p-3 rounded" style="height: 100%;">
        <h1>Bienvenue</h1>
        <p>voila notre histoire.</p>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>