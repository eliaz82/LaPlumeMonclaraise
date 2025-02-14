<?php

namespace App\Libraries;

use App\Models\Association;
use App\Libraries\CallApi;

class FacebookCache
{
    private $associationModel;
    private $callApi;
    private $cacheFile;

    public function __construct()
    {
        $this->associationModel = new Association();
        $this->callApi = new CallApi();
        $this->cacheFile = WRITEPATH . 'cache/facebook_posts.json';  // Fichier de cache
    }

    public function getFacebookPosts(): array
    {
        // Vérifier si le cache existe et s'il est valide

        $cacheDuration = 24 * 60 * 60; // Cache de 24 heures (en secondes)

        if (file_exists($this->cacheFile) && (filemtime($this->cacheFile) + $cacheDuration) > time()) {
            // Si le cache est encore valide, lire les données depuis le cache
            return json_decode(file_get_contents($this->cacheFile), true);
        } else {
            // Si le cache n'existe pas ou est expiré, appeler l'API Facebook
            $tokenFacebook = $this->associationModel->find(1);  // Obtenir le token de Facebook
            $pageId = env('FACEBOOK_PAGE_ID');

            // Initialiser la variable de cache et l'URL initiale de l'API
            $allPosts = [];
            $url = "https://graph.facebook.com/{$pageId}/feed?fields=id,message,created_time,permalink_url,attachments.limit(100){media,target,subattachments.limit(100){media,target,title,type,url}}&access_token={$tokenFacebook['tokenFacebook']}";

            // Récupérer toutes les pages de résultats
            while ($url) {
                // Appeler l'API Facebook pour récupérer les posts
                $posts = $this->callApi->callApi($url);

                // Ajouter les posts récupérés à la variable $allPosts
                $allPosts = array_merge($allPosts, $posts['data']);

                // Vérifier s'il existe une page suivante
                if (isset($posts['paging']['next'])) {
                    // Mettre à jour l'URL avec le lien de la page suivante
                    $url = $posts['paging']['next'];
                } else {
                    // Si aucune page suivante, arrêter la boucle
                    $url = null;
                }
            }

            // Mettre en cache les nouvelles données
            file_put_contents($this->cacheFile, json_encode($allPosts));

            return $allPosts;

        }
    }
    public function clearCache(): void
    {
        if (file_exists($this->cacheFile)) {
            unlink($this->cacheFile);  // Supprimer le fichier de cache
        }
    }
}
