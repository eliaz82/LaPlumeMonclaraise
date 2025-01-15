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
        return view('contact');
    }
    public function histoire()
    {
        return view('histoire');
    }
    public function downloadFichier($fileName)
    {
        $filePath = WRITEPATH . 'uploads/downloads/' . $fileName;

        if (file_exists($filePath)) {
            return $this->response->download($filePath, null)->setFileName($fileName);
        } else {
            return redirect()->route('fichierInscription')->with('error', 'Le fichier n\'existe pas.');
        }
    }

    public function fichierInscription()
    {
        $association = $this->associationModel->find(1);
        $fichierInscription = $association['fichierInscription'];

        return view('inscription', ['fichierInscription' => $fichierInscription]);
    }

    public function fichierInscriptionSubmit()
    {
        $file = $this->request->getFile('fichier_inscription');
        $filePath = FCPATH . 'uploads/downloads/';
        $file->move($filePath);
        $fileUrl = '/uploads/downloads/' . $file->getName();

        $this->associationModel->update(1, [
            'fichierInscription' => $fileUrl
        ]);

        return redirect()->route('fichierInscription')->with('success', 'Le fichier a été téléchargé avec succès.');
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

        if ($this->validate($rules)) {
            $name = $this->request->getPost('name');
            $phone = $this->request->getPost('phone');
            $email = $this->request->getPost('email');
            $subject = $this->request->getPost('subject');
            $message = $this->request->getPost('message');

            $emailService = \Config\Services::email();
            $emailService->setFrom($email, $name);
            $emailService->setTo('emmanuel.basck@gmail.com'); // Remplacez par votre email
            $emailService->setSubject($subject);
            $emailService->setMessage("Nom: $name\nTéléphone: $phone\nEmail: $email\nObjet: $subject\nMessage: $message");

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
