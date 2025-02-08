"use strict";
document.addEventListener("DOMContentLoaded", function () {
    const modalElement = document.getElementById('fullMessageModal');
    const modal = new bootstrap.Modal(modalElement);

    document.querySelectorAll(".toggle-text").forEach(button => {
        button.addEventListener("click", function () {
            const cardText = this.closest(".card-text");
            const fullText = cardText.querySelector(".full-text").innerHTML;
            const card = this.closest('.card');
            const cardImageElement = card.querySelector('img.card-img-top');
            let modalContent = '';

            if (cardImageElement) {
                modalContent += `<img src="${cardImageElement.src}" alt="${cardImageElement.alt}" class="img-fluid mb-3 modal-image">`;
            }
            modalContent += fullText;
            modalElement.querySelector(".modal-body").innerHTML = modalContent;
            modal.show();
        });
    });

    const highlighted = document.getElementById('highlightedEvent');
    if (highlighted) {
        highlighted.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
});
