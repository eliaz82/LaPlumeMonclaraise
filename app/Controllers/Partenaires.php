<?php

namespace App\Controllers;

class Partenaires extends BaseController
{
    private $partenairesModel;

    public function __construct()
    {
        $this->partenairesModel = model('Partenaires');
    }
    public function partenaires(): string
    {
        $partenaire = $this->partenairesModel->findAll();
        return view('partenaires',['partenaire'=> $partenaire]);
    }

    // Dans votre contrôleur
    public function partenairesSubmit()
    {
        $partenaire = $this->request->getPost();
        $logo = $this->request->getFile('logo');
    
        // Déplacez l'image du répertoire temporaire vers un répertoire de stockage définitif
        $filePath = FCPATH. 'uploads/downloads/';
        $logo->move($filePath);
        $logoUrl = 'uploads/downloads/'. $logo->getName();
    
        // Insérez les données du partenaire dans la base de données
        $this->partenairesModel->insert($partenaire);
        $idPartenaires = $this->partenairesModel->insertID();
    
        // Mettez à jour la table pour ajouter le logo
        $this->partenairesModel->update($idPartenaires, ['logo' => $logoUrl]);
    
        return redirect()->to("partenaires");
    }
}
