<?= $this->extend('layout') ?>
<?= $this->section('contenu') ?>

<!DOCTYPE html>
<html lang="fr">

<h1>Formulaire de contact
</h1>

<div class="container">
    <h1>Formulaire de Contact</h1>
    <form action="submit_form.php" method="post">
        <div class="form-group">
            <label for="name">Nom:</label>
            <input type="text" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="email">Téléphone:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="subject">E-mail:</label>
            <input type="text" id="subject" name="subject" required>
        </div>
        <div class="form-group">
            <label for="message">Objet:</label>
            <textarea id="message" name="message" rows="5" required></textarea>
        </div>
        <div class="form-group">
            <label for="message">Message:</label>
            <textarea id="message" name="message" rows="5" required></textarea>
        </div>
        <button type="submit">Envoyer</button>
    </form>
</div>

</html>
<?= $this->endSection() ?>