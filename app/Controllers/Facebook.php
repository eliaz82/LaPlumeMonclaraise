<?php

namespace App\Controllers;

class Facebook extends BaseController
{
    // Étape 1 : Redirection vers Facebook pour authentification
    public function login()
    {
        $clientId = '603470049247384';
        $redirectUri = 'https://d474-92-154-67-66.ngrok-free.app/facebook/callback'; // Page d'accueil
        $scope = 'public_profile,user_posts';

        $url = "https://www.facebook.com/v21.0/dialog/oauth?"
            . "client_id={$clientId}&redirect_uri={$redirectUri}&scope={$scope}&response_type=code";

        return redirect()->to($url);
    }

    // Étape 2 : Transformer le code en token
    public function callback()
    {
        $clientId = '603470049247384';
        $clientSecret = '4b2b340247ca62ea1aebc5adb347a359';
        $redirectUri = 'https://d474-92-154-67-66.ngrok-free.app/facebook/callback'; // URL de redirection

        // Récupérer le code
        $code = $this->request->getGet('code');
        if (!$code) {
            return 'Erreur : Aucun code reçu de Facebook.';
        }

        // Construire l'URL pour échanger le code contre un token
        $tokenUrl = "https://graph.facebook.com/v21.0/oauth/access_token?"
            . "client_id={$clientId}&redirect_uri={$redirectUri}&client_secret={$clientSecret}&code={$code}";

        // Appeler l'API Facebook pour obtenir le token
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $tokenUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);

        // Gérer les erreurs cURL
        if (curl_errno($ch)) {
            return 'Erreur cURL : ' . curl_error($ch);
        }

        curl_close($ch);

        // Décoder la réponse
        $data = json_decode($response, true);

        // Vérifier si un token est présent
        if (isset($data['access_token'])) {
            $accessToken = $data['access_token'];

            // Afficher le token
            return "Token d'accès Facebook : {$accessToken}";
        }

        // Gérer les erreurs renvoyées par Facebook
        if (isset($data['error'])) {
            return 'Erreur de Facebook : ' . $data['error']['message'];
        }

        return 'Erreur : Impossible de récupérer le token.';
    }
    public function getPosts()
    {
        $accessToken = 'EAAIk2lHrAJgBOzyxzUzbXFBZA4D0LpBbqQS46lXjoPeWGbkHG0zvsd8TZBHAawMzU2JgUBgheB2bAo30Lvkbcu6mnqE7wBA2xYWc3xO5F1qnGiGBjBsaZB3ZCsLFB0uoEObSX0e0LocTrjEnyKg6zbKDPMa1rfB7gRXCA3cizZBT4TxRVWrgIXFHZB5jXW1OLD2HGebEqBZBzi7ZAw7MCaAm7yUvmmoqzlLGJVflRIUIXa7qQpefHrIbkXYtkPZBy';
        $endpoint = 'https://graph.facebook.com/v21.0/me/posts?fields=message,created_time,permalink_url&access_token=' . $accessToken;

        // Appel cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $error = 'Erreur cURL : ' . curl_error($ch);
            curl_close($ch);
            return $this->response->setBody($error);
        }

        curl_close($ch);

        $data = json_decode($response, true);

        // Vérification des erreurs dans la réponse
        if (isset($data['error'])) {
            return $this->response->setBody('Erreur API : ' . $data['error']['message']);
        }

        // Afficher les publications en JSON pour debug
        return $this->response->setJSON($data);
    }
}
