<?php

namespace App\Controllers;

use App\Libraries\FacebookCache;

class Calendrier extends BaseController
{
    private $facebookModel;
    private $facebookCache;
    private $evenementsModel;

    public function __construct()
    {
        $this->facebookModel = model('Facebook');
        $this->facebookCache = new FacebookCache();
        $this->evenementsModel = model('Evenements');
    }
    public function calendrier(): string
    {
        try {
            // Récupération des posts depuis le cache Facebook
            $posts = $this->facebookCache->getFacebookPosts();

            // Vérification et extraction des posts
            if (!is_array($posts)) {
                log_message('error', 'Aucun post récupéré depuis Facebook ou structure incorrecte.');
                return view('calendrier', ['events' => [], 'posts' => []]);
            }

            // Récupération des hashtags depuis la base de données
            $hashtags = $this->facebookModel->where('pageName', 'evenementCalendrier')->findAll();
            $hashtagList = !empty($hashtags) ? array_column($hashtags, 'hashtag') : [];

            if (empty($posts)) {
                log_message('error', 'Aucun post récupéré depuis Facebook pour l’événement.');
            }

            // Filtrage des posts pour ne garder que ceux contenant un hashtag
            $filteredPosts = array_filter($posts, function ($post) use ($hashtagList) {
                if (!isset($post['message'])) {
                    return false;
                }
                foreach ($hashtagList as $hashtag) {
                    if (strpos($post['message'], $hashtag) !== false) {
                        return true;
                    }
                }
                return false;
            });

            // Récupération des événements depuis la base de données
            $eventsFromDb = $this->evenementsModel->findAll();
            $events = [];
            $currentDate = new \DateTime();
            $currentDate->setTime(23, 59, 59);

            // Tableau global des posts destinés au carousel
            $allPosts = [];

            // Traitement des posts Facebook
            foreach ($filteredPosts as &$post) {
                $eventDate = null;
                if (preg_match('/(\d{2}\/\d{2}\/\d{4})/', $post['message'], $matches)) {
                    $eventDate = $matches[1];
                }

                if (!$eventDate) {
                    continue; // Pas de date trouvée, on ignore ce post
                }
                list($day, $month, $year) = explode('/', $eventDate);
                if (!checkdate($month, $day, $year)) {
                    continue; // Date invalide
                }
                $eventDateTime = new \DateTime("$year-$month-$day");
                $eventDateTime->setTime(23, 59, 59);

                // Détermine si l'événement est passé
                $past = $eventDateTime < $currentDate;

                // Extraction du titre (entre *...* ou les 50 premiers caractères sinon)
                preg_match('/\*(.*?)\*/', $post['message'], $titleMatches);
                $eventTitle = isset($titleMatches[1]) ? $titleMatches[1] : substr($post['message'], 0, 50);

                // Récupération de l'image si elle existe
                $imageUrl = null;
                if (isset($post['attachments']['data'][0]['media']['image']['src'])) {
                    $imageUrl = $post['attachments']['data'][0]['media']['image']['src'];
                }

                // Ajouter l'événement pour le calendrier avec l'indicateur "past"
                $events[] = [
                    'title' => $eventTitle,
                    'start' => $eventDate, // Format DD/MM/YYYY (sera converti côté JS si nécessaire)
                    'image' => $imageUrl,
                    'id' => $post['id'] ?? uniqid(),
                    'past' => $past
                ];

                $post['date'] = $eventDate;
                $post['image'] = $imageUrl;
                $post['titre'] = $eventTitle;
                $post['id'] = $post['id'] ?? uniqid();
                $allPosts[] = $post;
            }

            // Traitement des événements provenant de la base de données
            foreach ($eventsFromDb as $eventFromDb) {
                // Assurer que la date est au format 'YYYY-MM-DD'
                $eventDate = $eventFromDb['date'];
                if (strpos($eventDate, '/') !== false) {
                    $eventDate = date('Y-m-d', strtotime(str_replace('/', '-', $eventDate)));
                }

                // Créer un objet DateTime pour la date de l'événement
                $eventDateTime = new \DateTime($eventDate);
                $eventDateTime->setTime(23, 59, 59);

                // Détermine si l'événement est passé
                $past = $eventDateTime < $currentDate;

                // Ajouter l'événement pour le calendrier
                $events[] = [
                    'title' => $eventFromDb['titre'],  // Titre de l'événement
                    'start' => $eventDate,              // Date au format 'YYYY-MM-DD'
                    'image' => $eventFromDb['image'],    // Image associée à l'événement
                    'id' => $eventFromDb['idEvenement'] ?? uniqid(),  // ID de l'événement
                    'past' => $past
                ];

                // Ajouter le post pour le carousel, qu'il soit passé ou futur
                $postDb = [
                    'date' => $eventFromDb['date'], // Ici, la date peut être 'YYYY-MM-DD'
                    'titre' => $eventFromDb['titre'],
                    'image' => $eventFromDb['image'],
                    'id' => $eventFromDb['idEvenement'] ?? uniqid()
                ];
                $allPosts[] = $postDb;  // Ajouter tous les posts, passés ou futurs
            }
            // On transmet $allPosts pour le carousel et $events pour le calendrier
            return view('calendrier', ['events' => $events, 'posts' => $allPosts]);

        } catch (\Exception $e) {
            // Log toutes les exceptions
            log_message('error', 'Erreur inconnue : ' . $e->getMessage());
            // Si erreur générale, on continue d'afficher la vue sans événements ou posts
            return view('calendrier', ['events' => [], 'posts' => []]);
        }
    }

}
