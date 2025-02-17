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
                $clientId = env('FACEBOOK_CLIENT_ID');
                $clientSecret = env('FACEBOOK_CLIENT_SECRET');
                $authorizedFacebookId = env('FACEBOOK_AUTHORIZED_USER_ID'); // ID utilisateur autorisé
                $redirectUri = base_url(); // URL de redirection

                // Échange du code contre un token utilisateur
                $tokenUrl = "https://graph.facebook.com/v21.0/oauth/access_token?"
                    . "client_id={$clientId}&redirect_uri={$redirectUri}&client_secret={$clientSecret}&code={$code}";

                $response = $this->callApi->callApi($tokenUrl);

                if (!$response || !isset($response['access_token'])) {
                    return redirect()->to('/')->with('error', 'Erreur lors de la récupération du token.');
                }

                $userAccessToken = $response['access_token'];

                // Récupérer les informations de l'utilisateur
                $userInfo = $this->callApi->callApi("https://graph.facebook.com/me?fields=id,name&access_token={$userAccessToken}");

                if (!isset($userInfo['id']) || $userInfo['id'] !== $authorizedFacebookId) {
                    return redirect()->to('/')->with('error', 'Accès refusé');
                }

                // Vérifier la validité du token utilisateur en récupérant data_access_expires_at
                $tokenInfo = $this->callApi->callApi("https://graph.facebook.com/debug_token?input_token={$userAccessToken}&access_token={$userAccessToken}");
                if (!isset($tokenInfo['data']['data_access_expires_at'])) {
                    return redirect()->to('/')->with('error', 'Erreur lors de la récupération des informations de validité du token.');
                }

                $expirationTimestamp = $tokenInfo['data']['data_access_expires_at'];
                $expirationDateDay = date('Y-m-d', $expirationTimestamp);
                $expirationDate = date('Y-m-d H:i:s', $expirationTimestamp);
                $daysRemaining = ceil(($expirationTimestamp - time()) / (60 * 60 * 24));

                // Récupérer le token de page
                $pageId = env('FACEBOOK_PAGE_ID');
                $pageTokenResponse = $this->callApi->callApi("https://graph.facebook.com/{$pageId}?fields=access_token&access_token={$userAccessToken}");

                if (!isset($pageTokenResponse['access_token'])) {
                    return redirect()->to('/')->with('error', 'Impossible de récupérer le token de la page.');
                }

                $pageAccessToken = $pageTokenResponse['access_token'];

                // Mise à jour du token de page et de la date d'expiration du token utilisateur
                $this->associationModel->update(1, [
                    'tokenFacebook' => $pageAccessToken, // Stocker le token de page
                    'tokenExpirationDate' => $expirationDate // Stocker la date d'expiration du token utilisateur
                ]);

                return redirect()->to('/')->with('success', "Le token a bien été renouvelé ! Il est valide jusqu'au {$expirationDateDay}, soit encore {$daysRemaining} jour(s).");
            }

            // Récupérer les posts depuis le cache ou API
            $posts = $this->facebookCache->getFacebookPosts();

            if (isset($posts['error'])) {
                $logo = $this->associationModel->find(1);
                return view('accueil', ['logo' => $logo, 'posts' => []]); // Passer un tableau vide si erreur
            }
            // Vérifier si les posts sont sous la forme attendue
            if (isset($posts['data']) && is_array($posts['data'])) {
                $posts = array_slice($posts['data'], 0, 10); // Limiter à 10 posts
            } elseif (!isset($posts['data']) && is_array($posts)) {
                $posts = array_slice($posts, 0, 10); // Sécurité supplémentaire
            } else {
                $logo = $this->associationModel->find(1);
                return view('accueil', ['logo' => $logo, 'posts' => []]);
            }

            $logo = $this->associationModel->find(1);
            return view('accueil', ['logo' => $logo, 'posts' => $posts]);
        } catch (\Exception $e) {
            log_message('error', 'Erreur lors du traitement de la demande : ' . $e->getMessage());
            $logo = $this->associationModel->find(1);
            return view('accueil', ['logo' => $logo]);
        }
    }
}
