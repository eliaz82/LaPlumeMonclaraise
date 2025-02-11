<?php

namespace App\Controllers;

class FusionAssociation extends BaseController
{
    private $associationModel;
    private $partenairesModel;
    private $adherantsModel;

    public function __construct()
    {
        $this->adherantsModel = model('Adherants');
        $this->partenairesModel = model('Partenaires');
        $this->associationModel = model('Association');
    }
    public function association()
    {
        try {
            // Récupération des équipes depuis la base de données
            $equipes = $this->adherantsModel->findAll();
            if (empty($equipes)) {
                log_message('error', 'Aucune équipe trouvée dans la base de données.');
            }

            // Récupération des partenaires depuis la base de données
            $partenaire = $this->partenairesModel->findAll();
            if (empty($partenaire)) {
                log_message('error', 'Aucun partenaire trouvé dans la base de données.');
            }

            // Récupération de l'association avec l'id 1
            $association = $this->associationModel->find(1);
            if (empty($association)) {
                log_message('error', 'Aucune association trouvée avec l\'ID 1.');
            }

            // Retourner la vue avec les données
            return view('association', [
                'equipes' => $equipes,
                'partenaire' => $partenaire,
                'association' => $association,
            ]);
        } catch (\Exception $e) {
            // Log toutes les exceptions
            log_message('error', 'Erreur inconnue dans le contrôleur association : ' . $e->getMessage());

            // En cas d'erreur, on retourne une vue avec des messages d'erreur ou des données vides
            return view('association', [
                'equipes' => [],
                'partenaire' => [],
                'association' => [],
            ]);
        }
    }


    /*controller adhérants*/

    // Ajouter un adhérent
    public function equipeSubmit()
    {
        // Définir les règles de validation pour les champs du formulaire
        $validationRules = [
            'nom' => 'required|max_length[50]',
            'prenom' => 'required|max_length[50]',
            'grade' => 'required',  // Le grade est obligatoire
            'photo' => 'uploaded[photo]|is_image[photo]|max_size[photo,2048]',  // Max 2MB, doit être une image
        ];

        // Valider les données du formulaire
        if (!$this->validate($validationRules)) {
            // Si les validations échouent, rediriger avec les erreurs
            return redirect()->to('/association#equipe')->withInput()->with('validation', $this->validator->getErrors());
        }

        try {
            // Récupérer les données envoyées par le formulaire
            $adherant = $this->request->getPost();
            $photo = $this->request->getFile('photo');

            // Vérifier si la photo est valide
            if (!$photo->isValid()) {
                log_message('error', 'Photo invalide reçue.');
                return redirect()->to('/association#equipe')->with('error', 'Erreur lors du téléchargement de la photo.');
            }

            // Déplacer l'image du répertoire temporaire vers un répertoire de stockage définitif
            $filePath = FCPATH . 'uploads/adherants/';
            if (!$photo->move($filePath)) {
                log_message('error', 'Erreur lors du déplacement de la photo.');
                return redirect()->to('/association#equipe')->with('error', 'Erreur lors du stockage de la photo.');
            }

            $photoUrl = 'uploads/adherants/' . $photo->getName();

            // Insérer les données de l'adhérent dans la base de données
            $adherantData = [
                'nom' => $adherant['nom'],
                'prenom' => $adherant['prenom'],
                'grade' => $adherant['grade'],  // Pas de restriction, juste l'obligation
            ];

            // Insérer l'adhérent dans la base de données
            if (!$this->adherantsModel->insert($adherantData)) {
                log_message('error', 'Erreur lors de l\'insertion des données de l\'adhérent.');
                return redirect()->to('/association#equipe')->with('error', 'Erreur lors de l\'ajout de l\'adhérent.');
            }

            // Récupérer l'ID de l'adhérent nouvellement inséré
            $idAdherant = $this->adherantsModel->insertID();

            // Mettre à jour la table pour ajouter le chemin de la photo
            if (!$this->adherantsModel->update($idAdherant, ['photo' => $photoUrl])) {
                log_message('error', 'Erreur lors de la mise à jour de la photo de l\'adhérent.');
                return redirect()->to('/association#equipe')->with('error', 'Erreur lors de la mise à jour de la photo.');
            }

            // Rediriger vers la page des équipes avec un message de succès
            return redirect()->to('/association#equipe')->with('success', 'Adhérent ajouté avec succès');
        } catch (\Exception $e) {
            // Log toute exception
            log_message('error', 'Erreur inconnue lors de l\'ajout de l\'adhérent : ' . $e->getMessage());

            // En cas d'erreur, rediriger vers la page des équipes avec un message d'erreur générique
            return redirect()->to('/association#equipe')->with('error', 'Une erreur est survenue lors de l\'ajout de l\'adhérent.');
        }
    }




