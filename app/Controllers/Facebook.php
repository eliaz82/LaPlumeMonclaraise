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
        // Validation du nom de la page
        if (empty($pageName) || !is_string($pageName)) {
            return $this->response->setJSON(['error' => 'Nom de la page invalide.'], 400);
        }

        // Recherche des hashtags pour la page spécifiée
        try {
            $hashtags = $this->facebookModel
                ->where('pageName', $pageName)
                ->select('idFacebook, hashtag')
                ->findAll();

            // Vérifier si des résultats ont été trouvés
            if (empty($hashtags)) {
                return $this->response->setJSON(['message' => 'Aucun hashtag trouvé pour cette page.'], 404);
            }

            // Retourner les hashtags en JSON
            return $this->response->setJSON($hashtags);
        } catch (\Exception $e) {
            // Gestion des erreurs en cas d'échec de la requête
            return $this->response->setJSON(['error' => 'Une erreur est survenue lors de la récupération des hashtags.'], 500);
        }
    }


    public function create()
    {
        // Récupérer les données JSON envoyées par le client
        $jsonData = $this->request->getJSON();

        if ($jsonData) {
            // Vérification que les champs nécessaires sont présents
            if (empty($jsonData->hashtag) || empty($jsonData->pageName)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Le hashtag ou le nom de la page est manquant.'
                ], 400); // Code 400 pour mauvaise requête
            }

            $hashtag = $jsonData->hashtag;
            $pageName = $jsonData->pageName;

            // Vérification du format du hashtag
            if (strpos($hashtag, '#') !== 0) {
                $hashtag = '#' . $hashtag; // Ajouter le "#" au début du hashtag si nécessaire
            }

            // Vérification si le hashtag existe déjà pour cette page
            $existingHashtag = $this->facebookModel
                ->where('pageName', $pageName)
                ->where('hashtag', $hashtag)
                ->first();

            if ($existingHashtag) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Ce hashtag est déjà associé à cette page.'
                ], 409); // Code 409 pour conflit (doublon)
            }

            // Insérer le hashtag dans la base de données et récupérer l'ID généré
            try {
                $id = $this->facebookModel->insert([
                    'hashtag' => $hashtag,
                    'pageName' => $pageName
                ], true); // Le paramètre "true" permet de récupérer l'ID inséré

                if ($id) {
                    // Retourner une réponse avec l'ID du nouveau hashtag
                    return $this->response->setJSON([
                        'success' => true,
                        'idFacebook' => $id,
                        'hashtag' => $hashtag,
                        'pageName' => $pageName
                    ]);
                } else {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Erreur lors de l\'ajout du hashtag'
                    ], 500); // Code 500 pour erreur serveur
                }
            } catch (\Exception $e) {
                // Gestion des erreurs en cas de problème d'insertion
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Une erreur est survenue lors de l\'ajout du hashtag : ' . $e->getMessage()
                ], 500);
            }
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Données mal formatées'
            ], 400); // Code 400 pour mauvaise requête
        }
    }



    public function delete($id)
    {
        // Vérification de l'ID : doit être un nombre entier positif
        if (empty($id) || !is_numeric($id) || $id <= 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID invalide ou manquant'
            ], 400); // Code 400 pour mauvaise requête
        }

        // Vérification si le hashtag existe avant de tenter la suppression
        $hashtag = $this->facebookModel->find($id);

        if (!$hashtag) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Hashtag non trouvé pour cet ID'
            ], 404); // Code 404 pour non trouvé
        }

        // Tentative de suppression du hashtag
        try {
            $deleted = $this->facebookModel->delete($id);

            if ($deleted) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Hashtag supprimé avec succès'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Erreur lors de la suppression'
                ], 500); // Code 500 pour erreur serveur
            }
        } catch (\Exception $e) {
            // Gestion des erreurs d'exception
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la suppression : ' . $e->getMessage()
            ], 500);
        }
    }


    public function getTokenExpirationDate()
    {
        try {
            // Récupérer la date d'expiration du token
            $tokenExpiration = $this->associationModel->find(1);

            // Vérifier si la donnée existe
            if ($tokenExpiration && isset($tokenExpiration['tokenExpirationDate'])) {
                return $this->response->setJSON([
                    'success' => true,
                    'expiration_date' => $tokenExpiration['tokenExpirationDate']
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Date d\'expiration du token non trouvée'
                ], 404); // Code 404 pour non trouvé
            }
        } catch (\Exception $e) {
            // Gestion des erreurs en cas de problème avec la requête
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la récupération de la date d\'expiration du token : ' . $e->getMessage()
            ], 500); // Code 500 pour erreur serveur
        }
    }


    public function refresh()
    {
        try {
            // Effacer le cache Facebook
            $this->facebookCache->clearCache();

            // Recréer le cache des posts Facebook
            $this->facebookCache->getFacebookPosts();

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Le cache a été rafraîchi avec succès.'
            ]);
        } catch (\Exception $e) {
            // Gestion des erreurs en cas de problème lors de l'effacement ou de la récupération du cache
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Une erreur est survenue lors du rafraîchissement du cache : ' . $e->getMessage()
            ], 500); // Code 500 pour erreur serveur
        }
    }
}
