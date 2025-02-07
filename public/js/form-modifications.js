"use strict";
$(document).ready(function () {
    // Pré-remplir le formulaire de modification d'adhérent
    $('.bouton-modifier-adherent').click(function () {
        const id = $(this).data('id');
        const nom = $(this).data('nom');
        const prenom = $(this).data('prenom');
        const grade = $(this).data('grade');
        const photo = $(this).data('photo');

        // Injecter les données dans les champs du formulaire de manière sécurisée
        $('#modifier-id-adherent').val(id);
        $('#modifier-nom-adherent').val(nom);
        $('#modifier-prenom-adherent').val(prenom);
        $('#modifier-grade-adherent').val(grade);

        // Prévisualiser l'image si elle existe
        if (photo) {
            $('#modifierPhotoPreviewAdherent').attr('src', photo).show();
        }
    });

    // Pré-remplir le formulaire de modification du partenaire
    $('.bouton-modifier-partenaire').click(function () {
        const idPartenaire = this.getAttribute('data-id');
        const info = this.getAttribute('data-info');
        const lien = this.getAttribute('data-lien');
        const logo = this.getAttribute('data-logo');

        document.getElementById('modifier-id').value = idPartenaire;
        document.getElementById('modifier-info').value = info;
        document.getElementById('modifier-lien').value = lien;

        if (logo) {
            $('#modifierLogoPreview').attr('src', logo).show();
        }
    });

    // Pré-remplir le formulaire de modification d'album
    $('.bouton-modifier-album').click(function () {
        const idAlbums = $(this).data('idalbums');
        const date = $(this).data('date');
        const nom = $(this).data('nom');
        const photo = $(this).data('photo');
        const isFacebook = $(this).data('facebook');

        $('#modifier-id-album').val(idAlbums);
        $('#modifier-date-album').val(date);
        $('#modifier-nom-album').val(nom);

        if (photo) {
            $('#modifierPhotoPreviewAlbum').attr('src', photo).show();
        } else {
            $('#modifierPhotoPreviewAlbum').hide();
        }
        if (isFacebook == 1) {
            $('#modifier-date-album').prop('disabled', true);
        } else {
            $('#modifier-date-album').prop('disabled', false);
        }
    });
});
