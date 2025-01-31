<?php

namespace App\Controllers;
use App\Libraries\CallApi;
class Actualite extends BaseController
{
    private $associationModel;
    private $facebookModel;
    private $callApi;
    public function __construct()
    {
        $this->associationModel = model('Association');
        $this->facebookModel = model('Facebook');
        $this->callApi = new CallApi();
    }
    public function actualite(): string
    {
        $tokenFacebook = $this->associationModel->find(1);
        // Appels à l'API pour récupérer les posts et les images
        $posts = $this->callApi->callApi("https://graph.facebook.com/me/feed?fields=id,message,created_time,permalink_url,attachments&access_token={$tokenFacebook['tokenFacebook']}");
       
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

