<?php

namespace App\Controllers;

use App\Libraries\CallApi;

class Facebook extends BaseController
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

    // Fonction pour récupérer les posts depuis Facebook
    public function getFacebookPosts()
    {
        $tokenFacebook = $this->facebookModel->find(1);

        // Vérifier si le token Facebook est disponible
        if (!$tokenFacebook || empty($tokenFacebook['tokenFacebook'])) {
            die("Erreur : Token Facebook non trouvé !");
        }

        // Construire l'URL pour récupérer les posts
        $url = "https://graph.facebook.com/me/feed?fields=id,message,created_time,permalink_url&access_token={$tokenFacebook['tokenFacebook']}";

        // Appeler l'API pour récupérer les posts
        $posts = $this->callApi->callApi($url);

        // Vérifier si la réponse est valide (JSON)
        $data = json_decode($posts, true);
dd($posts);
        if (json_last_error() !== JSON_ERROR_NONE) {
            die("Erreur lors du décodage JSON : " . json_last_error_msg());
        }

        // Vérifier s'il y a des erreurs dans la réponse de l'API
        if (isset($data['error'])) {
            die("Erreur API Facebook : " . $data['error']['message']);
        }

        // Debug : afficher la réponse de l'API pour vérifier la structure
        log_message('debug', 'Réponse de l\'API Facebook : ' . print_r($data, true));

        return $data['data'] ?? []; // Retourne un tableau de posts ou un tableau vide
    }


    // Filtrage des posts par hashtags enregistrés
    public function filterPostsByHashtag()
    {
        // Récupérer les hashtags enregistrés et leurs pages associées
        $registeredHashtags = $this->facebookModel->select('hastag, pageName')->findAll();

        // Récupérer les posts Facebook
        $posts = $this->getFacebookPosts();
        $filteredPosts = [];

        foreach ($posts as $post) {
            if (!isset($post['message'])) {
                continue; // Ignore les posts sans message
            }

            // Filtrer les posts par hashtags
            foreach ($registeredHashtags as $entry) {
                $hashtag = trim($entry['hastag'], "#"); // Supprimer # si présent en base de données
                $pageName = $entry['pageName'];

                if (stripos($post['message'], "#$hashtag") !== false) {
                    $filteredPosts[$pageName][] = $post;
                }
            }
        }

        return $filteredPosts;
    }

    // Affichage de la vue des posts filtrés par page
    public function showView($pageName)
    {
        $filteredPosts = $this->filterPostsByHashtag();
        $posts = $filteredPosts[$pageName] ?? []; // Récupérer les posts filtrés pour la page demandée

        return view('facebook/' . $pageName, [
            'posts' => $posts,
            'pageName' => $pageName // Assurer que la variable pageName est envoyée à la vue
        ]);
    }

}
