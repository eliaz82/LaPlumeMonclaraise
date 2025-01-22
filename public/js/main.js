"use strict";

$(document).ready(function () {
    // Afficher/Masquer le formulaire d'ajout
    $('#bouton-ajouter').click(function () {
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
    const reader = new FileReader();
    reader.onload = function () {
        $('#' + previewId).attr('src', reader.result).show(); // Afficher l'image prévisualisée
    };
    reader.readAsDataURL(event.target.files[0]);
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

function previewImage(event, id) {
    var reader = new FileReader();
    reader.onload = function(){
      var output = document.getElementById(id);
      output.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
  }


