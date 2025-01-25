<?php

namespace App\Controllers;

class FusionAssociation extends BaseController
{

    private $partenairesModel;
    private $adherantsModel;

    public function __construct()
    {
        $this->adherantsModel = model('Adherants');
        $this->partenairesModel = model('Partenaires');
    }
    public function association()
    {
        $equipes = $this->adherantsModel->findAll();
        $partenaire = $this->partenairesModel->findAll();
    
        return view('association', [
            'equipes' => $equipes,
            'partenaire' => $partenaire,
        ]);
    }

    /*controller adhérants*/


    public function equipe()
    {
        // Récupérer tous les adhérents depuis la base de données
        $equipes = $this->adherantsModel->findAll();

        // Charger la vue avec les adhérents
        return view('equipes', ['equipes' => $equipes]);
    }

    // Ajouter un adhérent
    public function equipeSubmit()
    {
        // Récupérer les données envoyées par le formulaire
        $adherant = $this->request->getPost();
        $photo = $this->request->getFile('photo');

        // Déplacer l'image du répertoire temporaire vers un répertoire de stockage définitif
        $filePath = FCPATH . 'uploads/adherants/';
        $photo->move($filePath);
        $photoUrl = 'uploads/adherants/' . $photo->getName();

        // Insérer les données de l'adhérent dans la base de données
        $this->adherantsModel->insert($adherant);
        $idAdherant = $this->adherantsModel->insertID();

        // Mettre à jour la table pour ajouter le chemin de la photo
        $this->adherantsModel->update($idAdherant, ['photo' => $photoUrl]);

        // Rediriger vers la page des équipes avec un message de succès
        return redirect()->to('/association#equipe')->with('success', 'Adhérent ajouté avec succès');
    }


    // Modifier un adhérent
    public function equipeUpdate()
    {
        // Récupérer l'ID de l'adhérent à modifier
        $idAdherant = $this->request->getPost('idAdherant');
        $data = $this->request->getPost();
        $adherant = $this->adherantsModel->find($idAdherant);

        // Gestion de l'upload de la nouvelle photo
        $photo = $this->request->getFile('photo');
        if ($photo && $photo->isValid()) {
            // Déplacer la nouvelle photo dans le répertoire de stockage définitif
            $filePath = FCPATH . 'uploads/adherants/';
            $photo->move($filePath);
            $photoUrl = 'uploads/adherants/' . $photo->getName();
            if (!empty($adherant['photo']) && file_exists(FCPATH . $adherant['photo'])) {
                unlink(FCPATH . $adherant['photo']);
            }
            // Ajouter le chemin de la nouvelle photo aux données
            $data['photo'] = $photoUrl;
        }

        // Mettre à jour les données de l'adhérent
        $this->adherantsModel->update($idAdherant, $data);

        // Rediriger vers la page des équipes avec un message de succès
        return redirect()->to('/association#equipe')->with('success', 'Adhérent modifié avec succès');
    }


    // Supprimer un adhérent
    public function equipeDelete()
    {
        // Récupérer l'ID de l'adhérent à supprimer
        $idAdherant = $this->request->getPost('idAdherant');
        $adherant = $this->adherantsModel->find($idAdherant);
        if (!empty($adherant['photo']) && file_exists(FCPATH . $adherant['photo'])) {
            unlink(FCPATH . $adherant['photo']);
        }
        // Supprimer l'adhérent
        $this->adherantsModel->delete($idAdherant);

        // Rediriger vers la page des équipes avec un message de succès
        return redirect()->to('/association#equipe')->with('success', 'Le membre de l\'équipe a été supprimé avec succès.');
    }


    /*controller partenaires*/

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

        return redirect()->to("association#partenaire");
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

        return redirect()->to("association#partenaire");
    }
    public function partenairesDelete()
    {
        $idPartenaire = $this->request->getPost('idPartenaire');
        $partenaire = $this->partenairesModel->find($idPartenaire);
        if (!empty($partenaire['logo']) && file_exists(FCPATH . $partenaire['logo'])) {
            unlink(FCPATH . $partenaire['logo']);
        }
        $this->partenairesModel->delete($idPartenaire);

        return redirect()->to('/association#partenaire')->with('success', 'Partenaire supprimé avec succès');
    }
}



