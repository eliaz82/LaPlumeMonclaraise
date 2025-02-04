<?php

namespace App\Controllers;
use App\Libraries\FacebookCache;
class Actualite extends BaseController
{
    private $facebookModel;
    private $facebookCache;

    public function __construct()
    {
        $this->facebookModel = model('Facebook');
        $this->facebookCache = new FacebookCache();
    }
    public function actualite(): string
    {
        //Pour récupérer les posts et les images
        $posts = $this->facebookCache->getFacebookPosts();

        $hashtags = $this->facebookModel->where('pageName', 'faitmarquant')->findAll();
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

        foreach ($filteredPosts as &$post) { // Utilisation de & pour modifier le tableau
            $post['image'] = null; // Par défaut, pas d'image
    
            // Vérifier les pièces jointes principales
            if (isset($post['attachments']['data'][0]['media']['image']['src'])) {
                $post['image'] = $post['attachments']['data'][0]['media']['image']['src'];
            }
    
            // Vérifier les subattachments (plusieurs images)
            if (isset($post['attachments']['data'][0]['subattachments']['data'])) {
                foreach ($post['attachments']['data'][0]['subattachments']['data'] as $subattachment) {
                    if (isset($subattachment['media']['image']['src'])) {
                        $post['image'] = $subattachment['media']['image']['src']; // Prend la première trouvée
                        break;
                    }
                }
            }
        }

        return view('faitMarquant', ['posts' => $filteredPosts]);
    }


}

