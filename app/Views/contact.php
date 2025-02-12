<?= $this->extend('layout') ?>
<?= $this->section('title') ?>
contact
<?= $this->endSection() ?>
<?= $this->section('css') ?>
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="<?= base_url('css/buttons.css') ?>">
<link rel="stylesheet" type="text/css" href="<?= base_url('css/contact.css') ?>">
<link rel="stylesheet" type="text/css" href="<?= base_url('css/responsive.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('contenu') ?>

<div class="container">

    <!-- Modal de modification de l'e-mail de contact -->
    <div class="modal fade" id="modalModifierContact" tabindex="-1" aria-labelledby="modalModifierContactLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="<?= route_to('contactUpdate'); ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="modal-header">
                        <h5 class="modal-title text-primary" id="modalModifierContactLabel">Modifier l'e-mail de contact
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="mailContact" class="form-label">E-mail de la plume monclaraise :</label>
                            <input type="email" id="mailContact" name="mailContact" class="form-control"
                                value="<?= esc($association['mailContact']) ?>" required>
                        </div>
                        <input type="hidden" id="idAssociation" name="idAssociation"
                            value="<?= esc($association['idAssociation']) ?>">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Modifier</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Contact Form -->
    <div class="contact-wrapper">
        <div class="contact-map" id="map" style="width: 100%; height: 600px;"></div>
        <div id="map-container" data-logo="<?= esc(base_url(getAssociationLogo())); ?>" data-lat="<?= esc($lat); ?>"
            data-lon="<?= esc($lon); ?>" data-adresse="<?= esc($association['adresse']); ?>">
        </div>

        <div class="contact-container">
            <form class="contact-form" action="<?= route_to('contactSubmit'); ?>" method="post">
                <?= csrf_field() ?>
                <p id="contact-heading">Contactez-nous</p>

                <div class="contact-field">
                    <input autocomplete="off" placeholder="Nom" id="nom" name="nom" class="contact-input" type="text"
                        value="<?= esc(old('nom')); ?>" required>
                </div>

                <div class="contact-field">
                    <input autocomplete="off" placeholder="Téléphone" id="phone" name="phone" class="contact-input"
                        type="text" value="<?= esc(old('phone')); ?>" required>
                </div>

                <div class="contact-field">
                    <input autocomplete="off" placeholder="Email" id="email" name="email" class="contact-input"
                        type="email" value="<?= esc(old('email')); ?>" required>
                </div>

                <div class="contact-field">
                    <input autocomplete="off" placeholder="Objet" id="subject" name="subject" class="contact-input"
                        type="text" value="<?= esc(old('subject')); ?>" required>
                </div>

                <div class="contact-field">
                    <textarea autocomplete="off" placeholder="Message" id="message" name="message" rows="5"
                        class="contact-textarea" required><?= esc(old('message')); ?></textarea>
                </div>

                <div class="recaptcha-container">
                    <div class="g-recaptcha" data-sitekey="6LdtAcgqAAAAAPhL5TB75gKZXQ8yn64CHmr09t4E"></div>
                </div>

                <div class="button-container">
                    <button class="btn-unique">
                        <div class="icon-wrapper-outer">
                            <div class="icon-wrapper-inner">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                                    <path fill="none" d="M0 0h24v24H0z"></path>
                                    <path fill="currentColor"
                                        d="M1.946 9.315c-.522-.174-.527-.455.01-.634l19.087-6.362c.529-.176.832.12.684.638l-5.454 19.086c-.15.529-.455.547-.679.045L12 14l6-8-8 6-8.054-2.685z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        <span class="btn-label">Envoyer</span>
                    </button>
                </div>

            </form>
        </div>
    </div>

</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<!-- Google reCAPTCHA -->
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<!-- Script personnalisé pour la carte -->
<?= script_tag('js/leaflet-map.js') ?>
<?= $this->endSection() ?>