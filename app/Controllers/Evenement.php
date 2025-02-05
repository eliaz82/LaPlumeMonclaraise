<?php

namespace App\Controllers;

use App\Libraries\FacebookCache;

class Evenement extends BaseController
{
    private $facebookModel;
    private $facebookCache;

    public function __construct()
    {
        $this->facebookModel = model('Facebook');
        $this->facebookCache = new FacebookCache();
    }
    public function evenement(string $id = null): string
    {
        // Récupération des posts depuis Facebook
        $posts = $this->facebookCache->getFacebookPosts();
        $hashtags = $this->facebookModel->where('pageName', 'evenementCalendrier')->findAll();
        $hashtagList = array_column($hashtags, 'hashtag');

        // Filtrer les posts pour ne conserver que ceux qui contiennent l'un des hashtags
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
        $currentDate = new \DateTime();
        $currentDate->setTime(23, 59, 59);
        $limitDate = new \DateTime();
        $limitDate->add(new \DateInterval('P10D')); // Ajouter 10 jours
        // Ajouter l'image (si présente) à chaque post
        foreach ($filteredPosts as &$post) {
            $post['image'] = $post['attachments']['data'][0]['media']['image']['src'] ?? null;

            // Extraction du titre (entre *...*)
            preg_match('/\*(.*?)\*/', $post['message'], $titleMatches);
            $post['titre'] = isset($titleMatches[1]) ? $titleMatches[1] : substr($post['message'], 0, 50);

            $eventDate = null;
            if (preg_match('/(\d{2}\/\d{2}\/\d{4})/', $post['message'], $matches)) {
                $eventDate = $matches[1];
            }

            if ($eventDate) {
                list($day, $month, $year) = explode('/', $eventDate);
                if (checkdate($month, $day, $year)) {
                    $post['date'] = $eventDate;

                    // Comparer la date avec aujourd'hui
                    $eventDateTime = new \DateTime("$year-$month-$day");
                    $eventDateTime->setTime(23, 59, 59);

                    if ($eventDateTime < $currentDate) {
                        $post['status'] = "Événement passé";
                    } else {
                        $post['status'] = "Événement futur";
                    }
                }
            } else {
                $post['status'] = "Date inconnue";
            }
        }


        // Afficher la vue de l'événement en lui passant les données de l'événement trouvé
        return view('evenements', [
            'posts'       => $filteredPosts,
            'highlightId' => $id  // Peut être null si aucun ID n'est fourni
        ]);
    }
}
