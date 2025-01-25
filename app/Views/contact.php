<?= $this->extend('layout') ?>
<?= $this->section('contenu') ?>


<div class="container">
    <h2 class="text-center mb-4">Formulaire de Contact</h2>

    <!-- Contact update -->
    <!-- Bouton pour ouvrir le modal -->
    <button id="bouton-modifier-contact" class="btn btn-primary mb-3" data-bs-toggle="modal"
        data-bs-target="#modalModifierContact">
        <i class="fa fa-edit" aria-hidden="true"></i> Modifier e-mail de contact
    </button>


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
    <form action="<?= route_to('contactSubmit'); ?>" method="post"
        class="p-4 border border-light rounded shadow-lg bg-white">
        <div class="mb-3">
            <label for="name" class="form-label">Nom:</label>
            <input type="text" id="name" name="name" class="form-control" placeholder="Votre nom" required>
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label">Téléphone:</label>
            <input type="text" id="phone" name="phone" class="form-control" placeholder="Votre téléphone" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" id="email" name="email" class="form-control" placeholder="Votre email" required>
        </div>

        <div class="mb-3">
            <label for="subject" class="form-label">Objet:</label>
            <input type="text" id="subject" name="subject" class="form-control" placeholder="Objet de votre message"
                required>
        </div>

        <div class="mb-3">
            <label for="message" class="form-label">Message:</label>
            <textarea id="message" name="message" rows="5" class="form-control" placeholder="Votre message"
                required></textarea>
        </div>

        <!-- boutton contact envoyer -->
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

    </form>
</div>

<?= $this->endSection() ?>