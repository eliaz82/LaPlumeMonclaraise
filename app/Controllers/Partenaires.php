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
        return view('partenaires', ['partenaire' => $partenaire]);
    }

    // Dans votre contrôleur
    public function partenairesSubmit()
    {
        $partenaire = $this->request->getPost();
        $logo = $this->request->getFile('logo');

        // Déplacez l'image du répertoire temporaire vers un répertoire de stockage définitif
        $filePath = FCPATH . 'uploads/partenaires/';
        $logo->move($filePath);
        $logoUrl = 'uploads/partenaires/' . $logo->getName();

        // Insérez les données du partenaire dans la base de données
        $this->partenairesModel->insert($partenaire);
        $idPartenaires = $this->partenairesModel->insertID();

        // Mettez à jour la table pour ajouter le logo
        $this->partenairesModel->update($idPartenaires, ['logo' => $logoUrl]);

        return redirect()->to("partenaires");
    }
    public function partenairesUpdate()
    {
        $idPartenaire = $this->request->getPost('idPartenaire');
        $data = $this->request->getPost();
        $logo = $this->request->getFile('logo');
        $partenaire = $this->partenairesModel->find($idPartenaire);

        // Si un nouveau logo est téléchargé, remplacez l'ancien
        if ($logo && $logo->isValid() && !$logo->hasMoved()) {
            $filePath = FCPATH . 'uploads/partenaires/';
            $logo->move($filePath);
            $logoUrl = 'uploads/partenaires/' . $logo->getName();
            if (!empty($partenaire['logo']) && file_exists(FCPATH . $partenaire['logo'])) {
                unlink(FCPATH . $partenaire['logo']);
            }
            $data['logo'] = $logoUrl;
        }

        // Mettez à jour les données du partenaire
        $this->partenairesModel->update($idPartenaire, $data);

        return redirect()->to("partenaires");
    }
    public function partenairesDelete()
    {
        $idPartenaire = $this->request->getPost('idPartenaire');
        $partenaire = $this->partenairesModel->find($idPartenaire);
        if (!empty($partenaire['logo']) && file_exists(FCPATH . $partenaire['logo'])) {
            unlink(FCPATH . $partenaire['logo']);
        }
        $this->partenairesModel->delete($idPartenaire);

        return redirect()->to('/partenaires')->with('success', 'Partenaire supprimé avec succès');
    }
}
