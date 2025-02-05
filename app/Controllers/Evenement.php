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
        // Récupération des posts et des hashtags
        $posts      = $this->facebookCache->getFacebookPosts();
        $hashtags   = $this->facebookModel->where('pageName', 'evenementCalendrier')->findAll();
        $hashtagList = array_column($hashtags, 'hashtag');

        // Filtrer les posts qui contiennent l'un des hashtags
        $filteredPosts = array_filter($posts['data'], function ($post) use ($hashtagList) {
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

        // Date actuelle fixée à 23:59:59
        $currentDate = new \DateTime();
        $currentDate->setTime(23, 59, 59);

        // Traitement de chaque post
        foreach ($filteredPosts as &$post) {
            $post['image'] = $post['attachments']['data'][0]['media']['image']['src'] ?? null;
            preg_match('/\*(.*?)\*/', $post['message'], $matches);
            $post['titre'] = $matches[1] ?? substr($post['message'], 0, 50);

            if (preg_match('/(\d{2}\/\d{2}\/\d{4})/', $post['message'], $matches)) {
                list($day, $month, $year) = explode('/', $matches[1]);
                if (checkdate($month, $day, $year)) {
                    $post['date'] = $matches[1];
                    $eventDateTime = new \DateTime("$year-$month-$day");
                    $eventDateTime->setTime(23, 59, 59);
                    $post['status'] = ($eventDateTime < $currentDate) ? "Événement passé" : "Événement futur";
                }
            } else {
                $post['status'] = "Date inconnue";
            }
        }

        // Seuil pour afficher les événements futurs ou passés depuis moins d'1 jour
        $threshold = (clone $currentDate)->sub(new \DateInterval('P1D'));
        // Seuil pour afficher les événements futurs ou passés depuis moins d'1 mois
        //$threshold = (clone $currentDate)->sub(new \DateInterval('P1M'));
        $filteredPosts = array_filter($filteredPosts, function ($post) use ($threshold) {
            if (isset($post['date'])) {
                list($day, $month, $year) = explode('/', $post['date']);
                $eventDateTime = new \DateTime("$year-$month-$day");
                $eventDateTime->setTime(23, 59, 59);
                return $eventDateTime >= $threshold;
            }
            return true;
        });

        return view('evenements', [
            'posts'       => $filteredPosts,
            'highlightId' => $id,
        ]);
    }
}
