"use strict";
document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');
    const carousel = document.querySelector('.carousel');
    const today = new Date();
    today.setHours(0, 0, 0, 0); // Réinitialise l'heure à 00:00:00.000
    const todayMidnightStr = today.toISOString().split('T')[0]; // Date sans heure (pour comparaison)
    const scrollUpButton = document.getElementById('scroll-up');
    const scrollDownButton = document.getElementById('scroll-down');
    const eventDataElement = document.getElementById('eventData');

    // Récupérer les valeurs des attributs data-*
    const eventUrl = eventDataElement.getAttribute('data-event-url');
    const posts = JSON.parse(eventDataElement.getAttribute('data-posts'));


    const originalPosts = posts.map(post => ({
        date: post.date || '',
        titre: post.titre || '',
        image: post.image || null,
        id: post.id || ''
    }));


    // Filtrer les posts pour ne garder que ceux après aujourd'hui à 00:00
    const futurePosts = originalPosts.filter(post => {
        const postDate = post.date.split('/').reverse().join('-'); // Format "YYYY-MM-DD"
        return postDate > todayMidnightStr;
    });

    const count = futurePosts.length;
    // Ne pas retourner si aucun événement futur, pour continuer à afficher le calendrier
    if (count === 0) {
        // Masquer le conteneur du carousel s'il n'y a que des événements passés
        const carouselContainer = document.querySelector('.carousel-container-calendrier');
        if (carouselContainer) {
            carouselContainer.style.display = 'none';
        }
        const calendarCol = document.querySelector('.col-12.col-md-8');
        if (calendarCol) {
            // Remplacer la classe "col-md-8" par "col-12"
            calendarCol.classList.remove('col-md-8');
            calendarCol.classList.add('col-12');
            // On peut aussi ajouter "mx-auto" pour centrer la colonne si nécessaire
            calendarCol.classList.add('mx-auto');
            // Optionnel : centrer le contenu de la colonne
            calendarCol.style.textAlign = 'center';
        }
        
        // Modifier le style du calendrier pour le centrer dans son conteneur
        const calendarEl = document.getElementById('calendar');
        if (calendarEl) {
            // Remplacer le style inline par des marges automatiques
            calendarEl.style.cssText = "max-width: 800px; width: 100%; margin: 0 auto;";
            // Modifier les classes pour que le texte soit centré
            calendarEl.classList.remove('text-end');
            calendarEl.classList.add('text-center');
        }
    }

    // Fonction pour créer un élément post sans image
    function createPostElement(post) {
        const postEl = document.createElement('div');
        postEl.classList.add('post');
        postEl.setAttribute('data-date', post.date);

        const p = document.createElement('p');
        p.textContent = post.titre;
        postEl.appendChild(p);

        // Ajouter l'image si elle existe et la rendre cliquable
        if (post.image) {
            const img = document.createElement('img');
            img.src = post.image;
            img.alt = 'Image associée à l\'événement';
            img.classList.add('post-image'); // Applique la classe pour la redimensionner
            img.style.cursor = 'pointer'; // Change le curseur pour indiquer qu'on peut cliquer
            postEl.appendChild(img);

            // Ajoute l'événement de clic pour zoomer sur l'image
            img.addEventListener('click', function () {
                zoomImage(this.src); // Appelle la fonction de zoom avec l'image cliquée
            });
        }
        const readMoreButton = document.createElement('button');
        readMoreButton.textContent = 'Voir plus';
        readMoreButton.classList.add('read-more-button');
        readMoreButton.setAttribute('data-event-id', post.id); // L'attribut data-event-id contient l'ID de l'événement
        postEl.appendChild(readMoreButton);
        return postEl;
    }

    // Construction du carousel
    const allPosts = [];
    // Duplique uniquement si on a plus de 2 posts
    if (futurePosts.length > 1) {
        for (let i = 0; i < 3; i++) {
            futurePosts.forEach((post, index) => {
                const postEl = createPostElement(post);
                postEl.setAttribute('data-original-index', index);
                allPosts.push(postEl);
            });
        }
    } else {
        // Si on a 1 ou 2 posts, on les ajoute une seule fois
        futurePosts.forEach((post, index) => {
            const postEl = createPostElement(post);
            postEl.setAttribute('data-original-index', index);
            allPosts.push(postEl);
        });
    }

    allPosts.forEach(el => carousel.appendChild(el));

    // On positionne le carousel sur la copie centrale (indices de count à 2*count-1)
    let currentIndex = count;

    // Fonction de mise à jour de la position du carousel
    function updateCarouselPosition(animate = true) {
        const posts = carousel.querySelectorAll('.post');
        if (posts.length === 0) return;
        const containerHeight = document.querySelector('.carousel-container-calendrier').clientHeight;
        const postHeight = posts[0].offsetHeight;
        const centerOffset = (containerHeight / 2) - (postHeight / 2);
        const offset = centerOffset - (currentIndex * postHeight);
        carousel.style.transition = animate ? 'transform 0.3s ease-in-out' : 'none';
        carousel.style.transform = `translateY(${offset}px)`;
    }

    // Position initiale sans animation
    updateCarouselPosition(false);

    // Ajustement pour rester dans la zone centrale
    function adjustIndexIfNeeded() {
        if (currentIndex < count) {
            currentIndex += count;
            updateCarouselPosition(false);
        } else if (currentIndex >= 2 * count) {
            currentIndex -= count;
            updateCarouselPosition(false);
        }
    }

    // Boutons de défilement (avec animation)
    scrollUpButton.addEventListener('click', function () {
        currentIndex--;
        updateCarouselPosition();
        setTimeout(adjustIndexIfNeeded, 350);
    });

    scrollDownButton.addEventListener('click', function () {
        currentIndex++;
        updateCarouselPosition();
        setTimeout(adjustIndexIfNeeded, 350);
    });

    // Gestion du clic sur un post du carousel
    carousel.addEventListener('click', function (e) {
        let target = e.target;

        // Vérifier si le clic provient du bouton "Lire plus"
        if (target && target.classList.contains('read-more-button')) {
            const eventId = target.getAttribute('data-event-id');
            if (eventId) {
                // Rediriger vers l'événement spécifique
                window.location.href = `${eventUrl}/${encodeURIComponent(eventId)}`;
            } else {
                console.error('ID de l\'événement non trouvé');
            }
        } else {
            // Logique existante pour gérer le carousel
            // Remonter jusqu'à l'élément qui possède la classe "post"
            while (target && !target.classList.contains('post')) {
                target = target.parentElement;
            }
            if (!target) return;

            const origIdx = parseInt(target.getAttribute('data-original-index'));
            currentIndex = count + origIdx;
            updateCarouselPosition(false);

            const posts = carousel.querySelectorAll('.post');
            posts.forEach(post => post.classList.remove('highlight'));
            if (posts[count + origIdx]) {
                posts[count + origIdx].classList.add('highlight');
            }

            const postDate = target.getAttribute('data-date'); // Récupère la date du post

            if (postDate) {
                // Vérifie si la date est déjà au format YYYY-MM-DD
                let formattedDate = postDate;
                if (!formattedDate.includes('-')) {
                    // Si la date est au format DD/MM/YYYY, la convertir en YYYY-MM-DD
                    const parts = formattedDate.split('/');
                    if (parts.length === 3) {
                        formattedDate = parts[2] + '-' + parts[1] + '-' + parts[0];
                    }
                }
                // Mettre à jour le calendrier avec la date formatée
                calendar.changeView('dayGridMonth', formattedDate);
                calendar.gotoDate(formattedDate);
                highlightDayInCalendar(formattedDate);
            }

        }
    });
    var events = JSON.parse(eventDataElement.getAttribute('data-events')) || [];


    events = events.map(event => {
        if (event.start.indexOf('/') !== -1) {
            const parts = event.start.split('/');
            if (parts.length === 3) {
                event.start = parts[2] + '-' + parts[1] + '-' + parts[0];
            }
        }
        return event;
    });
    var calendar = new FullCalendar.Calendar(calendarEl, {
        locale: 'fr',
        initialView: 'dayGridMonth',
        themeSystem: 'bootstrap5',
        eventDidMount: function (info) {
            const eventTitle = info.event.title;
            const titleElement = info.el.querySelector('.fc-event-title');

            if (titleElement) {
                // Créer un conteneur pour l'animation du texte
                const span = document.createElement('span');
                span.textContent = eventTitle;
                span.classList.add('scrolling-text'); // Ajout de la classe d'animation

                titleElement.innerHTML = ''; // Effacer l'ancien contenu
                titleElement.appendChild(span); // Ajouter le nouveau contenu
            }
            if (info.event.extendedProps.past) {
                info.el.classList.add('past-event');
            }
        },
        buttonText: {
            today: 'Aujourd\'hui',
            month: 'Mois',
            week: 'Semaine',
            day: 'Jour'
        },
        events: events,
        eventColor: '#2980b9',
        eventTextColor: '#f5c542',
        eventClick: function (info) {
            const clickedDate = info.event.startStr; // Date de l'événement (YYYY-MM-DD)

            const posts = carousel.querySelectorAll('.post');
            let foundIndices = [];

            posts.forEach((post, index) => {
                let postDate = post.getAttribute('data-date'); // Date du post

                if (postDate) {
                    let formattedPostDate = postDate; // Par défaut, on suppose que la date est déjà bien formatée

                    // Vérifier si la date est au format DD/MM/YYYY (et non YYYY-MM-DD)
                    const regexDMY = /^\d{2}\/\d{2}\/\d{4}$/;
                    if (regexDMY.test(postDate)) {
                        // Conversion en format YYYY-MM-DD
                        const parts = postDate.split('/');
                        formattedPostDate = `${parts[2]}-${parts[1]}-${parts[0]}`;
                    }

                    // Comparaison avec la date cliquée
                    if (formattedPostDate === clickedDate) {
                        foundIndices.push(index);
                    }
                }
            });
            if (foundIndices.length > 0) {
                posts.forEach(post => post.classList.remove('highlight'));
                foundIndices.forEach(idx => posts[idx].classList.add('highlight'));

                const origIdx = foundIndices[0] % count;
                currentIndex = count + origIdx;
                updateCarouselPosition();
            }

            highlightDayInCalendar(clickedDate);
        },
        dateClick: function (info) {
            const clickedDate = info.dateStr; // Format "YYYY-MM-DD"

            const posts = document.querySelectorAll('.post');
            let foundIndices = [];

            posts.forEach((post, index) => {
                let postDate = post.getAttribute('data-date');

                if (postDate) {
                    let formattedPostDate = postDate; // Par défaut, on suppose que c'est déjà YYYY-MM-DD

                    // Si la date contient '/', c'est du format DD/MM/YYYY => Convertir en YYYY-MM-DD
                    if (postDate.includes('/')) {
                        const parts = postDate.split('/');
                        if (parts.length === 3) {
                            formattedPostDate = `${parts[2]}-${parts[1]}-${parts[0]}`;
                        }
                    }
                    if (formattedPostDate === clickedDate) {
                        foundIndices.push(index);
                    }
                }
            });

            if (foundIndices.length > 0) {
                // Supprimer les anciens highlights
                posts.forEach(post => post.classList.remove('highlight'));

                // Ajouter le highlight aux posts trouvés
                foundIndices.forEach(idx => posts[idx].classList.add('highlight'));

                // Ajuster la position du carousel
                const origIdx = foundIndices[0] % count;
                currentIndex = count + origIdx;
                updateCarouselPosition();
            }

            highlightDayInCalendar(clickedDate);
        }

    });
    calendar.render();
    function highlightDayInCalendar(dateStr) {
        document.querySelectorAll('.fc-day').forEach(day => day.classList.remove('highlight'));
        const activeDay = document.querySelector(`.fc-day[data-date='${dateStr}']`);
        if (activeDay) {
            activeDay.classList.add('highlight');
        }
    }

});