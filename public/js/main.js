"use strict";
if (window.location.hash === "#_=_") {
    history.replaceState(null, null, window.location.href.split("#")[0]);
}

$(document).ready(function () {
    // Pré-remplir le formulaire de modification
    $('.bouton-modifier-adherent').click(function () {
        const id = $(this).data('id');
        const nom = $(this).data('nom');
        const prenom = $(this).data('prenom');
        const grade = $(this).data('grade');
        const photo = $(this).data('photo');

        // Injecter les données dans les champs du formulaire
        $('#modifier-id-adherent').val(id);
        $('#modifier-nom-adherent').val(nom);
        $('#modifier-prenom-adherent').val(prenom);
        $('#modifier-grade-adherent').val(grade);

        // Prévisualiser l'image si elle existe
        if (photo) {
            $('#modifierPhotoPreviewAdherent').attr('src', photo).show();
        }
    });


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
    $('.bouton-modifier-album').click(function () {
        const idAlbums = $(this).data('idalbums'); // Récupération de l'ID
        const date = $(this).data('date');
        const nom = $(this).data('nom');
        const photo = $(this).data('photo');
    
        $('#modifier-id-album').val(idAlbums); // Remplissage du champ caché
        $('#modifier-date-album').val(date);
        $('#modifier-nom-album').val(nom);
    
        if (photo) {
            $('#modifierPhotoPreviewAlbum').attr('src', photo).show();
        } else {
            $('#modifierPhotoPreviewAlbum').hide();
        }
    });
    
});

// Fonction de prévisualisation d'image
function previewImage(event, previewId) {
    const input = event.target;
    const preview = document.getElementById(previewId);

    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function (e) {
            preview.src = e.target.result; // Charger l'image
            preview.style.display = 'block'; // Afficher l'image
        };

        reader.readAsDataURL(input.files[0]); // Lire le fichier
    }
}




// le navbar pour mettre bien a revoir
document.addEventListener("DOMContentLoaded", () => {
    const navbar = document.querySelector('.navbar.fixed-top');
    const contentWrapper = document.body;
    if (navbar) {
        const adjustBodyPadding = () => {
            contentWrapper.style.paddingTop = `${navbar.offsetHeight}px`;
        };
        adjustBodyPadding();
        window.addEventListener('resize', adjustBodyPadding);
    }
});



// Gestion du glisser-déposer pour le fichier d'inscription
$(document).ready(function () {
    var dropArea = $('#drop-area');
    var fileInput = $('#fichier_inscription');
    var fileNameDisplay = $('#file-name');

    dropArea.on('dragover', function (e) {
        e.preventDefault();
        e.stopPropagation();
        dropArea.css('background', 'rgba(0, 140, 255, 0.1)');
    });

    dropArea.on('dragleave', function (e) {
        e.preventDefault();
        e.stopPropagation();
        dropArea.css('background', 'transparent');
    });

    dropArea.on('drop', function (e) {
        e.preventDefault();
        e.stopPropagation();
        dropArea.css('background', 'transparent');

        var files = e.originalEvent.dataTransfer.files;
        if (files.length > 0) {
            fileInput[0].files = files;
            fileNameDisplay.text(files[0].name); // Afficher le nom du fichier déposé
        }
    });

    fileInput.on('change', function () {
        var file = fileInput[0].files[0];
        if (file) {
            fileNameDisplay.text(file.name); // Afficher le nom du fichier sélectionné
        }
    });

    dropArea.on('click', function () {
        fileInput.click(); // Déclencher l'événement de clic sur l'input
    });
});
