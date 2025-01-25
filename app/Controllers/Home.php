<?php

namespace App\Controllers;

class Home extends BaseController
{
    private $associationModel;
    private $facebookModel;

    public function __construct()
    {
        $this->associationModel = model('Association');
        $this->facebookModel = model('Facebook');
    }

    public function index()
    {
        // Vérifier si un paramètre "code" est présent dans l'URL
        $code = $this->request->getGet('code');
        if ($code) {
            // Informations nécessaires pour l'authentification Facebook
            $clientId = '603470049247384';
            $clientSecret = '4b2b340247ca62ea1aebc5adb347a359';
            $redirectUri = 'https://c4d0-2a01-e0a-a49-1340-20d5-21d5-d382-dfe9.ngrok-free.app/'; // URL de redirection
            $authorizedFacebookId = '122101030388738715'; // ID utilisateur autorisé

            // Construire l'URL pour échanger le code contre un token
            $tokenUrl = "https://graph.facebook.com/v21.0/oauth/access_token?"
                . "client_id={$clientId}&redirect_uri={$redirectUri}&client_secret={$clientSecret}&code={$code}";

            // Appel API pour récupérer le token
            $response = $this->callApi($tokenUrl);
            if (!$response) {
                return redirect()->to('/')->with('error', 'Erreur lors de la récupération du token.');
            }

            // Vérifier si le token d'accès est présent
            if (isset($response['access_token'])) {
                $accessToken = $response['access_token'];

                // Récupérer les informations de l'utilisateur
                $userInfo = $this->callApi("https://graph.facebook.com/me?fields=id,name&access_token={$accessToken}");
                if (isset($userInfo['id']) && $userInfo['id'] === $authorizedFacebookId) {
                    // Vérifier les informations du token (validité et expiration)
                    $tokenInfo = $this->callApi("https://graph.facebook.com/debug_token?input_token={$accessToken}&access_token={$accessToken}");

                    if (isset($tokenInfo['data']['expires_at'])) {
                        $expirationTimestamp = $tokenInfo['data']['expires_at'];
                        $expirationDate = date('Y-m-d', $expirationTimestamp);
                        $daysRemaining = ceil(($expirationTimestamp - time()) / (60 * 60 * 24));
                        $this->facebookModel->update(1, [
                            'tokenFacebook' => $accessToken,
                            'tokenExpirationDate' => $expirationDate
                        ]);
                        return redirect()->to('/')->with('success', "Le token a bien été renouvelé ! Il est valide jusqu'au {$expirationDate}, soit encore {$daysRemaining} jour(s).");
                    }
                } else {
                    return redirect()->to('/')->with('error', 'Accès refusé');
                }
            }

            // Si une erreur survient dans la réponse de Facebook
            return redirect()->to('/')->with('error', 'Erreur dans la réponse de Facebook.');
        }

        // Charger la page d'accueil si aucun "code" n'est présent
        $logo = $this->associationModel->find(1);
        return view('accueil', ['logo' => $logo]);
    }

    // Méthode pour appeler l'API
    private function callApi(string $url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            curl_close($ch);
            return false;
        }

        curl_close($ch);
        return json_decode($response, true);
    }

}
