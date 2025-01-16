"use strict";
$(document).ready(function () {
    // Afficher ou masquer le formulaire d'ajout
    $('#bouton-ajouter').click(function () {
        $('#formulaire').toggle('slow');
    });

    // Remplir le formulaire de modification lorsque l'on clique sur "Modifier"
    $('.bouton-modifier').click(function () {
        const id = $(this).data('id');
        const info = $(this).data('info');
        const lien = $(this).data('lien');

        $('#modifier-id').val(id);
        $('#modifier-info').val(info);
        $('#modifier-lien').val(lien);
    });

});
function previewImage(event, previewId) {
    var reader = new FileReader();
    reader.onload = function () {
        var preview = document.getElementById(previewId);
        preview.src = reader.result;
        preview.style.display = 'block'; // Afficher l'image
    };
    reader.readAsDataURL(event.target.files[0]);
}
document.querySelectorAll('.bouton-modifier').forEach(button => {
    button.addEventListener('click', function () {
        // Récupérer les données du partenaire depuis les attributs du bouton
        var idPartenaire = this.getAttribute('data-id');
        var info = this.getAttribute('data-info');
        var lien = this.getAttribute('data-lien');
        var logo = this.getAttribute('data-logo');

        // Injecter ces données dans les champs du formulaire
        document.getElementById('modifier-id').value = idPartenaire;
        document.getElementById('modifier-info').value = info;
        document.getElementById('modifier-lien').value = lien;

        // Prévisualiser l'image du logo actuel
        var logoPreview = document.getElementById('modifierLogoPreview');
        logoPreview.src = logo;  // Afficher l'image existante
        logoPreview.style.display = 'block';  // Afficher l'image
    });
});