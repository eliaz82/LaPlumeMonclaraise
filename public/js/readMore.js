"use strict";

document.addEventListener("DOMContentLoaded", function () {
    var readMoreButtons = document.querySelectorAll('.read-more');

    function truncateText() {
        var posts = document.querySelectorAll('.post-message');
        posts.forEach(function (post) {
            var fullText = post.innerHTML.trim(); // Récupère le texte avec les sauts de ligne
            var words = fullText.split(/\s+/); // Divise le texte en mots
            var maxWords = 25;

            if (words.length > maxWords) {
                var truncated = words.slice(0, maxWords).join(' ') + '...'; // Tronque le texte
                post.innerHTML = truncated; // Affiche le texte tronqué
                post.dataset.fullText = fullText; // Stocke le texte complet
                post.dataset.truncatedText = truncated; // Stocke le texte tronqué
                post.nextElementSibling.style.display = 'inline-block';
            } else {
                post.nextElementSibling.style.display = 'none';
            }
        });
    }

    readMoreButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            var content = this.previousElementSibling;
            var fullText = content.dataset.fullText;
            var truncatedText = content.dataset.truncatedText;

            if (content.classList.contains('expanded')) {
                content.innerHTML = truncatedText;
                this.textContent = 'Lire plus';
            } else {
                content.innerHTML = fullText; // Affiche le texte complet avec les sauts de ligne
                this.textContent = 'Lire moins';
            }

            content.classList.toggle('expanded');
        });
    });

    truncateText();
});
