<?php

namespace App\Controllers;

use App\Libraries\FacebookCache;

class Calendrier extends BaseController
{
    private $facebookModel;
    private $facebookCache;
    public function __construct()
    {
        $this->facebookModel = model('Facebook');
        $this->facebookCache = new FacebookCache();
    }
    public function calendrier(): string
    {
        $posts = $this->facebookCache->getFacebookPosts();
        $hashtags = $this->facebookModel->where('pageName', 'calendrier')->findAll();
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
    
        $events = [];
        
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
    
            preg_match('/\*(.*?)\*/', $post['message'], $titleMatches);
            $eventTitle = isset($titleMatches[1]) ? $titleMatches[1] : substr($post['message'], 0, 50);

            $imageUrl = null;
            if (isset($post['attachments']['data'][0]['media']['image']['src'])) {
                $imageUrl = $post['attachments']['data'][0]['media']['image']['src'];
            }
    
            // Ajouter l'événement
            $events[] = [
                'title' => $eventTitle,
                'start' => $eventDate,
                'image' => $imageUrl
            ];
    
            // Ajouter la date directement dans le post
            $post['date'] = $eventDate;
            $post['image'] = $imageUrl;
        }
    
        return view('calendrier', ['events' => $events, 'posts' => $filteredPosts]);
    }
    
    
    
}
