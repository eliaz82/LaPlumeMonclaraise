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

    public function login()
    {
        $clientId = '603470049247384';
        $redirectUri = base_url();
        $scope = 'public_profile,user_posts';
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

            // Insérer le hashtag dans la base de données
            $this->facebookModel->insert([
                'hashtag' => $hashtag,
                'pageName' => $pageName
            ]);

            $hashtags = $this->facebookModel->getHashtags();

            return $this->response->setJSON([
                'success' => true,
                'hashtags' => $hashtags
            ]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Données mal formatées']);
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
}
