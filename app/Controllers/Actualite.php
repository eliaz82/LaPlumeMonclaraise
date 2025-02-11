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
    try {
        // Récupération des posts depuis le cache Facebook
        $posts = $this->facebookCache->getFacebookPosts();
        
        // Vérification que les données sont présentes
        if (empty($posts) || !isset($posts['data'])) {
            // Log l'erreur mais continue d'afficher la vue normalement
            log_message('error', 'Aucun post récupéré depuis Facebook.');
            return view('faitMarquant', ['posts' => []]);
        }

        // Récupération des hashtags depuis la base de données
        $hashtags = $this->facebookModel->where('pageName', 'faitmarquant')->findAll();

        // Vérification si les hashtags ont bien été récupérés
        if (empty($hashtags)) {
            // Log l'erreur mais continue d'afficher la vue normalement
            log_message('error', 'Aucun hashtag trouvé dans la base de données.');
            return view('faitMarquant', ['posts' => []]);
        }

        // Extraire les hashtags dans un tableau
        $hashtagList = array_column($hashtags, 'hashtag');

        // Filtrage des posts pour ne garder que ceux contenant un hashtag de la liste
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

        // Vérification si des posts ont été filtrés
        if (empty($filteredPosts)) {
            // Log l'erreur mais continue d'afficher la vue normalement
            log_message('error', 'Aucun post ne correspond aux hashtags définis.');
            return view('faitMarquant', ['posts' => []]);
        }

        // Traitement des posts pour ajouter les images
        foreach ($filteredPosts as &$post) {
            $post['image'] = null; // Par défaut, pas d'image

            // Vérification des pièces jointes principales
            if (isset($post['attachments']['data'][0]['media']['image']['src'])) {
                $post['image'] = $post['attachments']['data'][0]['media']['image']['src'];
            }

            // Vérification des subattachments (plusieurs images)
            if (isset($post['attachments']['data'][0]['subattachments']['data'])) {
                foreach ($post['attachments']['data'][0]['subattachments']['data'] as $subattachment) {
                    if (isset($subattachment['media']['image']['src'])) {
                        $post['image'] = $subattachment['media']['image']['src']; // Prend la première trouvée
                        break;
                    }
                }
            }
        }

        // Retourner la vue avec les posts filtrés
        return view('faitMarquant', ['posts' => $filteredPosts]);

    } catch (\Exception $e) {
        // Log toutes les exceptions
        log_message('error', 'Erreur inconnue : ' . $e->getMessage());
        // Si erreur générale, on continue d'afficher la vue sans posts
        return view('faitMarquant', ['posts' => []]);
    }
}



}

