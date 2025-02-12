"<?= $this->extend('layout') ?>

<?= $this->section('css') ?>
<!-- FullCalendar CSS -->

<link rel="stylesheet" type="text/css" href="<?= base_url('css/carousel.css') ?>">
<link rel="stylesheet" type="text/css" href="<?= base_url('css/responsive.css') ?>">

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css" rel="stylesheet">
<?= $this->endSection() ?>

<?= $this->section('contenu') ?>

<div class="container mt-5">
    <div class="row">
        <?php if (!empty($posts)): ?>
            <div class="col-4">
                <!-- Conteneur du carousel avec flèches fixes en haut et en bas -->
                <div class="carousel-container-calendrier">
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

        <div class="col-12 col-md-8">
            <div id="calendar" class="text-end"
                style="max-width: 800px; width: 100%; margin-left: auto; margin-right: 0;">
            </div>
        </div>

    </div>
</div>


<div id="eventData"
    data-event-url="<?= esc(site_url('evenement'), 'attr') ?>"
    data-posts="<?= esc(json_encode($posts), 'attr') ?>"
    data-events="<?= esc(json_encode($events), 'attr') ?>">
</div>


<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- FullCalendar Scripts -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/locales/fr.js"></script>

<?= script_tag('js/eventCalendar.js') ?>

<?= $this->endSection() ?>"