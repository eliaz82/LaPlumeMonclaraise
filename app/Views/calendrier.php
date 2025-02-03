<?= $this->extend('layout') ?>
<?= $this->section('contenu') ?>

<div class="container mt-5">
    <div class="row">
        <div class="col-4">
            <div class="carousel-container">
                <button id="scroll-up" class="carousel-nav-up"><i class="fas fa-chevron-up"></i></button>
                <div class="carousel">
                    <?php if (!empty($posts)): ?>
                        <?php foreach ($posts as $post): ?>
                            <div class="post" data-date="<?= isset($post['date']) ? esc($post['date']) : '' ?>">
                                <p><?= esc($post['message']) ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Aucune publication trouvée</p>
                    <?php endif; ?>
                </div>
                <button id="scroll-down" class="carousel-nav-down"><i class="fas fa-chevron-down"></i></button>
            </div>
        </div>

        <div class="col-8">
            <div id="calendar" class="text-end" style="max-width: 800px; width: 100%; margin-left: auto; margin-right: 0;"></div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    const carousel = document.querySelector('.carousel');
    let currentIndex = 0;

    // Conversion des événements du serveur au format { start: "YYYY-MM-DD", ... }
    var events = <?= json_encode($events); ?>;
    events = events.map(event => {
        const parts = event.start.split('/'); // [jour, mois, année]
        const formatted = parts[2] + '-' + parts[1] + '-' + parts[0];
        return { ...event, start: formatted };
    });

    var calendar = new FullCalendar.Calendar(calendarEl, {
        locale: 'fr',
        initialView: 'dayGridMonth',
        themeSystem: 'bootstrap5',
        buttonText: {
            today: 'Aujourd\'hui',
            month: 'Mois',
            week: 'Semaine',
            day: 'Jour'
        },
        events: events,
        eventColor: '#2980b9',
        eventTextColor: '#f5c542',
        dateClick: function(info) {
            const clickedDate = info.dateStr;  // Format "YYYY-MM-DD"
            let foundIndices = [];
            document.querySelectorAll('.carousel .post').forEach((post, index) => {
                const postDate = post.getAttribute('data-date');  // Au format "DD/MM/YYYY"
                const parts = postDate.split('/');
                const formattedPostDate = parts[2] + '-' + parts[1] + '-' + parts[0];
                if (formattedPostDate === clickedDate) {
                    foundIndices.push(index);
                }
            });
            if(foundIndices.length > 0) {
                // Réinitialiser la surbrillance sur tous les posts
                document.querySelectorAll('.carousel .post').forEach(post => post.classList.remove('highlight'));
                foundIndices.forEach(idx => {
                    document.querySelectorAll('.carousel .post')[idx].classList.add('highlight');
                });
                currentIndex = foundIndices[0];
                updateCarouselPosition();
            }
            highlightDayInCalendar(clickedDate);
        }
    });
    calendar.render();

    // Fonction qui centre l'élément sélectionné dans le carousel
    function updateCarouselPosition() {
        const posts = document.querySelectorAll('.carousel .post');
        if(posts.length === 0) return;
        const carouselHeight = carousel.clientHeight;
        const postHeight = posts[0].offsetHeight;
        // Calculer l'offset pour que le centre du post sélectionné soit au centre du carousel
        const centerOffset = (carouselHeight / 2) - (postHeight / 2);
        const offset = centerOffset - (currentIndex * postHeight);
        carousel.style.transition = 'transform 0.3s ease-in-out';
        carousel.style.transform = `translateY(${offset}px)`;
    }

    function highlightDayInCalendar(dateStr) {
        document.querySelectorAll('.fc-day').forEach(day => day.classList.remove('highlight'));
        const activeDay = document.querySelector(`.fc-day[data-date="${dateStr}"]`);
        if(activeDay) {
            activeDay.classList.add('highlight');
        }
    }

    // Lors du clic sur un post dans le carousel
    document.querySelectorAll('.carousel .post').forEach(post => {
        post.addEventListener('click', function() {
            // Effacer la surbrillance sur tous les posts
            const posts = document.querySelectorAll('.carousel .post');
            posts.forEach(p => p.classList.remove('highlight'));
            this.classList.add('highlight');
            currentIndex = Array.from(posts).indexOf(this);
            const postDate = this.getAttribute('data-date');
            if (!postDate) return;
            const parts = postDate.split('/');
            if(parts.length !== 3) return;
            const formattedDate = parts[2] + '-' + parts[1] + '-' + parts[0];
            calendar.changeView('dayGridMonth', formattedDate);
            calendar.gotoDate(formattedDate);
            highlightDayInCalendar(formattedDate);
            updateCarouselPosition();
        });
    });

    // Boutons de défilement avec boucle
    const scrollUpButton = document.getElementById('scroll-up');
    const scrollDownButton = document.getElementById('scroll-down');
    if(scrollUpButton && scrollDownButton) {
        scrollUpButton.addEventListener('click', function() {
            const posts = document.querySelectorAll('.carousel .post');
            if(currentIndex === 0) {
                currentIndex = posts.length - 1;
            } else {
                currentIndex--;
            }
            updateCarouselPosition();
        });
        scrollDownButton.addEventListener('click', function() {
            const posts = document.querySelectorAll('.carousel .post');
            if(currentIndex === posts.length - 1) {
                currentIndex = 0;
            } else {
                currentIndex++;
            }
            updateCarouselPosition();
        });
    }
});
</script>







<?= $this->endSection() ?>
