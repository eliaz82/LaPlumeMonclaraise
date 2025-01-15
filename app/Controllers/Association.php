<?php

namespace App\Controllers;

class Association extends BaseController
{
    public function histoire(): string
    {
        return view('histoire');
    }
    public function contact(): string
    {
        return view('contact');
    }

    public function contactSubmit() 
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('name', 'Nom', 'required');
        $this->form_validation->set_rules('phone', 'Téléphone', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('subject', 'Objet', 'required');
        $this->form_validation->set_rules('message', 'Message', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('contact_form');
        } else {
            $name = $this->input->post('name');
            $phone = $this->input->post('phone');
            $email = $this->input->post('email');
            $subject = $this->input->post('subject');
            $message = $this->input->post('message');

            $this->load->library('email');

            $this->email->from($email, $name);
            $this->email->to('votre-email@example.com'); // Remplacez par votre email
            $this->email->subject($subject);
            $this->email->message("Nom: $name\nTéléphone: $phone\nEmail: $email\nObjet: $subject\nMessage: $message");

            if ($this->email->send()) {
                $this->session->set_flashdata('success', 'Message envoyé avec succès !');
            } else {
                $this->session->set_flashdata('error', 'Une erreur est survenue lors de l\'envoi du message.');
            }

            redirect('contact');
        }
    }

    public function fichierInscription(): string
    {
        return view('inscription');
    }
}
