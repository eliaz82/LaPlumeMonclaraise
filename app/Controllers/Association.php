<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\Email\Email;
use CodeIgniter\HTTP\DownloadResponse;


class Association extends Controller
{
    private $associationModel;

    public function __construct()
    {
        $this->associationModel = model('Association');
    }
    public function contact()
    {
        try {
            // Récupérer l'association (par exemple avec l'ID 1)
            $association = $this->associationModel->find(1);

            // Vérifie si l'association existe et contient les coordonnées
            if ($association) {
                $lat = $association['latitude']; // Latitude de l'association
                $lon = $association['longitude']; // Longitude de l'association
            } else {
                // Valeurs par défaut en cas d'absence de données
                $lat = 43.966742479238754;
                $lon = 1.5866446106619663;
            }

            // Passer les coordonnées à la vue
            return view('contact', [
                'association' => $association,
                'lat' => $lat,
                'lon' => $lon
            ]);
        } catch (\Exception $e) {
            // Log de l'erreur pour débogage
            log_message('error', 'Erreur lors de la récupération des informations de l\'association : ' . $e->getMessage());

            // Passer des données par défaut en cas d'erreur
            return view('contact', [
                'association' => null,
                'lat' => 43.966742479238754,
                'lon' => 1.5866446106619663
            ]);
        }
    }

    public function tel()
    {
        // Récupération des données envoyées en POST
        $telephone    = $this->request->getPost('telephone');
        $associationId = $this->request->getPost('idAssociation');

        // Vous pouvez ajouter des validations sur le numéro de téléphone ici

        // Préparation des données à mettre à jour
        $data = [
            'tel' => $telephone,
        ];

        // Mise à jour dans la base de données
        if ($this->associationModel->update($associationId, $data)) {
            // Succès : rediriger en renvoyant un message
            return redirect()->back()->with('message', 'Téléphone mis à jour avec succès.');
        } else {
            // Erreur : rediriger avec un message d'erreur
            return redirect()->back()->with('error', 'Erreur lors de la mise à jour du téléphone.');
        }
    }

    public function getAssociationData()
    {
        try {
            // Récupérer l'association (par exemple avec l'ID 1)
            $association = $this->associationModel->find(1);

        // Vérifier si l'association existe et contient les informations requises
        if ($association) {
            $lat     = $association['latitude'];   // Latitude de l'association
            $lon     = $association['longitude'];  // Longitude de l'association
            $adresse = $association['adresse'];      // Adresse de l'association
            $tel     = $association['tel'];          // Téléphone de l'association
        } else {
            // Valeurs par défaut en cas d'absence de données
            $lat     = 43.966742479238754;
            $lon     = 1.5866446106619663;
            $adresse = "Adresse non définie";
            $tel     = ""; // Ou une valeur par défaut comme "Téléphone non défini"
        }
            // Vérifier si l'association existe
            if (!$association) {
                // Renvoi des valeurs par défaut en cas d'absence de données
                return $this->response->setJSON([
                    'latitude' => 43.966742479238754,
                    'longitude' => 1.5866446106619663,
                    'adresse' => "Adresse non définie",
                ]);
            }

            // Valider les données récupérées
            $lat = $association['latitude'];
            $lon = $association['longitude'];
            $adresse = $association['adresse'] ?? "Adresse non définie"; // Valeur par défaut si adresse est vide

            // Vérifier si la latitude et la longitude sont valides (par exemple, entre -90 et 90 pour latitude et -180 et 180 pour longitude)
            if (!is_numeric($lat) || !is_numeric($lon) || $lat < -90 || $lat > 90 || $lon < -180 || $lon > 180) {
                return $this->response->setJSON([
                    'error' => 'Données géographiques invalides.',
                ]);
            }

        // Préparer les données à renvoyer
        $data = [
            'latitude'  => $lat,
            'longitude' => $lon,
            'adresse'   => $adresse,
            'tel'       => $tel,
        ];
            // Préparer les données à renvoyer
            $data = [
                'latitude' => $lat,
                'longitude' => $lon,
                'adresse' => $adresse,
            ];

            return $this->response->setJSON($data);
        } catch (\Exception $e) {
            // Log de l'erreur pour le débogage
            log_message('error', 'Erreur lors de la récupération des données de l\'association : ' . $e->getMessage());

            // Retourner une réponse d'erreur générique
            return $this->response->setJSON([
                'error' => 'Une erreur est survenue lors de la récupération des données.',
            ]);
        }
    }




