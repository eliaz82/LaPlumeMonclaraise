<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\Email\Email;
use CodeIgniter\HTTP\DownloadResponse;


class Association extends Controller
{
    private $associationModel;

    public function layout(): string
{
    // Récupération de l'association (on vérifie qu'il y a une donnée)
    $association = $this->associationModel->find(1);
    // Vérification si la clé 'logo' existe dans la donnée récupérée
    $logo = isset($association['logo']) ? $association['logo'] : null;
    
    // Passer la variable $logo à la vue
    return view('layout', ['logo' => $logo]);
}


    public function __construct()
    {
        $this->associationModel = model('Association');
    }
    public function contact()
    {
        $association = $this->associationModel->find(1);
        return view('contact', ['association' => $association]);
    }
    public function histoire()
    {
        return view('histoire');
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
        return redirect()->route('fichierInscription')->with('success', 'Le fichier a été téléchargé avec succès.');
    }
    public function contactUpdate()
    {
        $mailContact = $this->request->getPost();
        $this->associationModel->save($mailContact);
        return redirect()->to(route_to('contact'));
    }
    public function logoUpdate()
{
    $association = $this->associationModel->find(1);  // On récupère l'association
    $file = $this->request->getFile('logo');  // On récupère le fichier logo téléchargé

    if ($file && $file->isValid()) {
        // Déplacer le fichier dans le dossier public/uploads
        $filePath = FCPATH . 'uploads/logos/';
        if (!is_dir($filePath)) {
            mkdir($filePath, 0777, true);  // Création du dossier si il n'existe pas
        }

        $filePath = FCPATH . 'uploads/logos/';
        $logoUrl = 'uploads/logos/' . $file->getName();

        // Si un ancien logo existe, on le supprime
        if (!empty($association['logo']) && file_exists(FCPATH . $association['logo'])) {
            unlink(FCPATH . $association['logo']);
        }

        // Mettre à jour le logo dans la base de données
        $this->associationModel->update(1, ['logo' => $logoUrl]);
    }

    // Rediriger vers la page d'accueil ou une autre page
    return redirect()->to(route_to('accueil'));
}

    public function contactSubmit()
    {
        helper('form');
        $rules = [
            'name' => 'required',
            'phone' => 'required',
            'email' => 'required|valid_email',
            'subject' => 'required',
            'message' => 'required'
        ];
        $association = $this->associationModel->find(1);
        $mailContact = $association['mailContact'];

        if ($this->validate($rules)) {
            $name = $this->request->getPost('name');
            $phone = $this->request->getPost('phone');
            $email = $this->request->getPost('email');
            $subject = $this->request->getPost('subject');
            $message = $this->request->getPost('message');

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
            $emailService->setTo($mailContact); //email  pour recevoir
            $emailService->setSubject($subject);
            $emailService->setMessage($htmlMessage); 

            $emailService->setMailType('html');

            if ($emailService->send()) {
                session()->setFlashdata('success', 'Message envoyé avec succès !');
            } else {
                session()->setFlashdata('error', 'Une erreur est survenue lors de l\'envoi du message.');
            }
        } else {
            session()->setFlashdata('error', 'Veuillez vérifier les champs du formulaire.');
        }

        return redirect()->to(route_to('contact'));
    }
}
