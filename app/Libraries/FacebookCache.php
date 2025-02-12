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
            $scope = env('FACEBOOK_SCOPE');
            $posts = $this->callApi->callApi("https://graph.facebook.com/me/feed?fields=id,message,created_time,permalink_url,attachments&access_token={$tokenFacebook['tokenFacebook']}");

            // Mettre en cache les nouvelles données
            file_put_contents($this->cacheFile, json_encode($posts));

            return $posts;
        }
    }
    public function clearCache(): void
    {
        if (file_exists($this->cacheFile)) {
            unlink($this->cacheFile);  // Supprimer le fichier de cache
        }
    }
}
