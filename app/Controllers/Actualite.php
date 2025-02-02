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

        $photosFacebook = [];
        foreach ($filteredPosts as $post) {
            // Vérifier les pièces jointes principales
            if (isset($post['attachments']['data'][0]['media']['image']['src'])) {
                $imageSrc = $post['attachments']['data'][0]['media']['image']['src'];
                if (!in_array($imageSrc, $photosFacebook)) {
                    $photosFacebook[] = $imageSrc;
                }
            }

            // Vérifier les subattachments
            if (isset($post['attachments']['data'][0]['subattachments']['data'])) {
                foreach ($post['attachments']['data'][0]['subattachments']['data'] as $subattachment) {
                    if (isset($subattachment['media']['image']['src'])) {
                        $imageSrc = $subattachment['media']['image']['src'];
                        if (!in_array($imageSrc, $photosFacebook)) {
                            $photosFacebook[] = $imageSrc;
                        }
                    }
                }
            }
        }
        // Afficher les photos (exemple)
        foreach ($photosFacebook as $photo) {
            echo "<img src='{$photo}' alt='Photo Facebook' />";
        }

        return view('faitMarquant', ['posts' => $filteredPosts]);
    }


}

