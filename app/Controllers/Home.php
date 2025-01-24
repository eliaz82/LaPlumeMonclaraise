<?php

namespace App\Controllers;

class Home extends BaseController
{
    private $associationModel;

    public function __construct()
    {
        $this->associationModel = model('Association');
    }

    public function index(): string
    {
        $logo = $this->associationModel->find(1);
        
        // Vérifier si un paramètre "code" est présent dans l'URL
        $code = $this->request->getGet('code');
        if ($code) {
            $clientId = '603470049247384';
            $clientSecret = '4b2b340247ca62ea1aebc5adb347a359';
            $redirectUri = 'https://ca2e-2a01-e0a-a49-1340-ce8-4f50-3a05-570.ngrok-free.app/'; // URL de redirection
    
            // Construire l'URL pour échanger le code contre un token
            $tokenUrl = "https://graph.facebook.com/v21.0/oauth/access_token?"
                . "client_id={$clientId}&redirect_uri={$redirectUri}&client_secret={$clientSecret}&code={$code}";
    
            // Appeler l'API Facebook pour obtenir le token
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $tokenUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            if (curl_errno($ch)) {
                curl_close($ch);
                return 'Erreur cURL : ' . curl_error($ch);
            }
            
            curl_close($ch);
    
            // Décoder la réponse du token
            $data = json_decode($response, true);
            
            // Vérifier si un token est présent
            if (isset($data['access_token'])) {
                $accessToken = $data['access_token'];
    
                // Récupérer les informations de l'utilisateur
                $userInfo = json_decode(file_get_contents("https://graph.facebook.com/me?fields=id,name&access_token={$accessToken}"), true);
    
                // Vérifier l'ID utilisateur
                $authorizedFacebookId = '122101030388738715'; // ID autorisé
        
                if (isset($userInfo['id']) && $userInfo['id'] === $authorizedFacebookId) {
                    return "Bonjour {$userInfo['name']} ! Vous êtes bien connecté.";
                } else {
                    return 'Accès refusé : cet utilisateur n\'est pas autorisé à se connecter.';
                }
            }
    
            // Gérer les erreurs renvoyées par Facebook
            if (isset($data['error'])) {
                return 'Erreur de Facebook : ' . $data['error']['message'];
            }
        }
        
        // Charger la page d'accueil si aucun "code" n'est présent ou après traitement
        return view('accueil', ['logo' => $logo]);
    }
    

}
