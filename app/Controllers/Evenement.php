<?php

namespace App\Controllers;

use App\Libraries\FacebookCache;

class Evenement extends BaseController
{
    private $facebookModel;
    private $facebookCache;
    private $evenementsModel;

    public function __construct()
    {
        $this->facebookModel = model('Facebook');
        $this->facebookCache = new FacebookCache();
        $this->evenementsModel = model('Evenements');
    }
    public function evenement(string $id = null): string
    {
        // Récupération des posts et des hashtags
        $posts = $this->facebookCache->getFacebookPosts();
        $hashtags = $this->facebookModel->where('pageName', 'evenementCalendrier')->findAll();
        $hashtagList = array_column($hashtags, 'hashtag');

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

        // Traitement de chaque post Facebook
        foreach ($filteredPosts as &$post) {
            $post['image'] = $post['attachments']['data'][0]['media']['image']['src'] ?? null;
            preg_match('/\*(.*?)\*/', $post['message'], $matches);
            $post['titre'] = $matches[1] ?? substr($post['message'], 0, 50);

            if (preg_match('/(\d{2}\/\d{2}\/\d{4})/', $post['message'], $matches)) {
                list($day, $month, $year) = explode('/', $matches[1]);
                if (checkdate($month, $day, $year)) {
                    $post['date'] = $matches[1];
                    $eventDateTime = new \DateTime("$year-$month-$day");
                    $eventDateTime->setTime(23, 59, 59);
                    $post['status'] = ($eventDateTime->getTimestamp() < $currentTimestamp) ? "Événement passé" : "Événement futur";
                    $post['timestamp'] = $eventDateTime->getTimestamp();
                }
            } else {
                $post['status'] = "Date inconnue";
            }
        }
        unset($post); // Bonne pratique après une boucle avec référence

        // Récupérer les événements de la base de données
        $evenements = $this->evenementsModel->findAll();
        foreach ($evenements as &$event) {
            // Créer un objet DateTime à partir de la date (en supposant qu'elle soit au format 'YYYY-MM-DD' ou convertible)
            $eventDate = new \DateTime($event['date']);
            $eventDate->setTime(23, 59, 59);
            $event['timestamp'] = $eventDate->getTimestamp();
            $event['status'] = ($event['timestamp'] < $currentTimestamp) ? "Événement passé" : "Événement futur";
        }
        unset($event);

        // Fusionner les événements de Facebook et ceux de la base de données
        $allPosts = array_merge($filteredPosts, $evenements);

        // Ne conserver que les événements futurs (ceux dont le timestamp est >= à la date actuelle)
        $allPosts = array_filter($allPosts, function ($post) use ($currentTimestamp) {
            return isset($post['timestamp']) && $post['timestamp'] >= $currentTimestamp;
        });

        // Trier les événements par date croissante (les plus proches en premier)
        usort($allPosts, function ($a, $b) {
            return $a['timestamp'] <=> $b['timestamp'];
        });

        return view('evenements', [
            'posts' => $allPosts,
            'highlightId' => $id,
        ]);
    }

    public function createEvenement()
    {
        // Récupérer les données envoyées par le formulaire
        $evenement = $this->request->getPost();
        $image = $this->request->getFile('image');

        // Vérifier si une image a été téléchargée
        if ($image && $image->isValid() && !$image->hasMoved()) {
            // Déplacer l'image du répertoire temporaire vers un répertoire de stockage définitif
            $filePath = FCPATH . 'uploads/evenements/';
            $image->move($filePath);
            $imageUrl = 'uploads/evenements/' . $image->getName();
        } else {
            // Si aucune image n'a été téléchargée, ne pas modifier le chemin de l'image
            $imageUrl = null;
        }

        // Préparer les données à insérer dans la base de données
        $evenementData = [
            'titre' => $evenement['titre'],
            'message' => $evenement['message'],
            'image' => $imageUrl,
            'date' => $evenement['date']
        ];

        // Insérer les données dans la table 'evenements'
        $this->evenementsModel->insert($evenementData);

        // Rediriger vers la page des événements avec un message de succès
        return redirect()->to('/evenement')->with('success', 'Événement ajouté avec succès');
    }
    public function updateEvenement()
    {
        // Récupérer l'ID de l'événement à modifier
        $idEvenement = $this->request->getPost('idEvenement');
        $data = $this->request->getPost();

        // Trouver l'événement existant
        $evenement = $this->evenementsModel->find($idEvenement);

        // Gestion de l'upload de la nouvelle image
        $image = $this->request->getFile('image');
        if ($image && $image->isValid()) {
            // Déplacer la nouvelle image dans le répertoire de stockage définitif
            $filePath = FCPATH . 'uploads/evenements/';
            $image->move($filePath);
            $imageUrl = 'uploads/evenements/' . $image->getName();

            // Supprimer l'ancienne image si elle existe
            if (!empty($evenement['image']) && file_exists(FCPATH . $evenement['image'])) {
                unlink(FCPATH . $evenement['image']);
            }

            // Ajouter le chemin de la nouvelle image aux données
            $data['image'] = $imageUrl;
        }

        // Mettre à jour les données de l'événement
        $this->evenementsModel->update($idEvenement, $data);

        // Rediriger vers la page des événements avec un message de succès
        return redirect()->to('/evenement')->with('success', 'L\'événement a été modifié avec succès');
    }

    public function evenementDelete()
    {
        // Récupérer l'ID de l'événement à supprimer
        $idEvenement = $this->request->getPost('idEvenement');
        $evenement = $this->evenementsModel->find($idEvenement);

        if (!empty($evenement['image']) && file_exists(FCPATH . $evenement['image'])) {
            // Supprimer l'image associée à l'événement
            unlink(FCPATH . $evenement['image']);
        }

        // Supprimer l'événement de la base de données
        $this->evenementsModel->delete($idEvenement);

        // Rediriger vers la page des événements avec un message de succès
        return redirect()->to('/evenement')->with('success', 'L\'événement a été supprimé avec succès.');
    }

}
