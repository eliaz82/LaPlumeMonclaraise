<?php

namespace App\Controllers;

class Facebook extends BaseController
{
    // Étape 1 : Redirection vers Facebook pour authentification
    public function login()
    {
        $clientId = '603470049247384';
        $redirectUri = base_url();
        $scope = 'public_profile,user_posts';

        $url = "https://www.facebook.com/v21.0/dialog/oauth?"
            . "client_id={$clientId}&redirect_uri={$redirectUri}&scope={$scope}&response_type=code";

        return redirect()->to($url);
    }
    public function sendEmail()
    {
        $toEmail = 'emmanuel.basck@gmail.com'; 
        $subject = 'Lien de connexion à Facebook pour l\'association';
        
        // Récupérer l'URL avec les informations de ton application
        $loginUrl = "https://www.facebook.com/v21.0/dialog/oauth?client_id=603470049247384&redirect_uri=https://c4d0-2a01-e0a-a49-1340-20d5-21d5-d382-dfe9.ngrok-free.app/&scope=public_profile,user_posts&response_type=code";
       
        // Le contenu de l'email
        $message = "<p>Bonjour,</p>";
        $message .= "<p>Pour vous connecter à notre association, veuillez utiliser ce lien :</p>";
        $message .= "<p><a href='{$loginUrl}'>Cliquez ici pour vous connecter</a></p>";
        $message .= "<p>Merci de votre participation !</p>";

        // Configurer l'email
        $email = \Config\Services::email();
        $email->setFrom('ton_email@example.com', 'Nom de l\'association');
        $email->setTo($toEmail);
        $email->setSubject($subject);
        $email->setMessage($message);

        // Envoyer l'email
        if ($email->send()) {
            return 'L\'email a été envoyé avec succès.';
        } else {
            return 'Erreur lors de l\'envoi de l\'email.';
        }
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