    // Modifier un adhérent
    // Modifier un adhérent
    public function equipeUpdate()
    {
        // Récupérer l'ID de l'adhérent à modifier
        $idAdherant = $this->request->getPost('idAdherant');
        $adherant = $this->adherantsModel->find($idAdherant);

        // Vérifier si l'adhérent existe
        if (!$adherant) {
            return redirect()->to('/association#equipe')->with('error', 'Adhérent introuvable.');
        }

        // Définir les règles de validation pour les champs du formulaire
        $validationRules = [
            'nom' => 'required|max_length[50]',
            'prenom' => 'required|max_length[50]',
            'grade' => 'required',  // Le grade est obligatoire
            'photo' => 'is_image[photo]|max_size[photo,2048]',  // La photo est optionnelle, si présente elle doit être valide
        ];

        // Valider les données du formulaire
        if (!$this->validate($validationRules)) {
            // Si la validation échoue, rediriger avec les erreurs
            return redirect()->to('/association#equipe')->withInput()->with('validation', $this->validator->getErrors());
        }

        try {
            // Récupérer les données envoyées par le formulaire
            $data = $this->request->getPost();

            // Gestion de l'upload de la photo
            $photo = $this->request->getFile('photo');
            if ($photo && $photo->isValid()) {
                // Déplacer la nouvelle photo dans le répertoire de stockage définitif
                $filePath = FCPATH . 'uploads/adherants/';
                $photo->move($filePath);
                $photoUrl = 'uploads/adherants/' . $photo->getName();

                // Si l'adhérent a déjà une photo existante, la supprimer
                if (!empty($adherant['photo']) && file_exists(FCPATH . $adherant['photo'])) {
                    unlink(FCPATH . $adherant['photo']);
                }

                // Ajouter le chemin de la nouvelle photo aux données
                $data['photo'] = $photoUrl;
            }

            // Mettre à jour les données de l'adhérent dans la base de données
            $this->adherantsModel->update($idAdherant, $data);

            // Rediriger vers la page des équipes avec un message de succès
            return redirect()->to('/association#equipe')->with('success', 'Adhérent modifié avec succès');
        } catch (\Exception $e) {
            // Log toute exception
            log_message('error', 'Erreur inconnue lors de la mise à jour de l\'adhérent : ' . $e->getMessage());

            // En cas d'erreur, rediriger vers la page des équipes avec un message d'erreur générique
            return redirect()->to('/association#equipe')->with('error', 'Une erreur est survenue lors de la mise à jour de l\'adhérent.');
        }
    }



    // Supprimer un adhérent
    // Supprimer un adhérent
    public function equipeDelete()
    {
        // Récupérer l'ID de l'adhérent à supprimer
        $idAdherant = $this->request->getPost('idAdherant');

        // Vérifier si l'adhérent existe
        $adherant = $this->adherantsModel->find($idAdherant);
        if (!$adherant) {
            return redirect()->to('/association#equipe')->with('error', 'Adhérent introuvable.');
        }

        try {
            // Supprimer la photo si elle existe
            if (!empty($adherant['photo']) && file_exists(FCPATH . $adherant['photo'])) {
                unlink(FCPATH . $adherant['photo']);
            }

            // Supprimer l'adhérent
            if (!$this->adherantsModel->delete($idAdherant)) {
                log_message('error', 'Erreur lors de la suppression de l\'adhérent.');
                return redirect()->to('/association#equipe')->with('error', 'Erreur lors de la suppression de l\'adhérent.');
            }

            // Rediriger vers la page des équipes avec un message de succès
            return redirect()->to('/association#equipe')->with('success', 'Le membre de l\'équipe a été supprimé avec succès.');
        } catch (\Exception $e) {
            // Log toute exception
            log_message('error', 'Erreur inconnue lors de la suppression de l\'adhérent : ' . $e->getMessage());

            // En cas d'erreur, rediriger avec un message d'erreur générique
            return redirect()->to('/association#equipe')->with('error', 'Une erreur est survenue lors de la suppression de l\'adhérent.');
        }
    }



