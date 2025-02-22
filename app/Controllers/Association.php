<?php

namespace App\Controllers;

use CodeIgniter\Controller;


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
        try {
            // Récupération des données envoyées en POST
            $telephone = $this->request->getPost('telephone');
            $associationId = $this->request->getPost('idAssociation');

            // Vérification si les données sont valides
            if (empty($telephone) || empty($associationId)) {
                return redirect()->back()->with('error', 'Numéro de téléphone ou ID de l\'association manquant.');
            }

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
        } catch (\Exception $e) {
            // Gestion des erreurs
            return redirect()->back()->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }
    }


    public function getAssociationData()
    {
        // Récupérer l'association (par exemple avec l'ID 1)
        $association = $this->associationModel->find(1);

        // Vérifier si l'association existe et contient les informations requises
        if ($association) {
            $lat = $association['latitude'];   // Latitude de l'association
            $lon = $association['longitude'];  // Longitude de l'association
            $adresse = $association['adresse'];    // Adresse de l'association
            $tel = $association['tel'];        // Téléphone de l'association
            $email = $association['mailContact']; // Email de contact de l'association
        } else {
            // Valeurs par défaut en cas d'absence de données
            $lat = 43.966742479238754;
            $lon = 1.5866446106619663;
            $adresse = "Adresse non définie";
            $tel = ""; // Ou une valeur par défaut comme "Téléphone non défini"
            $email = ""; // Valeur par défaut pour l'email
        }

        // Préparer les données à renvoyer
        $data = [
            'latitude' => $lat,
            'longitude' => $lon,
            'adresse' => $adresse,
            'tel' => $tel,
            'email' => $email, // Ajout de l'email dans la réponse
        ];

        return $this->response->setJSON($data);
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
                log_message('error', 'Valeur invalide pour l\'état du fichier d\'inscription.');
                return $this->response->setJSON([
                    'error' => 'Valeur de l\'état invalide.',
                    'csrfName' => csrf_token(),
                    'csrfHash' => csrf_hash()
                ]);
            }

            // Récupère l'association et vérifie si elle existe
            $association = $this->associationModel->find(1);
            if (!$association) {
                log_message('error', 'Aucune association trouvée avec l\'ID 1.');
                return $this->response->setJSON([
                    'error' => 'Association non trouvée.',
                    'csrfName' => csrf_token(),
                    'csrfHash' => csrf_hash()
                ]);
            }

            // Met à jour l'enregistrement (ID 1) avec la nouvelle valeur de l'état
            $this->associationModel->update(1, ['fichierInscriptionVisible' => $json->etat]);

            // Retourne un message de succès avec le token CSRF actualisé
            return $this->response->setJSON([
                'status' => 'success',
                'csrfName' => csrf_token(),
                'csrfHash' => csrf_hash()
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Erreur lors de la mise à jour de l\'état du fichier d\'inscription : ' . $e->getMessage());
            return $this->response->setJSON([
                'error' => 'Une erreur est survenue lors de la mise à jour de l\'état du fichier.',
                'csrfName' => csrf_token(),
                'csrfHash' => csrf_hash()
            ]);
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
                'rules' => 'required|regex_match[/^\+?(\d{1,3})?(\d{9,15})$/]',
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
            'dataConsent' => [
                'label' => 'Consentement RGPD',
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
        $name = esc($this->request->getPost('nom'));
        $phone = esc($this->request->getPost('phone'));
        $email = esc($this->request->getPost('email'));
        $subject = esc($this->request->getPost('subject'));
        $message = esc($this->request->getPost('message'));
        $recaptchaResponse = $this->request->getPost('g-recaptcha-response'); // Récupération de la réponse reCAPTCHA

        // Clé secrète de reCAPTCHA v2
        //$secretKey = getenv('RECAPTCHA_SECRET');
        $secretKey = "6LdtAcgqAAAAAA5g8pArLvx5aMsI0gWWjD2eCm3C";


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
            session()->setFlashdata('error', 'La vérification reCAPTCHA a échoué. Essayez à nouveau.');
            return redirect()->to(route_to('contact'))->withInput();
        }

        $htmlMessage = "
        <html>
        <head>
          <meta charset='UTF-8'>
          <style>
            body {
              font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
              background-color: #f4f4f4;
              margin: 0;
              padding: 0;
              color: #333;
            }
            .container {
              max-width: 600px;
              margin: 30px auto;
              background: #ffffff;
              padding: 30px;
              border: 1px solid #e0e0e0;
              border-radius: 8px;
              box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            }
            h3 {
              font-size: 24px;
              text-align: center;
              color: #222;
              border-bottom: 1px solid #e0e0e0;
              padding-bottom: 10px;
              margin-bottom: 25px;
            }
            .field {
              margin-bottom: 20px;
            }
            .label {
              display: block;
              font-weight: bold;
              font-size: 16px;
              margin-bottom: 8px;
              color: #555;
            }
            .info {
              font-size: 16px;
              line-height: 1.5;
              background-color: #f9f9f9;
              padding: 12px;
              border: 1px solid #ddd;
              border-radius: 4px;
            }
            .footer {
              margin-top: 30px;
              padding-top: 15px;
              border-top: 1px solid #e0e0e0;
              font-size: 12px;
              color: #888;
              text-align: center;
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
            
            <div class='field'>
              <span class='label'>Nom :</span>
              <div class='info'>$name</div>
            </div>
            
            <div class='field'>
              <span class='label'>Téléphone :</span>
              <div class='info'>$phone</div>
            </div>
            
            <div class='field'>
              <span class='label'>Email :</span>
              <div class='info'>$email</div>
            </div>
            
            <div class='field'>
              <span class='label'>Objet :</span>
              <div class='info'>$subject</div>
            </div>
            
            <div class='field'>
              <span class='label'>Message :</span>
              <div class='info'>$message</div>
            </div>
            
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

    public function cgu()
    {
        return view('cgu');
    }

    public function mentionsLegale()
    {
        return view('mentionsLegale');
    }

    public function politiqueConfidentialite()
    {
        return view('politiqueConfidentialite');
    }
    public function conformiteRgpd()
    {
        return view('conformiteRgpd');
    }
}
