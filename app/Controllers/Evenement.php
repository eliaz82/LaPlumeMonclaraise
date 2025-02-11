<?php

namespace App\Controllers;

use App\Libraries\FacebookCache;

class Evenement extends BaseController
{
    private $facebookModel;
    private $facebookCache;
    private $evenementsModel;
    private $validation;


    public function __construct()
    {
        $this->facebookModel = model('Facebook');
        $this->facebookCache = new FacebookCache();
        $this->evenementsModel = model('Evenements');
        $this->validation = \Config\Services::validation();
    }
    public function evenement(string $id = null): string
    {
        try {
            // Récupération sécurisée des posts et des hashtags
            $posts = $this->facebookCache->getFacebookPosts() ?? ['data' => []];
            $hashtags = $this->facebookModel->where('pageName', 'evenementCalendrier')->findAll() ?? [];
            $hashtagList = array_column($hashtags, 'hashtag');

            if (empty($posts['data'])) {
                log_message('error', "Aucun post récupéré depuis Facebook pour l'événement.");
            }

            // Filtrer les posts qui contiennent l'un des hashtags
            $filteredPosts = array_filter($posts['data'], function ($post) use ($hashtagList) {
                if (!isset($post['message'])) {
                    return false;
                }
                foreach ($hashtagList as $hashtag) {
                    if (strpos($post['message'], $hashtag) !== false) {
                        return true;
                    }
                }
                return false;
            });

            // Date actuelle fixée à 23:59:59
            $currentDate = new \DateTime();
            $currentDate->setTime(23, 59, 59);
            $currentTimestamp = $currentDate->getTimestamp();

            // Traitement des posts Facebook sécurisés
            foreach ($filteredPosts as &$post) {
                $post['image'] = $post['attachments']['data'][0]['media']['image']['src'] ?? null;
                preg_match('/\*(.*?)\*/', $post['message'], $matches);
                $post['titre'] = $matches[1] ?? substr($post['message'], 0, 50);

                if (preg_match('/(\d{2}\/\d{2}\/\d{4})/', $post['message'], $matches)) {
                    [$day, $month, $year] = explode('/', $matches[1]);
                    if (checkdate($month, $day, $year)) {
                        $post['date'] = "$year-$month-$day";
                        $eventDateTime = new \DateTime("$year-$month-$day 23:59:59");
                        $post['status'] = ($eventDateTime->getTimestamp() < $currentTimestamp) ? "Événement passé" : "Événement futur";
                        $post['timestamp'] = $eventDateTime->getTimestamp();
                    }
                } else {
                    $post['status'] = "Date inconnue";
                }
            }
            unset($post);

            // Récupérer et sécuriser les événements de la BDD
            $evenements = $this->evenementsModel->findAll() ?? [];
            foreach ($evenements as &$event) {
                try {
                    $eventDate = new \DateTime($event['date']);
                    $eventDate->setTime(23, 59, 59);
                    $event['timestamp'] = $eventDate->getTimestamp();
                    $event['status'] = ($event['timestamp'] < $currentTimestamp) ? "Événement passé" : "Événement futur";
                } catch (\Exception $e) {
                    log_message('error', "Erreur lors du traitement d'un événement: " . $e->getMessage());
                    continue;
                }
            }
            unset($event);

            // Fusionner et trier les événements
            $allPosts = array_merge($filteredPosts, $evenements);
            $allPosts = array_filter($allPosts, fn($post) => isset($post['timestamp']) && $post['timestamp'] >= $currentTimestamp);
            usort($allPosts, fn($a, $b) => $a['timestamp'] <=> $b['timestamp']);

            return view('evenements', [
                'posts' => $allPosts,
                'highlightId' => $id,
            ]);
        } catch (\Exception $e) {
            log_message('error', "Erreur dans la méthode evenement: " . $e->getMessage());
            return view('evenements', [
                'posts' => [],
                'highlightId' => $id,
            ]);
        }
    }


