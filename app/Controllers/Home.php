<?php

namespace App\Controllers;

use App\Libraries\CallApi;
use App\Libraries\FacebookCache;

class Home extends BaseController
{
    private $associationModel;
    private $facebookModel;
    private $facebookCache;
    private $callApi;
    public function __construct()
    {
        $this->associationModel = model('Association');
        $this->facebookModel = model('Facebook');
        $this->callApi = new CallApi();
        $this->facebookCache = new FacebookCache();
    }

    public function index()
    {
        try {
            $code = $this->request->getGet('code');

            if ($code) {
                // Informations nécessaires pour l'authentification Facebook
                $clientId = '603470049247384';
                $clientSecret = '4b2b340247ca62ea1aebc5adb347a359';
                $redirectUri = base_url(); // URL de redirection
                $authorizedFacebookId = '122101030388738715'; // ID utilisateur autorisé

                // Construire l'URL pour échanger le code contre un token
                $tokenUrl = "https://graph.facebook.com/v21.0/oauth/access_token?"
                    . "client_id={$clientId}&redirect_uri={$redirectUri}&client_secret={$clientSecret}&code={$code}";

                // Appel API pour récupérer le token
                $response = $this->callApi->callApi($tokenUrl);

                if (!$response) {
                    return redirect()->to('/')->with('error', 'Erreur lors de la récupération du token.');
                }

                // Vérifier si le token d'accès est présent
                if (isset($response['access_token'])) {
                    $accessToken = $response['access_token'];

                    // Récupérer les informations de l'utilisateur
                    $userInfo = $this->callApi->callApi("https://graph.facebook.com/me?fields=id,name&access_token={$accessToken}");
                    if (isset($userInfo['id']) && $userInfo['id'] === $authorizedFacebookId) {
                        // Vérifier les informations du token (validité et expiration)
                        $tokenInfo = $this->callApi->callApi("https://graph.facebook.com/debug_token?input_token={$accessToken}&access_token={$accessToken}");

                        if (isset($tokenInfo['data']['expires_at'])) {
                            $expirationTimestamp = $tokenInfo['data']['expires_at'];
                            $expirationDateDay = date('Y-m-d', $expirationTimestamp);
                            $expirationDate = date('Y-m-d H:i:s', $expirationTimestamp);
                            $daysRemaining = ceil(($expirationTimestamp - time()) / (60 * 60 * 24));

                            // Mise à jour du token dans la base de données
                            $this->associationModel->update(1, [
                                'tokenFacebook' => $accessToken,
                                'tokenExpirationDate' => $expirationDate
                            ]);

                            return redirect()->to('/')->with('success', "Le token a bien été renouvelé ! Il est valide jusqu'au {$expirationDateDay}, soit encore {$daysRemaining} jour(s).");
                        } else {
                            return redirect()->to('/')->with('error', 'Erreur lors de la récupération des informations de validité du token.');
                        }
                    } else {
                        return redirect()->to('/')->with('error', 'Accès refusé');
                    }
                }

                // Si une erreur survient dans la réponse de Facebook
                return redirect()->to('/')->with('error', 'Erreur dans la réponse de Facebook.');
            }

            // Récupérer les posts depuis le cache ou API
            $posts = $this->facebookCache->getFacebookPosts();

            // Vérifier si la réponse contient une erreur
            if (isset($posts['error'])) {
                // En cas d'erreur, afficher un message explicite et éviter de planter
                $logo = $this->associationModel->find(1);
                return view('accueil', ['logo' => $logo, 'posts' => $posts]);
            }

            // Vérifier si les données existent et sont valides
            if (isset($posts['data']) && is_array($posts['data'])) {
                $posts = $posts['data'];
            } else {
                // Si "data" est manquant ou non valide
                $logo = $this->associationModel->find(1);
                return view('accueil', ['logo' => $logo, 'posts' => $posts]);
            }

            // Récupérer le logo
            $logo = $this->associationModel->find(1);
            return view('accueil', ['logo' => $logo, 'posts' => $posts]);
        } catch (\Exception $e) {
            // Log de l'erreur pour aider au débogage
            log_message('error', 'Erreur lors du traitement de la demande : ' . $e->getMessage());
            return redirect()->to('/')->with('error', 'Une erreur est survenue. Veuillez réessayer plus tard.');
        }
    }
}
