<?php

namespace App\Controllers;

class Adherants extends BaseController
{
    private $adherantsModel;

    public function __construct()
    {
        $this->adherantsModel = model('Adherants');
    }
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
        return redirect()->to('/equipe')->with('success', 'Adhérent ajouté avec succès');
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
        return redirect()->to('/equipe')->with('success', 'Adhérent modifié avec succès');
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
        return redirect()->to('/equipe')->with('success', 'Le membre de l\'équipe a été supprimé avec succès.');
    }
}
