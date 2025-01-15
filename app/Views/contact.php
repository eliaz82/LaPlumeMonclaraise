<?= $this->extend('layout') ?>
<?= $this->section('contenu') ?>
>
    <h1>Formulaire de Contact</h1>
    <div class="container">
        <h1>Formulaire de Contact</h1>
        <?php if (session('success')): ?>
            <div class="alert success">
                <?php echo session('success'); ?>
            </div>
        <?php endif; ?>
        <?php if (session('error')): ?>
            <div class="alert error">
                <?php echo session('error'); ?>
            </div>
        <?php endif; ?>
        <?php if (session('validation')): ?>
            <div class="alert error">
                <?php echo session('validation')->listErrors(); ?>
            </div>
        <?php endif; ?>
        <form action="<?php echo route_to('contactSubmit'); ?>" method="post">
            <div class="form-group">
                <label for="name">Nom:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="phone">Téléphone:</label>
                <input type="text" id="phone" name="phone" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="subject">Objet:</label>
                <input type="text" id="subject" name="subject" required>
            </div>
            <div class="form-group">
                <label for="message">Message:</label>
                <textarea id="message" name="message" rows="5" required></textarea>
            </div>
            <button type="submit">Envoyer</button>
        </form>
    </div>

<?= $this->endSection() ?>