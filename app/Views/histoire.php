<section id="histoire">
    <div class="container py-5">
        <!-- Titre principal -->
        <h1 class="text-center fw-bold my-5 <?= esc($customTitleColor ?? 'custom-title-color', 'attr'); ?>">Histoire du
            Club de Sport</h1>

        <!-- Section Histoire -->
        <div class="row mt-5 align-items-center">

            <div class="col-md-6">
                <h2 class="mb-4 <?= esc($customTitleColor ?? 'custom-title-color', 'attr'); ?>">Notre histoire</h2>
                <ul class="list-unstyled">
                    <li class="mb-3">
                        <p>
                            <strong><i class="bi bi-people-fill text-info"></i> Lancement:</strong>
                            A l'origine, La Plume Monclaraise c'est le projet de 2 joueurs passionnés du badminton qui
                            se connaissent depuis 20 ans et qui se retrouve à vivre dans le même village. Animés d'une
                            envie de pouvoir jouer "local", nous avons lancé cette association dans le but de faire
                            découvrir ce sport beaucoup trop sous-estimé et de partager le plaisir de s'affronter sur
                            les terrains.
                        </p>
                    </li>
                    <li class="mb-3">
                        <p>
                            <strong><i class="bi bi-person-fill text-primary"></i> Nos Pilotes:</strong>
                            Vincent a une expérience assez riche en terme d'animation et d'encadrement, il apporte le
                            côté "pep's" et décontracté aux entraînements tandis que Cindy pose des bases carrées, ayant
                            déjà été impliqué dans la vie associative de précédents clubs de badminton.
                        </p>
                    </li>
                    <li class="mb-3">
                        <p>
                            <strong><i class="bi bi-heart-fill text-danger"></i> Notre Communauté:</strong>
                            Avec une capacité de jeu de 16 personnes en simultané, le club se retrouve avec 86
                            adhérents, une bienveillance sans faille entre les joueurs et un beau maillot pour
                            représenter fièrement les couleurs de ce nouveau club. La Plume Monclaraise est un lieu de
                            jeu et de partage, c'est une petite communauté, une famille où chacun y trouve sa place :)
                        </p>
                    </li>
                </ul>
            </div>

            <div class="col-md-6 position-relative">
                <!-- Image avec l'effet de grisement -->
                <div class="custom-image-container overflow-hidden rounded shadow-lg position-relative">
                    <img src="<?= esc(base_url('image/bad.jpg'), 'attr'); ?>" alt="Club de sport"
                        class="img-fluid rounded">
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
                    une communauté dynamique et amicale qui vous encourage à atteindre vos objectifs sportifs.</p>

                <div class="text-center mt-4">
                    <?php if (isset($association['fichierInscriptionVisible']) && $association['fichierInscriptionVisible'] == 0): ?>
                        <div class="alert alert-danger text-center fw-bold shadow-sm mb-3" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            Les inscriptions sont actuellement fermées. Restez connectés pour la prochaine ouverture !
                        </div>
                    <?php endif; ?>
                    <a href="<?= esc(base_url('/fichier-inscription'), 'attr'); ?>" id="bouton-rejoindre"
                        class="btn btn-modify">Rejoignez-nous</a>
                </div>
            </div>
        </div>
    </div>
</section>