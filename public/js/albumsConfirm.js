"use strict";
function confirmerSuppression(facebookPostUrl) {
    if (facebookPostUrl) {
        if (confirm("Cet album provient de Facebook. Vous devez d'abord le supprimer sur Facebook. Après l'avoir supprimé, cliquez sur le bouton d'actualisation, puis supprimez-le ici à nouveau.\n\nOu simplement, supprimez le hashtag de la publication, actualisez grâce au bouton, puis supprimez-le enfin ici.\n\nVoulez-vous aller sur le post Facebook ?")) {
            window.open(facebookPostUrl, "_blank");
        }
        return false;
    }
    return confirm("Êtes-vous sûr de vouloir supprimer cet album photo ?");
}
