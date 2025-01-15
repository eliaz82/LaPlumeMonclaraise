<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\Email\Email;
use CodeIgniter\HTTP\DownloadResponse;


class Association extends Controller
{
    public function contact()
    {
        return view('contact');
    }
    public function fichierInscription(): mixed
    {
        if ($this->request->getGet('downloadPdf')) {
            return redirect()->to(base_url('telecharger-fichier-inscription'));
        }
        return view('inscription');
    }
    
    public function telechargerFichierInscription(): DownloadResponse
    {
        $filename = 'test.pdf';
        $filePath = WRITEPATH. '../public/downloads/'. $filename;
        if (file_exists($filePath)) {
            return $this->response->download($filePath, null, true);
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Le fichier $filename n'a pas été trouvé.");
        }
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
