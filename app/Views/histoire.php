<?= $this->extend('layout')?>
<?= $this->section('contenu')?>

<div class="container">
    <h1 class="text-center text-primary my-5">Histoire du Club de Sport</h1>
    
    <div class="row mt-5">
        <div class="col-md-6">
            <h2 class="mb-3 text-info">Notre histoire</h2>
            <p><strong>Fondation:</strong> Le club a été fondé en <time datetime="2000-01-01">janvier 2000</time>. Notre objectif était de créer une communauté de passionnés de sport qui apprécient le travail d'équipe, le dépassement de soi et le respect mutuel.</p>
            <p><strong>Principaux accomplissements:</strong> Au fil des ans, le club a remporté plusieurs championnats régionaux et a été reconnu pour ses efforts de bénévolat et de service communautaire. Les membres ont également contribué à la croissance et au développement du club, ajoutant des installations et des équipements modernes.</p>
            <p><strong>Principaux défis:</strong> Nous avons affronté divers défis, notamment les restrictions budgétaires, la concurrence et les changements dans le calendrier des activités sportives. Cependant, grâce à la détermination et au travail d'équipe de nos membres, nous avons surmonté ces obstacles et continué à prospérer.</p>
        </div>
        <div class="col-md-6">
            <img src="image/bad.jpg" alt="Club de sport" class="img-fluid rounded shadow-lg">
        </div>
    </div>
    
    <div class="row mt-5">
        <div class="col-md-12">
            <h3 class="mb-3 text-success">Description du club</h3>
            <p>Le club de sport est un lieu où les passionnés de sport peuvent se rencontrer pour pratiquer, partager leurs connaissances et s'améliorer mutuellement. Nous valorisons le travail d'équipe, le respect et la dévotion à la compétition éthique. En rejoignant le club, vous rejoignez une communauté dynamique et amicale qui vous encourage à atteindre vos objectifs sportifs.</p>
        </div>
    </div>
</div>

<?= $this->endSection()?>