    public function fichierInscription()
    {
        try {
            // Récupérer l'association (par exemple avec l'ID 1)
            $association = $this->associationModel->find(1);

            // Vérifier si l'association existe et contient le fichier d'inscription
            if (!$association || empty($association['fichierInscription'])) {
                // Log de l'erreur pour le débogage
                log_message('error', 'Le fichier d\'inscription est introuvable ou vide.');

                // Retourner un message d'erreur si aucune donnée de fichier d'inscription n'est trouvée
                return view('inscription', [
                    'inscriptionsClosed' => true
                ]);
            }
            // Vérifier si la visibilité du fichier d'inscription est activée
            $inscriptionsClosed = ($association['fichierInscriptionVisible'] == 0);

            // Préparer les données à renvoyer à la vue
            $data = [
                'fichierInscription' => $association['fichierInscription'],
                'inscriptionsClosed' => $inscriptionsClosed
            ];

            return view('inscription', $data);
        } catch (\Exception $e) {
            // Log de l'erreur pour le débogage
            log_message('error', 'Erreur lors de la récupération du fichier d\'inscription : ' . $e->getMessage());

            // Retourner une réponse d'erreur générique à la vue
            return view('inscription', [
                'inscriptionsClosed' => true
            ]);
        }
    }



    public function downloadFichier($fileName)
    {
        // Définir le chemin du fichier
        $filePath = WRITEPATH . 'uploads/inscription/' . $fileName;

        try {
            // Vérifier si le fichier existe
            if (file_exists($filePath)) {
                // Télécharger le fichier si il existe
                return $this->response->download($filePath, null)->setFileName($fileName);
            } else {
                // Log de l'erreur
                log_message('error', 'Le fichier d\'inscription demandé n\'existe pas : ' . $filePath);

                // Retourner un message d'erreur si le fichier n'existe pas
                return redirect()->route('fichierInscription')->with('error', 'Le fichier n\'existe pas.');
            }
        } catch (\Exception $e) {
            // Log de l'exception pour le débogage
            log_message('error', 'Erreur lors de la tentative de téléchargement du fichier : ' . $e->getMessage());

            // Retourner un message d'erreur générique
            return redirect()->route('fichierInscription')->with('error', 'Une erreur est survenue lors du téléchargement du fichier.');
        }
    }

    public function fichierInscriptionSubmit()
    {
        // Récupérer le fichier téléchargé
        $file = $this->request->getFile('fichier_inscription');

        try {
            // Vérifier si le fichier a bien été téléchargé
            if (!$file || !$file->isValid()) {
                // Log de l'erreur de téléchargement du fichier
                log_message('error', 'Aucun fichier téléchargé ou fichier invalide.');

                // Retourner un message d'erreur à l'utilisateur
                return redirect()->route('fichierInscription')->with('error', 'Le fichier est invalide ou absent.');
            }

            // Récupérer l'association et vérifier si le fichier existant doit être supprimé
            $association = $this->associationModel->find(1);

            if (!empty($association['fichierInscription']) && file_exists(FCPATH . $association['fichierInscription'])) {
                unlink(FCPATH . $association['fichierInscription']);
            }

            // Définir le chemin de destination pour le fichier
            $filePath = FCPATH . 'uploads/inscription/';

            // Déplacer le fichier téléchargé vers le répertoire cible
            if (!$file->move($filePath)) {
                // Log de l'échec du déplacement du fichier
                log_message('error', 'Erreur lors du déplacement du fichier vers ' . $filePath);

                // Retourner un message d'erreur
                return redirect()->route('fichierInscription')->with('error', 'Une erreur est survenue lors de l\'upload du fichier.');
            }

            // Construire l'URL du fichier
            $fileUrl = 'uploads/inscription/' . $file->getName();

            // Mettre à jour l'enregistrement de l'association avec l'URL du fichier
            $this->associationModel->update(1, [
                'fichierInscription' => $fileUrl
            ]);

            // Retourner un message de succès
            return redirect()->route('fichierInscription')->with('success', 'Le fichier a été téléversé avec succès.');
        } catch (\Exception $e) {
            // Log de l'exception pour aider au débogage
            log_message('error', 'Erreur lors de la soumission du fichier d\'inscription : ' . $e->getMessage());

            // Retourner un message d'erreur générique
            return redirect()->route('fichierInscription')->with('error', 'Une erreur est survenue, veuillez réessayer.');
        }
    }

