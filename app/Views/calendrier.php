<?= $this->extend('layout') ?>
<?= $this->section('contenu') ?>


<div class="container mt-5">
    <div class="row">
        <?php if (!empty($posts)): ?>
            <div class="col-4">
                <!-- Conteneur du carousel avec flèches fixes en haut et en bas -->
                <div class="carousel-container" style="position: relative; overflow: hidden; height: 500px;">
                    <!-- Flèche du haut -->
                    <button id="scroll-up" class="carousel-nav-up">
                        <i class="fas fa-chevron-up"></i>
                    </button>
                    <!-- Le carousel -->
                    <div class="carousel">
                        <!-- Les éléments du carousel (posts avec images cliquables) vont ici -->
                    </div>
                    <!-- Conteneur pour zoomer sur l'image -->
                    <div id="zoom-container" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; 
                        background-color: rgba(0, 0, 0, 0.8); justify-content: center; align-items: center;">
                        <img id="zoomed-image" src="" alt="Zoomed Image" style="max-width: 90%; max-height: 90%;" />
                        <button onclick="closeZoom()"
                            style="position: absolute; top: 20px; right: 20px; color: white; font-size: 30px; background: none; border: none;">X</button>
                    </div>
                    <!-- Flèche du bas -->
                    <button id="scroll-down" class="carousel-nav-down">
                        <i class="fas fa-chevron-down"></i>
                    </button>
                </div>
            </div>
        <?php else: ?>
            <div class="col-12 text-center">
                <p>Aucun événement trouvé</p>
            </div>
        <?php endif; ?>

        <div class="col-8">
            <div id="calendar" class="text-end"
                style="max-width: 800px; width: 100%; margin-left: auto; margin-right: 0;"></div>
        </div>
    </div>
</div>




<script>
    document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('calendar');
        const carousel = document.querySelector('.carousel');
        const today = new Date();
        const todayStr = today.toISOString().split('T')[0]; // Date au format YYYY-MM-DD
        today.setHours(0, 0, 0, 0); // Réinitialise l'heure à 00:00:00.000
        const todayMidnightStr = today.toISOString().split('T')[0]; // Date sans heure (pour comparaison)
        const scrollUpButton = document.getElementById('scroll-up');
        const scrollDownButton = document.getElementById('scroll-down');

        // Récupération des posts d'origine depuis PHP
        const originalPosts = [];
        <?php if (!empty($posts)): ?>
            <?php foreach ($posts as $post): ?>
                originalPosts.push({
                    date: "<?= isset($post['date']) ? esc($post['date']) : '' ?>",
                    titre: "<?= isset($post['titre']) ? esc($post['titre']) : '' ?>",
                    image: <?= isset($post['image']) ? json_encode($post['image']) : 'null' ?>,
                    id: "<?= isset($post['id']) ? esc($post['id']) : '' ?>"
                });
            <?php endforeach; ?>
        <?php endif; ?>

        // Filtrer les posts pour ne garder que ceux après aujourd'hui à 00:00
        const futurePosts = originalPosts.filter(post => {
            const postDate = post.date.split('/').reverse().join('-'); // Convertir la date au format "YYYY-MM-DD"
            return postDate > todayMidnightStr; // Comparer avec la date d'aujourd'hui à minuit
        });

        // Nombre de posts futurs
        const count = futurePosts.length;
        if (count === 0) return;

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
                img.addEventListener('click', function() {
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
        if (futurePosts.length > 2) {
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
            const containerHeight = document.querySelector('.carousel-container').clientHeight;
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
        scrollUpButton.addEventListener('click', function() {
            currentIndex--;
            updateCarouselPosition();
            setTimeout(adjustIndexIfNeeded, 350);
        });

        scrollDownButton.addEventListener('click', function() {
            currentIndex++;
            updateCarouselPosition();
            setTimeout(adjustIndexIfNeeded, 350);
        });

        // Gestion du clic sur un post du carousel
        carousel.addEventListener('click', function(e) {
            let target = e.target;

            // Vérifier si le clic provient du bouton "Lire plus"
            if (target && target.classList.contains('read-more-button')) {
                const eventId = target.getAttribute('data-event-id');
                if (eventId) {
                    // Rediriger vers l'événement spécifique
                    window.location.href = `<?= site_url('evenement') ?>/${eventId}`;
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

                const postDate = target.getAttribute('data-date');
                if (postDate) {
                    const parts = postDate.split('/');
                    if (parts.length === 3) {
                        const formattedDate = parts[2] + '-' + parts[1] + '-' + parts[0];
                        calendar.changeView('dayGridMonth', formattedDate);
                        calendar.gotoDate(formattedDate);
                        highlightDayInCalendar(formattedDate);
                    }
                }
            }
        });



        // Initialisation de FullCalendar
        var events = <?= json_encode($events); ?>;
        events = events.map(event => {
            const parts = event.start.split('/'); // [jour, mois, année]
            const formatted = parts[2] + '-' + parts[1] + '-' + parts[0];
            return {
                ...event,
                start: formatted
            };
        });

        var calendar = new FullCalendar.Calendar(calendarEl, {
            locale: 'fr',
            initialView: 'dayGridMonth',
            themeSystem: 'bootstrap5',
            eventDidMount: function(info) {
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
            eventClick: function(info) {
                const clickedDate = info.event.startStr; // Récupère la date de l'événement
                const posts = carousel.querySelectorAll('.post');
                let foundIndices = [];

                console.log("Événement cliqué :", info.event.title, "| Date :", clickedDate);

                // Recherche des posts correspondant à la date de l'événement
                posts.forEach((post, index) => {
                    const postDate = post.getAttribute('data-date');
                    if (postDate) {
                        const parts = postDate.split('/');
                        if (parts.length === 3) {
                            const formattedPostDate = `${parts[2]}-${parts[1]}-${parts[0]}`;
                            if (formattedPostDate === clickedDate) {
                                foundIndices.push(index);
                            }
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
            dateClick: function(info) {
                const clickedDate = info.dateStr; // Format "YYYY-MM-DD"
                const posts = carousel.querySelectorAll('.post');
                let foundIndices = [];
                // Recherche des posts dont la date correspond
                posts.forEach((post, index) => {
                    const postDate = post.getAttribute('data-date');
                    if (postDate) {
                        const parts = postDate.split('/');
                        if (parts.length === 3) {
                            const formattedPostDate = parts[2] + '-' + parts[1] + '-' + parts[0];
                            if (formattedPostDate === clickedDate) {
                                foundIndices.push(index);
                            }
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
            }
        });
        calendar.render();

        function highlightDayInCalendar(dateStr) {
            document.querySelectorAll('.fc-day').forEach(day => day.classList.remove('highlight'));
            const activeDay = document.querySelector(`.fc-day[data-date="${dateStr}"]`);
            if (activeDay) {
                activeDay.classList.add('highlight');
            }
        }
    });
</script>


<?= $this->endSection() ?>