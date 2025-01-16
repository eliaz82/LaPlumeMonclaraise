<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\Email\Email;

class Association extends Controller
{
    public function contact()
    {
        return view('contact');
    }
    public function fichierInscription()
    {
        return view('inscription');
    }
    public function histoire()
    {
        return view('histoire');
    }


    public function contactSubmit()
    {
        helper('form');
        $validation = \Config\Services::validation();

        $rules = [
            'name' =>'required',
            'phone' =>'required',
            'email' =>'required|valid_email',
           'subject' =>'required',
           'message' =>'required'
        ];

        $validation->setRules($rules);

        if ($validation->run($this->request->getPost())) {
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
                session()->setFlashdata('success', 'Message envoyé avec succès!');
            } else {
                session()->setFlashdata('error', 'Une erreur est survenue lors de l\'envoi du message.');
            }
        } else {
            session()->setFlashdata('validation', $validation);
        }

        return redirect()->to(route_to('contact'));
    }
}