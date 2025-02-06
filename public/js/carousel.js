/* "use strict";

// Carrousel Facebook sécurisé
document.addEventListener('DOMContentLoaded', function () {
    let currentIndex = 0;
    const posts = document.querySelectorAll('.carousel-cell');
    const totalPosts = posts.length;
    const wrapper = document.querySelector('.carousel-wrapper');

    wrapper.style.width = `${totalPosts * 100}%`;

    function showNext() {
        if (currentIndex < totalPosts - 1) {
            currentIndex++;
        } else {
            currentIndex = 0;
        }
        updateCarousel();
    }

    function showPrev() {
        if (currentIndex > 0) {
            currentIndex--;
        } else {
            currentIndex = totalPosts - 1;
        }
        updateCarousel();
    }

    function updateCarousel() {
        const offset = -currentIndex * (100 / totalPosts);
        wrapper.style.transform = `translateX(${offset}%)`;
    }

    document.getElementById('nextButton').addEventListener('click', showNext);
    document.getElementById('prevButton').addEventListener('click', showPrev);

    setInterval(showNext, 5000);
    updateCarousel();
});

const carouselWrapper = document.querySelector('.carousel-wrapper');
const cells = document.querySelectorAll('.carousel-cell');

let cellWidth = cells[0].offsetWidth + 20;
let index = 0;

function slideCarousel() {
    index++;
    if (index >= cells.length) {
        index = 0;
    }
    carouselWrapper.style.transform = `translateX(-${index * cellWidth}px)`;
}

setInterval(slideCarousel, 3000);

function addPost(postHTML) {
    const maxPosts = 10;
    const carouselCells = document.querySelectorAll('.carousel-cell');

    if (carouselCells.length >= maxPosts) {
        carouselWrapper.removeChild(carouselCells[0]);
    }

    const newPost = document.createElement('div');
    newPost.classList.add('carousel-cell');
    newPost.innerHTML = postHTML;
    carouselWrapper.appendChild(newPost);
}

// Sécurisation des boutons de modification
const buttons = document.querySelectorAll('.edit-button');

buttons.forEach(button => {
    button.addEventListener('mouseover', function () {
        const text = button.getAttribute('data-text');
        button.setAttribute('data-original-text', text);
    });
}); */
