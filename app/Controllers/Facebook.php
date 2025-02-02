<?php

namespace App\Controllers;
use App\Libraries\FacebookCache;

class Facebook extends BaseController
{
    private $associationModel;
    private $facebookModel;
    private $facebookCache;

    public function __construct()
    {
        $this->associationModel = model('Association');
        $this->facebookModel = model('Facebook');
        $this->facebookCache = new FacebookCache();
    }

    public function login()
    {
        $clientId = '603470049247384';
        $redirectUri = base_url();
        $scope = 'public_profile,user_posts,user_videos,user_photos';
        $url = "https://www.facebook.com/v21.0/dialog/oauth?"
            . "client_id={$clientId}&redirect_uri={$redirectUri}&scope={$scope}&response_type=code";

        return redirect()->to($url);
    }

    public function getHashtagsByPage($pageName)
    {
        $hashtags = $this->facebookModel
            ->where('pageName', $pageName)
            ->select('idFacebook, hashtag')
            ->findAll();

        return $this->response->setJSON($hashtags);
    }

    public function create()
    {
        $jsonData = $this->request->getJSON();

        if ($jsonData) {
            $hashtag = $jsonData->hashtag;
            $pageName = $jsonData->pageName;
            
            if ($hashtag[0] !== '#') {
                $hashtag = '#' . $hashtag;
            }
            // Insérer et récupérer l'ID généré
            $id = $this->facebookModel->insert([
                'hashtag' => $hashtag,
                'pageName' => $pageName
            ], true); // Le second paramètre "true" permet de récupérer l'ID inséré

            if ($id) {
                return $this->response->setJSON([
                    'success' => true,
                    'idFacebook' => $id, // Envoie l'ID du nouveau hashtag
                    'hashtag' => $hashtag
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => "Erreur lors de l'ajout du hashtag"
                ]);
            }
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Données mal formatées'
            ]);
        }
    }


    public function delete($id)
    {
        // Vérification de l'ID
        if (empty($id)) {
            return $this->response->setJSON(['success' => false, 'message' => 'ID invalide']);
        }
        $deleted = $this->facebookModel->delete($id);

        if ($deleted) {
            return $this->response->setJSON(['success' => true]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Erreur lors de la suppression']);
        }
    }

    public function getTokenExpirationDate()
    {
        $tokenExpiration = $this->associationModel->find(1);
        if ($tokenExpiration) {
            return $this->response->setJSON([
                'success' => true,
                'expiration_date' => $tokenExpiration['tokenExpirationDate']
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Token non trouvé'
            ]);
        }
    }
    public function getPosts()
    {
        $posts = $this->facebookCache->getFacebookPosts();
        return $this->response->setJSON(['posts' => $posts['data'] ?? []]);
    }
    public function refresh()
    {
        $this->facebookCache->clearCache(); // Supprimer le cache
        $this->facebookCache->getFacebookPosts(); // Recréer le cache

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Cache Facebook rafraîchi !'
        ]);
    }
}
