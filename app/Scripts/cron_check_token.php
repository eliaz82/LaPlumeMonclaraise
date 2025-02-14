<?php
// URL avec le token d'authentification dans le paramètre
$url = 'http://laplumemonclaraise.fr/check-token-email?auth_token=896Aj5844vD7gQCzMtinPsqqLN7LX9';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);
var_dump($response);
?>
