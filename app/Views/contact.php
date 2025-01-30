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

        <div class="contact-container">
            <form class="contact-form" action="<?= route_to('contactSubmit'); ?>" method="post">
                <p id="contact-heading">Contactez-nous</p>

                <div class="contact-field">
                    <input autocomplete="off" placeholder="Nom" id="name" name="name" class="contact-input" type="text"
                        required>
                </div>

                <div class="contact-field">
                    <input autocomplete="off" placeholder="Téléphone" id="phone" name="phone" class="contact-input"
                        type="text" required>
                </div>

                <div class="contact-field">
                    <input autocomplete="off" placeholder="Email" id="email" name="email" class="contact-input"
                        type="email" required>
                </div>

                <div class="contact-field">
                    <input autocomplete="off" placeholder="Objet" id="subject" name="subject" class="contact-input"
                        type="text" required>
                </div>

                <div class="contact-field">
                    <textarea autocomplete="off" placeholder="Message" id="message" name="message" rows="5"
                        class="contact-textarea" required></textarea>
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
                <button id="bouton-modifier-contact" class="btn btn-primary mb-3" data-bs-toggle="modal"
                    data-bs-target="#modalModifierContact">
                    <i class="fa fa-edit" aria-hidden="true"></i> Modifier e-mail de contact
                </button>
            </form>
        </div>
    </div>

</div>

<?= $this->endSection() ?>