    public function getFichierInscriptionEtat()
    {
        try {
            // Récupère l'enregistrement avec l'ID 1
            $association = $this->associationModel->find(1);

            if (!$association) {
                // Log de l'erreur si l'association n'est pas trouvée
                log_message('error', 'Aucune association trouvée avec l\'ID 1.');

                // Retourner un message d'erreur si aucune donnée n'est trouvée
                return $this->response->setJSON(['error' => 'Association non trouvée.']);
            }

            // Vérifie si la colonne "fichierInscriptionVisible" existe et récupère sa valeur
            $etat = isset($association['fichierInscriptionVisible']) ? $association['fichierInscriptionVisible'] : 1;

            // Retourne l'état du fichier d'inscription sous forme de JSON
            return $this->response->setJSON(['etat' => $etat]);
        } catch (\Exception $e) {
            // Log de l'exception pour aider au débogage
            log_message('error', 'Erreur lors de la récupération de l\'état du fichier d\'inscription : ' . $e->getMessage());

            // Retourner un message d'erreur générique en cas d'exception
            return $this->response->setJSON(['error' => 'Une erreur est survenue lors de la récupération de l\'état du fichier.']);
        }
    }


    public function updateFichierInscriptionEtat()
    {
        try {
            // Récupère les données JSON envoyées par la requête
            $json = $this->request->getJSON();

            // Vérifie si le paramètre 'etat' existe et est un entier valide (0 ou 1)
            if (!isset($json->etat) || !in_array($json->etat, [0, 1], true)) {
                // Log de l'erreur si l'état est invalide
                log_message('error', 'Valeur invalide pour l\'état du fichier d\'inscription.');

                // Retourne un message d'erreur si l'état est invalide
                return $this->response->setJSON(['error' => 'Valeur de l\'état invalide.']);
            }

            // Récupère l'association et vérifie si elle existe
            $association = $this->associationModel->find(1);
            if (!$association) {
                // Log de l'erreur si l'association n'est pas trouvée
                log_message('error', 'Aucune association trouvée avec l\'ID 1.');

                // Retourner un message d'erreur si aucune association n'est trouvée
                return $this->response->setJSON(['error' => 'Association non trouvée.']);
            }

            // Met à jour l'enregistrement (ID 1) avec la nouvelle valeur de l'état
            $this->associationModel->update(1, ['fichierInscriptionVisible' => $json->etat]);

            // Retourne un message de succès
            return $this->response->setJSON(['status' => 'success']);
        } catch (\Exception $e) {
            // Log de l'exception pour aider au débogage
            log_message('error', 'Erreur lors de la mise à jour de l\'état du fichier d\'inscription : ' . $e->getMessage());

            // Retourner un message d'erreur générique en cas d'exception
            return $this->response->setJSON(['error' => 'Une erreur est survenue lors de la mise à jour de l\'état du fichier.']);
        }
    }