    public function createEvenement()
    {
        try {
            // Récupération des données
            $evenement = $this->request->getPost();
            $image = $this->request->getFile('image');
    
            // Préparer les données de validation
            $validationData = $evenement;
    
            // Ajouter l'image aux données de validation si elle est valide
            if ($image && $image->isValid() && !$image->hasMoved()) {
                $validationData['image'] = $image;  // Ajouter l'image à la validation si elle est présente
            }
    
            // Définir les règles de validation
            $rules = [
                'titre'   => 'required|min_length[3]',
                'message' => 'required|min_length[10]',
                'date'    => 'required|valid_date',
                // Ne valider l'image que si elle est présente et valide
                'image'   => 'permit_empty|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png]',
            ];
    
            // Appliquer la validation
            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('error', implode('<br>', $this->validator->getErrors()));
            }
    
            $imageUrl = null;
    
            // Vérifier et traiter l'image si elle est fournie
            if ($image && $image->isValid() && !$image->hasMoved()) {
                $newName = $image->getRandomName();
                $image->move(FCPATH . 'uploads/evenements/', $newName);
                $imageUrl = 'uploads/evenements/' . $newName;
            }
    
            // Insérer l'événement dans la base de données
            $this->evenementsModel->insert([
                'titre'   => esc($evenement['titre']),
                'message' => esc($evenement['message']),
                'image'   => $imageUrl,
                'date'    => esc($evenement['date']),
            ]);
    
            return redirect()->to('/evenement')->with('success', 'Événement ajouté avec succès');
        } catch (\Exception $e) {
            log_message('error', 'Erreur lors de l\'ajout de l\'événement : ' . $e->getMessage());
            return redirect()->back()->with('error', 'Une erreur est survenue.');
        }
    }
    
    

    public function updateEvenement()
    {
        try {
            // Récupérer l'ID de l'événement à modifier
            $idEvenement = $this->request->getPost('idEvenement');
    
            // Vérifier si l'événement existe
            $evenement = $this->evenementsModel->find($idEvenement);
            if (!$evenement) {
                return redirect()->back()->with('error', 'Événement introuvable.');
            }
    
            // Définir les règles de validation directement
            $rules = [
                'titre'   => 'required|min_length[3]',
                'message' => 'required|min_length[10]',
                'date'    => 'required|valid_date',
                'image'   => 'permit_empty|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png]',
            ];
    
            // Supprimer la contrainte "required" sur l'image car ce n'est pas obligatoire en mise à jour
            unset($rules['image']);
    
            // Validation des données sans l'image
            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('error', implode('<br>', $this->validator->getErrors()));
            }
    
            // Récupérer les données envoyées
            $data = [
                'titre'   => esc($this->request->getPost('titre')),
                'message' => esc($this->request->getPost('message')),
                'date'    => esc($this->request->getPost('date')),
            ];
    
            // Gestion de l'upload de la nouvelle image si elle est fournie
            $image = $this->request->getFile('image');
            if ($image && $image->isValid() && !$image->hasMoved()) {
                $filePath = FCPATH . 'uploads/evenements/';
                $newName = $image->getRandomName();
                $image->move($filePath, $newName);
                $imageUrl = 'uploads/evenements/' . $newName;
    
                // Supprimer l'ancienne image si elle existe et n'est pas vide
                if (!empty($evenement['image']) && file_exists(FCPATH . $evenement['image'])) {
                    unlink(FCPATH . $evenement['image']);
                }
    
                // Ajouter la nouvelle image aux données
                $data['image'] = $imageUrl;
            }
    
            // Mise à jour de l'événement
            $this->evenementsModel->update($idEvenement, $data);
    
            return redirect()->to('/evenement')->with('success', 'L\'événement a été modifié avec succès');
        } catch (\Exception $e) {
            log_message('error', 'Erreur lors de la modification de l\'événement : ' . $e->getMessage());
            return redirect()->back()->with('error', 'Une erreur est survenue.');
        }
    }
    
    


    public function evenementDelete()
    {
        try {
            // Récupérer l'ID de l'événement à supprimer
            $idEvenement = $this->request->getPost('idEvenement');
            $evenement = $this->evenementsModel->find($idEvenement);
    
            if (!$evenement) {
                return redirect()->back()->with('error', 'Événement introuvable.');
            }
    
            // Vérifier si l'événement possède une image et si elle existe
            if (!empty($evenement['image']) && file_exists(FCPATH . $evenement['image'])) {
                // Supprimer l'image associée à l'événement
                unlink(FCPATH . $evenement['image']);
            }
    
            // Supprimer l'événement de la base de données
            $this->evenementsModel->delete($idEvenement);
    
            // Rediriger vers la page des événements avec un message de succès
            return redirect()->to('/evenement')->with('success', 'L\'événement a été supprimé avec succès.');
        } catch (\Exception $e) {
            log_message('error', 'Erreur lors de la suppression de l\'événement : ' . $e->getMessage());
            return redirect()->back()->with('error', 'Une erreur est survenue lors de la suppression.');
        }
    }
    
}
