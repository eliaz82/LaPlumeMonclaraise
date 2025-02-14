<?php
// Inclure le framework CodeIgniter
require __DIR__ . '/vendor/autoload.php'; // Ajuste ce chemin selon ta structure

// Démarrer le framework CodeIgniter
$app = require_once __DIR__ . '/app/Config/Paths.php';
$env = \Config\Services::getEnv();

// Appel à ta méthode de contrôleur directement
$controller = new \App\Controllers\Facebook(); // Remplace 'Facebook' par ton contrôleur
$controller->check_and_send_email();  // Appel de la méthode dans le contrôleur
?>