    public function contactUpdate()
    {
        try {
            // Récupère le mail de contact envoyé par la requête POST
            $mailContact = $this->request->getPost('mailContact');

            // Validation : Vérifie si l'email est valide
            if (!filter_var($mailContact, FILTER_VALIDATE_EMAIL)) {
                // Log de l'erreur si l'email est invalide
                log_message('error', 'Adresse e-mail de contact invalide : ' . $mailContact);

                // Retourne un message d'erreur si l'email n'est pas valide
                return redirect()->route('contact')->with('error', 'L\'adresse e-mail de contact est invalide.');
            }

            // Récupère l'association
            $association = $this->associationModel->find(1);
            if (!$association) {
                // Log de l'erreur si l'association n'est pas trouvée
                log_message('error', 'Aucune association trouvée avec l\'ID 1.');

                // Retourne un message d'erreur si l'association n'est pas trouvée
                return redirect()->route('contact')->with('error', 'Aucune association trouvée.');
            }

            // Met à jour l'enregistrement (ID 1) avec la nouvelle adresse e-mail de contact
            $this->associationModel->update(1, ['mailContact' => $mailContact]);

            // Retourne un message de succès
            return redirect()->route('contact')->with('success', 'Le mail de contact a été modifié avec succès.');
        } catch (\Exception $e) {
            // Log de l'exception pour aider au débogage
            log_message('error', 'Erreur lors de la mise à jour du mail de contact : ' . $e->getMessage());

            // Retourner un message d'erreur générique en cas d'exception
            return redirect()->route('contact')->with('error', 'Une erreur est survenue lors de la mise à jour du mail de contact.');
        }
    }

    public function localisation()
    {
        try {
            // Récupère les données envoyées par la requête POST
            $data = $this->request->getPost();

            // Validation des données : vérifie si les coordonnées sont présentes et valides
            if (empty($data['latitude']) || !is_numeric($data['latitude']) || !is_numeric($data['longitude'])) {
                // Log de l'erreur si les coordonnées sont invalides
                log_message('error', 'Coordonnées invalides ou absentes : ' . json_encode($data));

                // Retourne un message d'erreur si les coordonnées sont invalides
                return redirect()->route('contact')->with('error', 'Les coordonnées de localisation sont invalides.');
            }

            // Mise à jour de la localisation de l'association avec les nouvelles données
            $this->associationModel->update(1, $data);

            // Retourne un message de succès
            return redirect()->route('contact')->with('success', 'La localisation a été modifiée avec succès.');
        } catch (\Exception $e) {
            // Log de l'exception pour aider au débogage
            log_message('error', 'Erreur lors de la mise à jour de la localisation : ' . $e->getMessage());

            // Retourner un message d'erreur générique en cas d'exception
            return redirect()->route('contact')->with('error', 'Une erreur est survenue lors de la mise à jour de la localisation.');
        }
    }



    public function getEmailReception()
    {
        try {
            // Récupérer l'association depuis le modèle (ici, l'association avec l'ID 1)
            $association = $this->associationModel->find(1);

            // Vérifier si l'association existe
            if (!$association) {
                // Log de l'erreur si l'association n'est pas trouvée
                log_message('error', 'Association non trouvée avec l\'ID 1.');

                // Retourner un message d'erreur si l'association n'est pas trouvée
                return $this->response->setJSON([
                    'error' => 'Association non trouvée.'
                ]);
            }

            // Retourner les informations de l'email et de l'id de l'association
            return $this->response->setJSON([
                'emailContact' => $association['mailContact'],
                'idAssociation' => $association['idAssociation']  // Assurez-vous que la clé correspond à l'id de l'association
            ]);
        } catch (\Exception $e) {
            // Log de l'exception pour aider au débogage
            log_message('error', 'Erreur lors de la récupération de l\'email de réception : ' . $e->getMessage());

            // Retourner un message d'erreur générique
            return $this->response->setJSON([
                'error' => 'Une erreur est survenue lors de la récupération des données.'
            ]);
        }
    }



