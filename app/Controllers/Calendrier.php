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
        $posts = $this->facebookCache->getFacebookPosts();
        $hashtags = $this->facebookModel->where('pageName', 'evenementCalendrier')->findAll();
        $hashtagList = array_column($hashtags, 'hashtag');

        $filteredPosts = array_filter($posts['data'], function ($post) use ($hashtagList) {
            if (isset($post['message'])) {
                foreach ($hashtagList as $hashtag) {
                    if (strpos($post['message'], $hashtag) !== false) {
                        return true;
                    }
                }
            }
            return false;
        });
        $eventsFromDb = $this->evenementsModel->findAll();
        $events = [];
        $currentDate = new \DateTime();
        $currentDate->setTime(23, 59, 59);

        // On va utiliser $allPosts pour regrouper les posts destinés au carousel
        $allPosts = [];

        // Traitement des posts Facebook
        foreach ($filteredPosts as &$post) { // Utilisation de "&" pour modifier directement le post
            $eventDate = null;
            if (preg_match('/(\d{2}\/\d{2}\/\d{4})/', $post['message'], $matches)) {
                $eventDate = $matches[1];
            }

            if (!$eventDate) {
                continue; // Si pas de date trouvée, on ignore ce post
            }
            list($day, $month, $year) = explode('/', $eventDate);
            if (!checkdate($month, $day, $year)) {
                continue; // Ignorer les dates invalides
            }
            $eventDateTime = new \DateTime("$year-$month-$day");
            $eventDateTime->setTime(23, 59, 59);

            if ($eventDateTime < $currentDate) {
                continue; // Ignore les événements dans le passé
            }
            preg_match('/\*(.*?)\*/', $post['message'], $titleMatches);
            $eventTitle = isset($titleMatches[1]) ? $titleMatches[1] : substr($post['message'], 0, 50);

            $imageUrl = null;
            if (isset($post['attachments']['data'][0]['media']['image']['src'])) {
                $imageUrl = $post['attachments']['data'][0]['media']['image']['src'];
            }

            // Ajouter l'événement pour le calendrier
            $events[] = [
                'title' => $eventTitle,
                'start' => $eventDate, // Format DD/MM/YYYY (sera converti côté JS si nécessaire)
                'image' => $imageUrl,
                'id' => $post['id'] ?? uniqid()
            ];

            // Enrichir le post pour le carousel
            $post['date'] = $eventDate;
            $post['image'] = $imageUrl;
            $post['titre'] = $eventTitle;
            $post['id'] = $post['id'] ?? uniqid();

            // Ajouter le post Facebook au tableau global du carousel
            $allPosts[] = $post;
        }
        // Traitement des événements provenant de la base de données
        foreach ($eventsFromDb as $eventFromDb) {
            // Assurer que la date est bien au format 'YYYY-MM-DD'
            $eventDate = $eventFromDb['date'];
            if (strpos($eventDate, '/') !== false) {
                // Si la date est en format 'DD/MM/YYYY', la convertir au format 'YYYY-MM-DD'
                $eventDate = date('Y-m-d', strtotime(str_replace('/', '-', $eventDate)));
            }

            // Créer un objet DateTime pour la date de l'événement
            $eventDateTime = new \DateTime($eventDate);
            $eventDateTime->setTime(23, 59, 59);  // Assure-toi que l'heure est à la fin de la journée

            // Comparer avec la date actuelle (si l'événement est dans le passé, on l'ignore)
            if ($eventDateTime < $currentDate) {
                continue;  // Ignorer les événements dans le passé
            }

            // Ajouter l'événement pour le calendrier
            $events[] = [
                'title' => $eventFromDb['titre'],  // Titre de l'événement
                'start' => $eventDate,  // Date au format 'YYYY-MM-DD'
                'image' => $eventFromDb['image'],  // Image associée à l'événement
                'id' => $eventFromDb['idEvenement'] ?? uniqid()  // ID de l'événement
            ];

            // Préparer le post pour le carousel
            $postDb = [];
            $postDb['date'] = $eventFromDb['date']; // Ici, la date peut être 'YYYY-MM-DD'
            $postDb['titre'] = $eventFromDb['titre'];
            $postDb['image'] = $eventFromDb['image'];
            $postDb['id'] = $eventFromDb['idEvenement'] ?? uniqid();

            // Ajouter le post issu de la base au tableau global du carousel
            $allPosts[] = $postDb;
        }

        // On transmet $allPosts pour le carousel et $events pour le calendrier
        return view('calendrier', ['events' => $events, 'posts' => $allPosts]);
    }

}
