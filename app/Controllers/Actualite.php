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

            // Vérification que les données sont valides
            if (!is_array($posts)) {
                log_message('error', 'Aucun post récupéré depuis Facebook ou structure incorrecte.');
                return view('faitMarquant', ['posts' => []]);
            }

            // Extraction des données si nécessaire
            $postsData = isset($posts['data']) && is_array($posts['data']) ? $posts['data'] : $posts;

            // Récupération des hashtags depuis la base de données
            $hashtags = array_column(
                $this->facebookModel->where('pageName', 'faitmarquant')->findAll(),
                'hashtag'
            );

            if (empty($hashtags)) {
                log_message('error', 'Aucun hashtag trouvé dans la base de données.');
                return view('faitMarquant', ['posts' => []]);
            }

            // Filtrage des posts pour ne garder que ceux contenant un hashtag de la liste
            $filteredPosts = array_filter($postsData, function ($post) use ($hashtags) {
                return isset($post['message']) && array_filter($hashtags, fn($tag) => strpos($post['message'], $tag) !== false);
            });

            if (empty($filteredPosts)) {
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

