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
    }
    public function getAssociationData()
    {
        // Récupérer l'association (par exemple avec l'ID 1)
        $association = $this->associationModel->find(1);
    
        // Vérifier si l'association existe et contient les coordonnées
        if ($association) {
            $lat        = $association['latitude'];  // Latitude de l'association
            $lon        = $association['longitude']; // Longitude de l'association
            $adresse    = $association['adresse'];   // Adresse de l'association
        } else {
            // Valeurs par défaut en cas d'absence de données
            $lat         = 43.966742479238754;
            $lon         = 1.5866446106619663;
            $adresse     = "Adresse non définie";
        }
    
        // Préparer les données à renvoyer
        $data = [
            'latitude'    => $lat,
            'longitude'   => $lon,
            'adresse'     => $adresse,
        ];
    
        return $this->response->setJSON($data);
    }
    


    public function fichierInscription()
    {
        $association = $this->associationModel->find(1);
        $fichierInscription = $association['fichierInscription'];

        return view('inscription', ['fichierInscription' => $fichierInscription]);
    }


    public function downloadFichier($fileName)
    {
        $filePath = WRITEPATH . 'uploads/inscription/' . $fileName;

        if (file_exists($filePath)) {
            return $this->response->download($filePath, null)->setFileName($fileName);
        } else {
            return redirect()->route('fichierInscription')->with('error', 'Le fichier n\'existe pas.');
        }
    }
    public function fichierInscriptionSubmit()
    {
        $file = $this->request->getFile('fichier_inscription');
        $association = $this->associationModel->find(1);
        if (!empty($association['fichierInscription']) && file_exists(FCPATH . $association['fichierInscription'])) {
            unlink(FCPATH . $association['fichierInscription']);
        }
        $filePath = FCPATH . 'uploads/inscription/';
        $file->move($filePath);
        $fileUrl = 'uploads/inscription/' . $file->getName();
        $this->associationModel->update(1, [
            'fichierInscription' => $fileUrl
        ]);
        return redirect()->route('fichierInscription')->with('success', 'Le fichier a été téléversez avec succès.');
    }
    public function contactUpdate()
    {
        $mailContact = $this->request->getPost('mailContact');
      
        $this->associationModel->update(1, ['mailContact' => $mailContact]);
        return redirect()->route('contact')->with('success', 'Le mail de contact a été modifié avec succès.');
    }
    public function localisation()
    {
        $data = $this->request->getPost();
        $this->associationModel->update(1, $data);
        return redirect()->route('contact')->with('success', 'La localisation a été modifié avec succès.');
    }


    public function getEmailReception()
    {
        // Récupérer l'association depuis le modèle
        $association = $this->associationModel->find(1);
        return $this->response->setJSON(['emailContact' => $association['mailContact']]);
    }

    public function logoUpdate()
    {
        $association = $this->associationModel->find(1);
        $file = $this->request->getFile('logo');

        if ($file && $file->isValid()) {
            $filePath = FCPATH . 'uploads/logos/';
            $file->move($filePath);
            $logoUrl = 'uploads/logos/' . $file->getName();
            if (!empty($association['logo']) && file_exists(FCPATH . $association['logo'])) {
                unlink(FCPATH . $association['logo']);
            }
            $this->associationModel->update(1, ['logo' => $logoUrl]);
        }
        return redirect()->route('accueil')->with('success', 'Le logo a été modifié avec succès.');
    }

    public function contactSubmit()
    {
        helper('form');
        $rules = [
            'nom' => [
                'label' => 'Nom', // Nom du champ en français
                'rules' => 'required',
            ],
            'phone' => [
                'label' => 'Téléphone',
                'rules' => 'required',
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

        if (!$this->validate($rules)) {
            session()->setFlashdata('validation', $this->validator->getErrors());
            return redirect()->to(route_to('contact'))->withInput();
        }
        $association = $this->associationModel->find(1);
        $mailContact = $association['mailContact'];


        // Récupération des champs du formulaire
        $name = $this->request->getPost('nom');
        $phone = $this->request->getPost('phone');
        $email = $this->request->getPost('email');
        $subject = $this->request->getPost('subject');
        $message = $this->request->getPost('message');
        $recaptchaResponse = $this->request->getPost('g-recaptcha-response'); // Récupération de la réponse reCAPTCHA

        // Clé secrète de reCAPTCHA v2
        $secretKey = '6LdtAcgqAAAAAA5g8pArLvx5aMsI0gWWjD2eCm3C'; // Remplace par ta clé secrète

        // Vérification du reCAPTCHA
        $recaptchaUrl = 'https://www.google.com/recaptcha/api/siteverify';
        $recaptchaData = [
            'secret' => $secretKey,
            'response' => $recaptchaResponse
        ];

        $response = \Config\Services::curlrequest()->post($recaptchaUrl, [
            'form_params' => $recaptchaData
        ]);

        $result = json_decode($response->getBody());

        if (!$result->success) {
            // Si le reCAPTCHA échoue
            session()->setFlashdata('error', 'La vérification reCAPTCHA a échoué. Veuillez réessayer.');
            return redirect()->to(route_to('contact'))->withInput();
        }

        // Si tout est valide, envoi du message par email
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
        $emailService->setTo($mailContact); // email pour recevoir
        $emailService->setSubject($subject);
        $emailService->setMessage($htmlMessage);
        $emailService->setMailType('html');

        if ($emailService->send()) {
            session()->setFlashdata('success', 'Message envoyé avec succès !');
        } else {
            session()->setFlashdata('error', 'Une erreur est survenue lors de l\'envoi du message.');
            return redirect()->to(route_to('contact'))->withInput();
        }
        return redirect()->to(route_to('contact'));
    }
}
