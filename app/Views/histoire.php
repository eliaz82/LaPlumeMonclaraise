<section id="histoire">
    <div class="container py-5">
        <!-- Titre principal -->
        <h1 class="text-center fw-bold my-5 <?= esc($customTitleColor ?? 'custom-title-color', 'attr'); ?>">Histoire du Club de Sport</h1>

        <!-- Section Histoire -->
        <div class="row mt-5 align-items-center">
            <div class="col-md-6">
                <h2 class="mb-4 <?= esc($customTitleColor ?? 'custom-title-color', 'attr'); ?>">Notre histoire</h2>
                <ul class="list-unstyled">
                    <li class="mb-3">
                        <p><strong><i class="bi bi-calendar-event-fill text-warning"></i> Fondation:</strong> Le club a
                            été fondé en <time datetime="2000-01-01"><?= esc('janvier 2000'); ?></time>. Notre objectif était de créer une communauté de passionnés de sport qui apprécient le travail d'équipe, le dépassement de soi et le respect mutuel.</p>
                    </li>
                    <li class="mb-3">
                        <p><strong><i class="bi bi-trophy-fill text-success"></i> Principaux accomplissements:</strong> Au
                            fil des ans, le club a remporté plusieurs championnats régionaux et a été reconnu pour ses efforts de bénévolat et de service communautaire. Les membres ont également contribué à la croissance et au développement du club, ajoutant des installations et des équipements modernes.
                        </p>
                    </li>
                    <li class="mb-3">
                        <p><strong><i class="bi bi-lightning-fill text-danger"></i> Principaux défis:</strong> Nous avons
                            affronté divers défis, notamment les restrictions budgétaires, la concurrence et les changements
                            dans le calendrier des activités sportives. Grâce à la détermination et au travail d'équipe de
                            nos membres, nous avons surmonté ces obstacles et continué à prospérer.</p>
                    </li>
                </ul>
            </div>
            <div class="col-md-6 position-relative">
                <!-- Image avec l'effet de grisement -->
                <div class="custom-image-container overflow-hidden rounded shadow-lg position-relative">
                    <img src="<?= esc(base_url('image/bad.jpg'), 'attr'); ?>" alt="Club de sport" class="img-fluid rounded">
                    <div class="custom-overlay d-flex justify-content-center align-items-center">
                        <h5 class="text-white fw-bold">Découvrez notre passion pour le sport !</h5>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section Description -->
        <div class="row mt-5">
            <div class="col-md-12">
                <h3 class="mb-4 <?= esc($customTitleColor ?? 'custom-title-color', 'attr'); ?>">Description du club</h3>
                <p class="lead">Le club de sport est un lieu où les passionnés de sport peuvent se rencontrer pour
                    pratiquer, partager leurs connaissances et s'améliorer mutuellement. Nous valorisons le travail
                    d'équipe, le respect et la dévotion à la compétition éthique. En rejoignant le club, vous rejoignez
                    une
                    communauté dynamique et amicale qui vous encourage à atteindre vos objectifs sportifs.</p>
                <div class="text-center mt-4">
                    <a href="<?= esc(base_url('/fichier-inscription'), 'attr'); ?>" id="bouton-modifier-inscription" class="btn btn-modify">Rejoignez-nous</a>
                </div>
            </div>
        </div>
    </div>
</section>