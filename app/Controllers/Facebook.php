<?php

namespace App\Controllers;

use App\Libraries\FacebookCache;
use DateTime;


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
    public function check_and_send_email()
    {
        // Récupérer les informations du token
        $association = $this->associationModel->find(1);
        $email = $association['mailContact']; // Utilisation de l'email du contact
    
        // Vérifier si l'association existe
        if ($association) {
            // Récupérer la date d'expiration du token et l'actuelle
            $expiration_date = new DateTime($association['tokenExpirationDate']);
            $current_date = new DateTime();
    
            // Calculer l'intervalle de jours entre la date actuelle et la date d'expiration
            $interval = $current_date->diff($expiration_date);
            $remainingDays = $interval->days; // Nombre de jours restants
    
            // Si l'expiration est dans des jours valides (10, 5, 1) en fonction du cron job
            if (in_array($remainingDays, [10, 5, 1])) {
                // Appel de la méthode login pour générer l'URL de renouvellement
                $renewLink = $this->login();
    
                // Préparer le message HTML à envoyer avec le lien de renouvellement
                $htmlMessage = "
                    <html>
                    <head>
                        <style>
                            body {
                                font-family: 'Arial', sans-serif;
                                margin: 0;
                                padding: 0;
                                background-color: #f4f4f4;
                            }
                            .container {
                                width: 100%;
                                max-width: 600px;
                                margin: 0 auto;
                                background-color: #ffffff;
                                padding: 20px;
                                border-radius: 8px;
                                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                            }
                            h3 {
                                color: #333333;
                                text-align: center;
                                font-size: 24px;
                                margin-bottom: 20px;
                            }
                            p {
                                font-size: 16px;
                                color: #555555;
                                margin: 10px 0;
                            }
                            .link {
                                font-size: 16px;
                                color: #007BFF;
                                text-decoration: none;
                            }
                        </style>
                    </head>
                    <body>
                        <div class='container'>
                            <h3>Notification d'expiration du token</h3>
                            <p>Votre token expire dans <strong>{$remainingDays}</strong> jours. Veuillez le renouveler bientôt.</p>
                            <p><a href='{$renewLink}' class='link'>Cliquez ici pour renouveler votre token</a></p>
                        </div>
                    </body>
                    </html>
                ";
    
                // Configuration de l'email
                $emailService = \Config\Services::email();
                $emailService->setFrom('noreply@laplume.com', 'Support');
                $emailService->setTo($email); // Utilisation de la variable $email
                $emailService->setSubject('Expiration imminente de votre token');
                $emailService->setMessage($htmlMessage);
                $emailService->setMailType('html');
    
                // Envoi de l'email
                if ($emailService->send()) {
                    log_message('info', 'Email envoyé avec succès à ' . $email);
                } else {
                    log_message('error', 'Erreur lors de l\'envoi de l\'email pour l\'utilisateur ' . $email);
                }
            }
        } else {
            log_message('error', 'Aucune association trouvée pour l\'ID spécifié.');
        }
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