    public function logoUpdate()
    {
        try {
            // Récupérer l'association
            $association = $this->associationModel->find(1);

            // Récupérer le fichier téléchargé
            $file = $this->request->getFile('logo');

            // Vérifier si un fichier a bien été téléchargé et s'il est valide
            if ($file && $file->isValid()) {

                // Liste des types de fichiers d'image autorisés
                $allowedTypes = [
                    'image/jpg',
                    'image/jpeg',
                    'image/png',
                    'image/gif',
                    'image/bmp',
                    'image/tiff',
                    'image/x-icon',
                    'image/webp',
                    'image/svg+xml',
                    'image/heif',
                    'image/heic'
                ];

                // Vérifier si le type MIME du fichier est autorisé
                if (!in_array($file->getMimeType(), $allowedTypes)) {
                    // Log de l'erreur de type de fichier
                    log_message('error', 'Le fichier téléchargé n\'est pas du bon type. Type attendu : JPG, JPEG, PNG, GIF, BMP, TIFF, SVG, HEIF, HEIC.');

                    return redirect()->route('accueil')->with('error', 'Seuls les fichiers image valides (JPG, JPEG, PNG, GIF, BMP, TIFF, SVG, HEIF, HEIC) sont autorisés.');
                }

                // Définir le chemin de destination pour le fichier
                $filePath = FCPATH . 'uploads/logos/';

                // Déplacer le fichier téléchargé
                if (!$file->move($filePath)) {
                    // Log de l'échec du déplacement du fichier
                    log_message('error', 'Erreur lors du déplacement du fichier vers ' . $filePath);

                    return redirect()->route('accueil')->with('error', 'Une erreur est survenue lors de l\'upload du logo.');
                }

                // Construire l'URL du fichier
                $logoUrl = 'uploads/logos/' . $file->getName();

                // Supprimer l'ancien logo s'il existe
                if (!empty($association['logo']) && file_exists(FCPATH . $association['logo'])) {
                    unlink(FCPATH . $association['logo']);
                }

                // Mettre à jour l'enregistrement de l'association avec le nouveau logo
                $this->associationModel->update(1, ['logo' => $logoUrl]);

                // Retourner un message de succès
                return redirect()->route('accueil')->with('success', 'Le logo a été modifié avec succès.');
            } else {
                // Log de l'erreur si le fichier n'est pas valide
                log_message('error', 'Aucun fichier ou fichier invalide téléchargé.');

                return redirect()->route('accueil')->with('error', 'Aucun fichier n\'a été téléchargé ou le fichier est invalide.');
            }
        } catch (\Exception $e) {
            // Log de l'exception pour aider au débogage
            log_message('error', 'Erreur lors de la mise à jour du logo : ' . $e->getMessage());

            // Retourner un message d'erreur générique
            return redirect()->route('accueil')->with('error', 'Une erreur est survenue, veuillez réessayer.');
        }
    }


