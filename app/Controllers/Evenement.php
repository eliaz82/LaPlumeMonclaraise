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

        // Ajouter l'image (si présente) à chaque post
        foreach ($filteredPosts as &$post) {
            if (isset($post['attachments']['data'][0]['media']['image']['src'])) {
                $post['image'] = $post['attachments']['data'][0]['media']['image']['src'];
            } else {
                $post['image'] = null; // Pas d'image associée
            }
        }



        // Afficher la vue de l'événement en lui passant les données de l'événement trouvé
        return view('evenements', [
            'posts'       => $filteredPosts,
            'highlightId' => $id  // Peut être null si aucun ID n'est fourni
        ]);
    }
}