    /*controller partenaires*/

    // Dans votre contrôleur
    // Ajouter un partenaire
    public function partenairesSubmit()
    {
        // Définir les règles de validation pour les champs du formulaire
        $validationRules = [
            'info' => 'required|max_length[500]',  // L'information du partenaire est obligatoire et doit être limitée à 500 caractères
            'logo' => 'uploaded[logo]|is_image[logo]|max_size[logo,2048]',  // Le logo est obligatoire, doit être une image et sa taille doit être inférieure à 2MB
            'lien' => 'permit_empty|valid_url',  // Le lien est optionnel et doit être un URL valide si fourni
        ];

        // Valider les données du formulaire
        if (!$this->validate($validationRules)) {
            // Si les validations échouent, rediriger avec les erreurs
            return redirect()->to('/association#partenaire')->withInput()->with('validation', $this->validator->getErrors());
        }

        try {
            // Récupérer les données envoyées par le formulaire
            $partenaire = $this->request->getPost();
            $logo = $this->request->getFile('logo');

            // Vérifier si le logo est valide
            if (!$logo->isValid()) {
                log_message('error', 'Logo invalide reçu.');
                return redirect()->to('/association#partenaire')->with('error', 'Erreur lors du téléchargement du logo.');
            }

            // Déplacer l'image du répertoire temporaire vers un répertoire de stockage définitif
            $filePath = FCPATH . 'uploads/partenaires/';
            if (!$logo->move($filePath)) {
                log_message('error', 'Erreur lors du déplacement du logo.');
                return redirect()->to('/association#partenaire')->with('error', 'Erreur lors du stockage du logo.');
            }

            $logoUrl = 'uploads/partenaires/' . $logo->getName();

            // Insérer les données du partenaire dans la base de données
            $partenaireData = [
                'info' => $partenaire['info'],
                'lien' => $partenaire['lien'] ?? null,  // Si le lien n'est pas fourni, le laisser null
            ];

            // Insérer le partenaire dans la base de données
            if (!$this->partenairesModel->insert($partenaireData)) {
                log_message('error', 'Erreur lors de l\'insertion des données du partenaire.');
                return redirect()->to('/association#partenaire')->with('error', 'Erreur lors de l\'ajout du partenaire.');
            }

            // Récupérer l'ID du partenaire nouvellement inséré
            $idPartenaire = $this->partenairesModel->insertID();

            // Mettre à jour la table pour ajouter le logo
            if (!$this->partenairesModel->update($idPartenaire, ['logo' => $logoUrl])) {
                log_message('error', 'Erreur lors de la mise à jour du logo du partenaire.');
                return redirect()->to('/association#partenaire')->with('error', 'Erreur lors de la mise à jour du logo.');
            }

            // Rediriger vers la page des partenaires avec un message de succès
            return redirect()->to('/association#partenaire')->with('success', 'Partenaire ajouté avec succès');
        } catch (\Exception $e) {
            // Log toute exception
            log_message('error', 'Erreur inconnue lors de l\'ajout du partenaire : ' . $e->getMessage());

            // En cas d'erreur, rediriger vers la page des partenaires avec un message d'erreur générique
            return redirect()->to('/association#partenaire')->with('error', 'Une erreur est survenue lors de l\'ajout du partenaire.');
        }
    }

