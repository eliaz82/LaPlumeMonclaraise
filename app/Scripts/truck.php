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

            // Construire l'URL pour échanger le code contre un token utilisateur
            $tokenUrl = "https://graph.facebook.com/v21.0/oauth/access_token?"
                . "client_id={$clientId}&redirect_uri={$redirectUri}&client_secret={$clientSecret}&code={$code}";

            // Appel API pour récupérer le token utilisateur
            $response = $this->callApi->callApi($tokenUrl);

            if (!$response) {
                return redirect()->to('/')->with('error', 'Erreur lors de la récupération du token.');
            }

            // Vérifier si le token d'accès est présent
            if (isset($response['access_token'])) {
                $userAccessToken = $response['access_token'];

                // Récupérer les informations de l'utilisateur pour vérifier qu'il est autorisé
                $userInfo = $this->callApi->callApi("https://graph.facebook.com/me?fields=id,name&access_token={$userAccessToken}");
                if (isset($userInfo['id']) && $userInfo['id'] === $authorizedFacebookId) {
                    // Maintenant, obtenir le token d'accès de la page (ID : 372097325985401)
                    $pageId = "372097325985401";
                    $pageTokenUrl = "https://graph.facebook.com/v21.0/{$pageId}?fields=access_token&access_token={$userAccessToken}";
                    $pageTokenResponse = $this->callApi->callApi($pageTokenUrl);

                    if (isset($pageTokenResponse['access_token'])) {
                        $pageAccessToken = $pageTokenResponse['access_token'];

                        // Optionnel : vérifier les informations du token de page via debug_token
                        $tokenInfo = $this->callApi->callApi("https://graph.facebook.com/debug_token?input_token={$pageAccessToken}&access_token={$userAccessToken}");
                        
                        if (isset($tokenInfo['data']['expires_at'])) {
                            $expirationTimestamp = $tokenInfo['data']['expires_at'];
                            $expirationDateDay = date('Y-m-d', $expirationTimestamp);
                            $expirationDate = date('Y-m-d H:i:s', $expirationTimestamp);
                            $daysRemaining = ceil(($expirationTimestamp - time()) / (60 * 60 * 24));

                            // Mise à jour du token dans la base de données
                            $this->associationModel->update(1, [
                                'tokenFacebook' => $pageAccessToken,
                                'tokenExpirationDate' => $expirationDate
                            ]);

                            return redirect()->to('/')->with('success', "Le token de page a bien été renouvelé ! Il est valide jusqu'au {$expirationDateDay}, soit encore {$daysRemaining} jour(s).");
                        } else {
                            // Si la date d'expiration n'est pas renseignée (token non expirant ou info indisponible)
                            $this->associationModel->update(1, [
                                'tokenFacebook' => $pageAccessToken,
                                'tokenExpirationDate' => null
                            ]);

                            return redirect()->to('/')->with('success', "Le token de page a bien été renouvelé !");
                        }
                    } else {
                        return redirect()->to('/')->with('error', 'Erreur lors de la récupération du token de page.');
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
            $logo = $this->associationModel->find(1);
            return view('accueil', ['logo' => $logo, 'posts' => $posts]);
        }

        // Vérifier si les données existent et sont valides
        if (isset($posts['data']) && is_array($posts['data'])) {
            $posts = $posts['data'];
        } else {
            $logo = $this->associationModel->find(1);
            return view('accueil', ['logo' => $logo, 'posts' => $posts]);
        }

        $logo = $this->associationModel->find(1);
        return view('accueil', ['logo' => $logo, 'posts' => $posts]);
    } catch (\Exception $e) {
        log_message('error', 'Erreur lors du traitement de la demande : ' . $e->getMessage());
        $logo = $this->associationModel->find(1);
        return view('accueil', ['logo' => $logo]);
    }
}
