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
    // Pré-remplir le formulaire de modification d'événement
    $(document).ready(function () {
        $('.bouton-modifier-evenement').click(function () {
            const idEvenement = $(this).data('id');
            const titre = $(this).data('titre');
            const message = $(this).data('message');
            const date = $(this).data('date');
            const image = $(this).data('image');

            // Injecter les données dans les champs du formulaire de manière sécurisée
            $('#editEventId').val(idEvenement);
            $('#editTitre').val(titre);
            $('#editMessage').val(message);
            $('#editDate').val(date);

            // Prévisualiser l'image si elle existe
            if (image) {
                $('#editImagePreview').attr('src', image).show();
            } else {
                $('#editImagePreview').hide(); // Cacher l'image si elle n'existe pas
            }
        });
    });
});
