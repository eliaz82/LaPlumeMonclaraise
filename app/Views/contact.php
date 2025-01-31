<?= $this->extend('layout') ?>
<?= $this->section('contenu') ?>


<div class="container">

    <!-- Modal de modification de l'e-mail de contact -->
    <div class="modal fade" id="modalModifierContact" tabindex="-1" aria-labelledby="modalModifierContactLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="<?= route_to('contactUpdate'); ?>" method="post">
                    <div class="modal-header">
                        <h5 class="modal-title text-primary" id="modalModifierContactLabel">Modifier l'e-mail de contact
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="mailContact" class="form-label">E-mail de la plume monclaraise :</label>
                            <input type="email" id="mailContact" name="mailContact" class="form-control"
                                value="<?= $association['mailContact'] ?>" required>
                        </div>
                        <input type="hidden" id="idAssociation" name="idAssociation"
                            value="<?= $association['idAssociation'] ?>">
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
        <div class="contact-map" id="map" style="width: 100%; height: 300px;"></div>
        <div id="map-container" data-logo="<?= base_url(getAssociationLogo()); ?>"></div>


        <div class="contact-container">
            <form class="contact-form" action="<?= route_to('contactSubmit'); ?>" method="post">
                <p id="contact-heading">Contactez-nous</p>

                <div class="contact-field">
                    <input autocomplete="off" placeholder="Nom" id="nom" name="nom" class="contact-input" type="text"
                        value="<?= old('name'); ?>">
                </div>

                <div class="contact-field">
                    <input autocomplete="off" placeholder="Téléphone" id="phone" name="phone" class="contact-input"
                        type="text" value="<?= old('phone'); ?>" required>
                </div>

                <div class="contact-field">
                    <input autocomplete="off" placeholder="Email" id="email" name="email" class="contact-input"
                        type="email" value="<?= old('email'); ?>" required>
                </div>

                <div class="contact-field">
                    <input autocomplete="off" placeholder="Objet" id="subject" name="subject" class="contact-input"
                        type="text" value="<?= old('subject'); ?>" required>
                </div>

                <div class="contact-field">
                    <textarea autocomplete="off" placeholder="Message" id="message" name="message" rows="5"
                        class="contact-textarea" required><?= old('message'); ?></textarea>
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

                
                <button class="edit-button" data-bs-toggle="modal" data-bs-target="#modalModifierContact"
                    data-text="Modifier votre e-mail">
                    <svg class="edit-svgIcon" viewBox="0 0 512 512">
                        <path
                            d="M410.3 231l11.3-11.3-33.9-33.9-62.1-62.1L291.7 89.8l-11.3 11.3-22.6 22.6L58.6 322.9c-10.4 10.4-18 23.3-22.2 37.4L1 480.7c-2.5 8.4-.2 17.5 6.1 23.7s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L387.7 253.7 410.3 231zM160 399.4l-9.1 22.7c-4 3.1-8.5 5.4-13.3 6.9L59.4 452l23-78.1c1.4-4.9 3.8-9.4 6.9-13.3l22.7-9.1v32c0 8.8 7.2 16 16 16h32zM362.7 18.7L348.3 33.2 325.7 55.8 314.3 67.1l33.9 33.9 62.1 62.1 33.9 33.9 11.3-11.3 22.6-22.6 14.5-14.5c25-25 25-65.5 0-90.5L453.3 18.7c-25-25-65.5-25-90.5 0zm-47.4 168l-144 144c-6.2 6.2-16.4 6.2-22.6 0s-6.2-16.4 0-22.6l144-144c6.2-6.2 16.4-6.2 22.6 0s6.2 16.4 0 22.6z">
                        </path>
                    </svg>
                </button>
            </form>
        </div>
    </div>

</div>

<?= $this->endSection() ?>