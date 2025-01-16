<?= $this->extend('layout') ?>
<?= $this->section('contenu') ?>

<h1 class="text-center mb-5 text-dark">Formulaire de Contact</h1>

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

    <!-- Contact update -->
    <form action="<?= route_to('contactUpdate'); ?>" method="post" class="p-4 border border-light rounded shadow-lg bg-white">
        <div class="mb-3">
            <label for="name" class="form-label">E-mail de la plume monclaraise :</label>
          
            <input type="text" id="mailContact" name="mailContact" class="form-control" value="<?= $association['mailContact'] ?>">

        </div>
        <input type="hidden" id="idAssociation" name="idAssociation" value="<?= $association['idAssociation'] ?>">
        <button type="submit" class="btn btn-primary btn-block">Envoyer</button>
    </form>

    <!-- Contact Form -->
    <form action="<?= route_to('contactSubmit'); ?>" method="post" class="p-4 border border-light rounded shadow-lg bg-white">
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
            <input type="text" id="subject" name="subject" class="form-control" placeholder="Objet de votre message" required>
        </div>

        <div class="mb-3">
            <label for="message" class="form-label">Message:</label>
            <textarea id="message" name="message" rows="5" class="form-control" placeholder="Votre message" required></textarea>
        </div>

        <button type="submit" class="btn btn-primary btn-block">Envoyer</button>
    </form>
</div>

<?= $this->endSection() ?>