    public function contactSubmit()
    {
        // Charger l'helper de formulaire
        helper('form');

        // Définir les règles de validation
        $rules = [
            'nom' => [
                'label' => 'Nom',
                'rules' => 'required',
            ],
            'phone' => [
                'label' => 'Téléphone',
                'rules' => 'required|regex_match[/^\+?(\d{1,3})?(\d{10})$/]',
            ],
            'email' => [
                'label' => 'Email',
                'rules' => 'required|valid_email',
            ],
            'subject' => [
                'label' => 'Objet',
                'rules' => 'required',
            ],
            'message' => [
                'label' => 'Message',
                'rules' => 'required',
            ],
            'g-recaptcha-response' => [
                'label' => 'reCAPTCHA',
                'rules' => 'required',
            ],
        ];

        // Validation des règles
        if (!$this->validate($rules)) {
            session()->setFlashdata('validation', $this->validator->getErrors());
            return redirect()->to(route_to('contact'))->withInput();
        }

        // Récupérer l'association pour l'email de contact
        $association = $this->associationModel->find(1);
        $mailContact = $association['mailContact'];

        // Récupérer les données du formulaire
        $name = $this->request->getPost('nom');
        $phone = $this->request->getPost('phone');
        $email = $this->request->getPost('email');
        $subject = $this->request->getPost('subject');
        $message = $this->request->getPost('message');
        $recaptchaResponse = $this->request->getPost('g-recaptcha-response'); // Récupération de la réponse reCAPTCHA

        // Clé secrète de reCAPTCHA v2
        $secretKey = '6LdtAcgqAAAAAA5g8pArLvx5aMsI0gWWjD2eCm3C'; // Remplacer par ta propre clé secrète

        // Vérification du reCAPTCHA
        $recaptchaUrl = 'https://www.google.com/recaptcha/api/siteverify';
        $recaptchaData = [
            'secret' => $secretKey,
            'response' => $recaptchaResponse
        ];

        // Appel API pour vérifier le reCAPTCHA
        $response = \Config\Services::curlrequest()->post($recaptchaUrl, [
            'form_params' => $recaptchaData
        ]);

        // Décoder la réponse du reCAPTCHA
        $result = json_decode($response->getBody());

        if (!$result->success) {
            // Si le reCAPTCHA échoue
            session()->setFlashdata('error', 'La vérification reCAPTCHA a échoué. Veuillez réessayer.');
            return redirect()->to(route_to('contact'))->withInput();
        }

        // Préparer le message HTML à envoyer
        $htmlMessage = "
            <html>
            <head>
                <style>
                   body {
                            font-family: 'Arial', sans-serif;
                            margin: 0;
                            padding: 0;
                            background-color: #f4f4f4;
                        }
                        .container {
                            width: 100%;
                            max-width: 600px;
                            margin: 0 auto;
                            background-color: #ffffff;
                            padding: 20px;
                            border-radius: 8px;
                            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                        }
                        h3 {
                            color: #333333;
                            text-align: center;
                            font-size: 24px;
                            margin-bottom: 20px;
                        }
                        p {
                            font-size: 16px;
                            color: #555555;
                            margin: 10px 0;
                        }
                        .label {
                            font-weight: bold;
                            color: #333333;
                        }
                        .info {
                            background-color: #f9f9f9;
                            padding: 10px;
                            border-radius: 5px;
                            border: 1px solid #ddd;
                        }
                        .footer {
                            margin-top: 20px;
                            text-align: center;
                            font-size: 12px;
                            color: #888888;
                        }
                        .footer a {
                            color: #007BFF;
                            text-decoration: none;
                        }
                </style>
            </head>
            <body>
                <div class='container'>
                    <h3>Nouvelle demande de contact</h3>
                    <p class='label'>Nom:</p>
                    <p class='info'>$name</p>
    
                    <p class='label'>Téléphone:</p>
                    <p class='info'>$phone</p>
    
                    <p class='label'>Email:</p>
                    <p class='info'>$email</p>
    
                    <p class='label'>Objet:</p>
                    <p class='info'>$subject</p>
    
                    <p class='label'>Message:</p>
                    <p class='info'>$message</p>
    
                    <div class='footer'>
                        <p>Vous avez reçu ce message via le formulaire de contact de votre site web.</p>
                        <p><a href='mailto:$email'>Répondre à cet email</a></p>
                    </div>
                </div>
            </body>
            </html>
        ";

        // Configuration de l'email
        $emailService = \Config\Services::email();
        $emailService->setFrom($email, $name);
        $emailService->setTo($mailContact); // Email de réception
        $emailService->setSubject($subject);
        $emailService->setMessage($htmlMessage);
        $emailService->setMailType('html');

        // Envoi de l'email
        if ($emailService->send()) {
            session()->setFlashdata('success', 'Message envoyé avec succès !');
        } else {
            session()->setFlashdata('error', 'Une erreur est survenue lors de l\'envoi du message.');
            return redirect()->to(route_to('contact'))->withInput();
        }

        // Rediriger vers la page de contact
        return redirect()->to(route_to('contact'));
    }
}
