"use strict";

$(document).ready(function () {
    // Afficher/Masquer le formulaire d'ajout
    $('#bouton-ajouter').click(function () {
        $('#formulaire').toggle('slow');
    });
    $('#bouton-modifier').click(function () {
        $('#formulaire').toggle('slow');
    });
    // Pré-remplir le formulaire de modification
    $('.bouton-modifier').click(function () {
        const id = $(this).data('id');
        const nom = $(this).data('nom');
        const prenom = $(this).data('prenom');
        const grade = $(this).data('grade');
        const photo = $(this).data('photo');

        // Injecter les données dans les champs du formulaire
        $('#modifier-id').val(id);
        $('#modifier-nom').val(nom);
        $('#modifier-prenom').val(prenom);
        $('#modifier-grade').val(grade);

        // Prévisualiser l'image si elle existe
        if (photo) {
            $('#modifierPhotoPreview').attr('src', photo).show();
        } else {
            $('#modifierPhotoPreview').hide();
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



// Écouteurs pour les boutons "Modifier"
document.querySelectorAll('.bouton-modifier').forEach(button => {
    button.addEventListener('click', function () {
        // Récupérer les données depuis les attributs du bouton
        const idPartenaire = this.getAttribute('data-id');
        const info = this.getAttribute('data-info');
        const lien = this.getAttribute('data-lien');
        const logo = this.getAttribute('data-logo');

        // Injecter ces données dans les champs du formulaire
        document.getElementById('modifier-id').value = idPartenaire;
        document.getElementById('modifier-info').value = info;
        document.getElementById('modifier-lien').value = lien;

        // Prévisualiser le logo si présent
        const logoPreview = document.getElementById('modifierLogoPreview');
        if (logo) {
            logoPreview.src = logo;
            logoPreview.style.display = 'block';
        } else {
            logoPreview.style.display = 'none';
        }
    });
});
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
