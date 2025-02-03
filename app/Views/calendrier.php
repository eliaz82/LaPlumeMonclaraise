<?= $this->extend('layout') ?>
<?= $this->section('contenu') ?>

<div class="container mt-5">
  <div class="row">
    <div class="col-4">
      <div class="carousel-container" style="position: relative; overflow: hidden; height: 500px;">
        <button id="scroll-up" class="carousel-nav-up"><i class="fas fa-chevron-up"></i></button>
        <div class="carousel"></div>
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
  const scrollUpButton = document.getElementById('scroll-up');
  const scrollDownButton = document.getElementById('scroll-down');
  
  // Récupération des posts d'origine depuis PHP
  const originalPosts = [];
  <?php if (!empty($posts)): ?>
    <?php foreach ($posts as $post): ?>
      originalPosts.push({
        date: "<?= isset($post['date']) ? esc($post['date']) : '' ?>",
        message: "<?= esc($post['message']) ?>"
      });
    <?php endforeach; ?>
  <?php endif; ?>
  
  // Nombre de posts originaux
  const count = originalPosts.length;
  if(count === 0) return;
  
  // Fonction pour créer un élément post
  function createPostElement(post) {
    const postEl = document.createElement('div');
    postEl.classList.add('post');
    postEl.setAttribute('data-date', post.date);
    const p = document.createElement('p');
    p.textContent = post.message;
    postEl.appendChild(p);
    return postEl;
  }
  
  // Construction du carousel en dupliquant la liste trois fois
  const allPosts = [];
  for(let i = 0; i < 3; i++){
    originalPosts.forEach(post => {
      const postEl = createPostElement(post);
      // Stocker l'index original pour pouvoir le retrouver lors du clic
      postEl.setAttribute('data-original-index', originalPosts.indexOf(post));
      allPosts.push(postEl);
    });
  }
  allPosts.forEach(el => carousel.appendChild(el));
  
  // On positionne le carousel sur la copie centrale (indices de count à 2*count-1)
  let currentIndex = count;
  
  // Fonction de mise à jour de la position du carousel
  function updateCarouselPosition(animate = true) {
    const posts = carousel.querySelectorAll('.post');
    if(posts.length === 0) return;
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
    if(currentIndex < count) {
      currentIndex += count;
      updateCarouselPosition(false);
    } else if(currentIndex >= 2 * count) {
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
    // Remonter jusqu'à l'élément qui possède la classe "post"
    while (target && !target.classList.contains('post')) {
      target = target.parentElement;
    }
    if(!target) return;
    
    // Récupérer l'index original stocké dans l'attribut
    const origIdx = parseInt(target.getAttribute('data-original-index'));
    
    // Positionner le carousel pour centrer la copie centrale correspondante
    currentIndex = count + origIdx;
    updateCarouselPosition(false); // Mise à jour immédiate sans animation
    
    // Une fois le repositionnement fait, on met à jour la surbrillance
    const posts = carousel.querySelectorAll('.post');
    posts.forEach(post => post.classList.remove('highlight'));
    if(posts[count + origIdx]) {
      posts[count + origIdx].classList.add('highlight');
    }
    
    // Mise à jour du calendrier
    const postDate = target.getAttribute('data-date');
    if(postDate) {
      const parts = postDate.split('/');
      if(parts.length === 3) {
        const formattedDate = parts[2] + '-' + parts[1] + '-' + parts[0];
        calendar.changeView('dayGridMonth', formattedDate);
        calendar.gotoDate(formattedDate);
        highlightDayInCalendar(formattedDate);
      }
    }
  });
  
  // Initialisation de FullCalendar
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
      const posts = carousel.querySelectorAll('.post');
      let foundIndices = [];
      // Recherche des posts dont la date correspond
      posts.forEach((post, index) => {
        const postDate = post.getAttribute('data-date');
        if(postDate) {
          const parts = postDate.split('/');
          if(parts.length === 3) {
            const formattedPostDate = parts[2] + '-' + parts[1] + '-' + parts[0];
            if(formattedPostDate === clickedDate) {
              foundIndices.push(index);
            }
          }
        }
      });
      if(foundIndices.length > 0) {
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
    if(activeDay) {
      activeDay.classList.add('highlight');
    }
  }
});
</script>

<?= $this->endSection() ?>