    public function partenairesUpdate()
    {
        // Récupérer l'ID du partenaire à mettre à jour
        $idPartenaire = $this->request->getPost('idPartenaire');

        // Récupérer les données envoyées par le formulaire
        $data = $this->request->getPost();
        $logo = $this->request->getFile('logo');
        $partenaire = $this->partenairesModel->find($idPartenaire);

        // Définir les règles de validation pour les champs du formulaire
        $validationRules = [
            'info' => 'required|max_length[500]',  // L'information du partenaire est obligatoire et doit être limitée à 500 caractères
            'logo' => 'permit_empty|is_image[logo]|max_size[logo,2048]',  // Le logo est optionnel, mais s'il est fourni, il doit être une image valide et ne pas dépasser 2MB
            'lien' => 'permit_empty|valid_url',  // Le lien est optionnel et doit être une URL valide si fourni
        ];

        // Valider les données du formulaire
        if (!$this->validate($validationRules)) {
            // Si les validations échouent, rediriger avec les erreurs
            return redirect()->to('/association#partenaire')->withInput()->with('validation', $this->validator->getErrors());
        }

        try {
            // Si un nouveau logo est téléchargé, remplacez l'ancien
            if ($logo && $logo->isValid() && !$logo->hasMoved()) {
                // Déplacez le nouveau logo vers le répertoire de stockage définitif
                $filePath = FCPATH . 'uploads/partenaires/';
                $logo->move($filePath);
                $logoUrl = 'uploads/partenaires/' . $logo->getName();

                // Supprimer l'ancien logo si nécessaire
                if (!empty($partenaire['logo']) && file_exists(FCPATH . $partenaire['logo'])) {
                    unlink(FCPATH . $partenaire['logo']);
                }

                // Ajouter l'URL du nouveau logo aux données à mettre à jour
                $data['logo'] = $logoUrl;
            }

            // Mettre à jour les autres informations du partenaire
            $data['info'] = $data['info'];
            $data['lien'] = $data['lien'] ?? null;  // Si le lien n'est pas fourni, le laisser null

            // Mettre à jour les données du partenaire dans la base de données
            if (!$this->partenairesModel->update($idPartenaire, $data)) {
                log_message('error', 'Erreur lors de la mise à jour des données du partenaire.');
                return redirect()->to('/association#partenaire')->with('error', 'Erreur lors de la mise à jour du partenaire.');
            }

            // Rediriger vers la page des partenaires avec un message de succès
            return redirect()->to('/association#partenaire')->with('success', 'Partenaire modifié avec succès');
        } catch (\Exception $e) {
            // Log toute exception
            log_message('error', 'Erreur inconnue lors de la mise à jour du partenaire : ' . $e->getMessage());

            // En cas d'erreur, rediriger vers la page des partenaires avec un message d'erreur générique
            return redirect()->to('/association#partenaire')->with('error', 'Une erreur est survenue lors de la mise à jour du partenaire.');
        }
    }

    public function partenairesDelete()
    {
        // Récupérer l'ID du partenaire à supprimer
        $idPartenaire = $this->request->getPost('idPartenaire');

        // Trouver le partenaire dans la base de données
        $partenaire = $this->partenairesModel->find($idPartenaire);

        if ($partenaire) {
            // Vérifier si un logo est associé et le supprimer si il existe
            if (!empty($partenaire['logo']) && file_exists(FCPATH . $partenaire['logo'])) {
                // Log en cas d'erreur lors de la suppression du logo
                if (!unlink(FCPATH . $partenaire['logo'])) {
                    log_message('error', 'Erreur lors de la suppression du logo du partenaire ID: ' . $idPartenaire);
                }
            }

            // Suppression du partenaire dans la base de données
            if (!$this->partenairesModel->delete($idPartenaire)) {
                // Log en cas d'erreur lors de la suppression du partenaire
                log_message('error', 'Erreur lors de la suppression du partenaire ID: ' . $idPartenaire);

                // Rediriger vers la page des partenaires avec un message d'erreur
                return redirect()->to('/association#partenaire')->with('error', 'Erreur lors de la suppression du partenaire');
            }

            // Rediriger vers la page des partenaires avec un message de succès
            return redirect()->to('/association#partenaire')->with('success', 'Partenaire supprimé avec succès');
        } else {
            // Log si le partenaire n'est pas trouvé
            log_message('error', 'Partenaire ID ' . $idPartenaire . ' non trouvé');

            // Si le partenaire n'existe pas
            return redirect()->to('/association#partenaire')->with('error', 'Partenaire non trouvé');
        }
    }
}
