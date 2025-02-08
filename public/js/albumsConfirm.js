"use strict";
function confirmerSuppression(facebookPostUrl) {
    if (facebookPostUrl) {
        if (confirm("Cet album provient de Facebook. Vous devez d'abord le supprimer sur Facebook, puis ici pour qu'il soit complètement supprimé.\n\nVoulez-vous aller sur le post Facebook ?")) {
            window.open(facebookPostUrl, "_blank");
        }
        return false;
    }
    return confirm("Êtes-vous sûr de vouloir supprimer cet album photo ?");
}
