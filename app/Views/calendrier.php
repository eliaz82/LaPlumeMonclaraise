<?= $this->extend('layout') ?>
<?= $this->section('contenu') ?>

<div class="container mt-5">
    <div class="row">
        <?php if (!empty($posts)): ?>
            <div class="col-4">
                <!-- Conteneur du carousel avec flèches fixes en haut et en bas -->
                <div class="carousel-container" style="position: relative; overflow: hidden; height: 500px;">
                    <!-- Flèche du haut -->
                    <button id="scroll-up" class="carousel-nav-up">
                        <i class="fas fa-chevron-up"></i>
                    </button>
                    <!-- Le carousel -->
                    <div class="carousel">
                        <!-- Les éléments du carousel (posts avec images cliquables) vont ici -->
                    </div>
                    <!-- Conteneur pour zoomer sur l'image -->
                    <div id="zoom-container" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; 
                        background-color: rgba(0, 0, 0, 0.8); justify-content: center; align-items: center;">
                        <img id="zoomed-image" src="" alt="Zoomed Image" style="max-width: 90%; max-height: 90%;" />
                        <button onclick="closeZoom()"
                            style="position: absolute; top: 20px; right: 20px; color: white; font-size: 30px; background: none; border: none;">X</button>
                    </div>
                    <!-- Flèche du bas -->
                    <button id="scroll-down" class="carousel-nav-down">
                        <i class="fas fa-chevron-down"></i>
                    </button>
                </div>
            </div>
        <?php else: ?>
            <div class="col-12 text-center">
                <p>Aucun événement trouvé</p>
            </div>
        <?php endif; ?>

        <div class="col-8">
            <div id="calendar" class="text-end"
                style="max-width: 800px; width: 100%; margin-left: auto; margin-right: 0;"></div>
        </div>
    </div>
</div>


<style>
    .past-event {
        opacity: 0.5;
        /* Tu peux aussi modifier la couleur de fond ou d'autres styles, par exemple : */
        background-color: #d9534f !important;
    }
</style>

<div id="eventData" data-event-url="<?= site_url('evenement') ?>"
    data-posts="<?= htmlspecialchars(json_encode($posts)) ?>"
    data-events="<?= htmlspecialchars(json_encode($events)) ?>">
</div>

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/locales/fr.js"></script>
<?= script_tag('js/eventCalendar.js') ?>
<?= $this->endSection() ?>