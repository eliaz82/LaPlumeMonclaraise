<?php
// Utilisation de CURL pour faire une requête HTTP à la route définie
$url = 'https://laplumemonclaraise.fr/check-token-email'; // Remplace par l'URL correcte de ta route
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 30); // 30 secondes pour attendre la réponse
$response = curl_exec($ch);
curl_close($ch);

?>
