<?= $this->extend('layout') ?>
<?= $this->section('contenu') ?>


<div class="container">
    <!-- Success Message -->
    <?php if (session('success')): ?>
        <div class="alert alert-success">
            <?= session('success'); ?>
        </div>
    <?php endif; ?>


    <!-- Error Message -->
    <?php if (session('error')): ?>
        <div class="alert alert-danger">
            <?= session('error'); ?>
        </div>
    <?php endif; ?>

    <!-- Validation Errors -->
    <?php if (session('validation')): ?>
        <div class="alert alert-danger">
            <?= session('validation')->listErrors() ?>
        </div>
    <?php endif; ?>

    <h2 class="text-center mb-4">Formulaire de Contact</h2>

    <!-- Contact update -->
    <button id="bouton-ajouter" class="btn btn-primary mb-3">
        <i class="fa fa-plus" aria-hidden="true"></i> modifier e-mail de contact
    </button>

    <form id="formulaire" style="display:none;" action="<?= route_to('contactUpdate'); ?>" method="post"
        class="p-4 border border-light rounded shadow-lg bg-white">
        <div class="mb-3">
            <label for="name" class="form-label">E-mail de la plume monclaraise :</label>

            <input type="text" id="mailContact" name="mailContact" class="form-control"
                value="<?= $association['mailContact'] ?>">

        </div>
        <input type="hidden" id="idAssociation" name="idAssociation" value="<?= $association['idAssociation'] ?>">
        <button type="submit" class="btn btn-primary btn-block">modifier</button>
    </form>

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