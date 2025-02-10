<?php

namespace App\Controllers;

use App\Libraries\FacebookCache;

class AlbumsPhoto extends BaseController
{
    private $albumsPhoto;
    private $photoModel;
    private $facebookModel;
    private $facebookCache;
    private $validation;

    public function __construct()
    {
        $this->albumsPhoto = model('AlbumsPhoto');
        $this->photoModel = model('Photo');
        $this->facebookModel = model('Facebook');
        $this->facebookCache = new FacebookCache();
        $this->validation = \Config\Services::validation();
    }

    public function albumsPhoto(): string
    {
        // Initialiser la variable $albumsPhotos avec un tableau vide
        $albumsPhotos = [];
    
        try {
            // Récupérer tous les albums photos
            $albumsPhotos = $this->albumsPhoto->findAll();
    
            // Appels à l'API pour récupérer les posts et les images
            $posts = $this->facebookCache->getFacebookPosts();
            
            // Vérification que les posts sont bien récupérés
            if (empty($posts['data'])) {
                throw new \Exception("Aucun post récupéré depuis Facebook.");
            }
    
            // Récupérer les hashtags
            $hashtags = $this->facebookModel->where('pageName', 'albumsphoto')->findAll();
            $hashtagList = array_column($hashtags, 'hashtag');
    
            $filteredPosts = array_filter($posts['data'], function ($post) use ($hashtagList) {
                if (isset($post['message'])) {
                    foreach ($hashtagList as $hashtag) {
                        if (strpos($post['message'], $hashtag) !== false) {
                            return true;
                        }
                    }
                }
                return false;
            });
    
            $facebookMapping = [];
    
            foreach ($filteredPosts as $post) {
                $dateAlbums = null;
                // Extraction de la date de l'album
                if (preg_match('/(\d{2}\/\d{2}\/\d{4})/', $post['message'], $matches)) {
                    $dateAlbums = $matches[1];
                }
    
                // Validation de la date extraite
                if ($dateAlbums) {
                    list($day, $month, $year) = explode('/', $dateAlbums);
                    if (!checkdate($month, $day, $year)) {
                        throw new \Exception("Date invalide extraite du post.");
                    }
                }
    
                // Utiliser la date de publication par défaut si aucune date n'est extraite
                if (!$dateAlbums) {
                    $dateAlbums = date('Y-m-d', strtotime($post['created_time']));
                } else {
                    $dateAlbums = "$year-$month-$day";
                }
    
                // Vérifier la présence de l'image
                if (isset($post['attachments']['data'][0]['media']['image']['src'])) {
                    $facebookMapping[$dateAlbums] = $post['id'];
                }
    
                preg_match('/\*(.*?)\*/', $post['message'], $titleMatches);
                $titreAlbums = isset($titleMatches[1]) ? $titleMatches[1] : $dateAlbums;
    
                // Vérification des images associées à ce post
                if (isset($post['attachments']['data'][0]['media']['image']['src'])) {
                    $photo = $post['attachments']['data'][0]['media']['image']['src'];
    
                    // Vérifier si un album existe déjà avec cette date
                    $existingAlbum = $this->albumsPhoto->where('dateAlbums', $dateAlbums)->first();
    
                    if (!$existingAlbum) {
                        // Créer un nouvel album si nécessaire
                        $this->albumsPhoto->save([
                            'dateAlbums' => $dateAlbums,
                            'nom' => $titreAlbums,
                            'photo' => $photo,
                        ]);
    
                        // Récupérer l'ID de l'album créé
                        $idAlbums = $this->albumsPhoto->getInsertID();
                    } else {
                        $idAlbums = $existingAlbum['idAlbums'];
                    }
    
                    // Stocker les photos de Facebook
                    $photosFacebook = [];
                    $seenImageUrls = [];
    
                    // Vérifier et ajouter les images associées
                    if (isset($post['attachments']['data'][0]['media']['image']['src'])) {
                        $imageSrc = $post['attachments']['data'][0]['media']['image']['src'];
                        $imageId = $post['attachments']['data'][0]['target']['id'];
                        if (!in_array($imageSrc, $seenImageUrls)) {
                            $photosFacebook[] = ['url' => $imageSrc, 'id' => $imageId];
                            $seenImageUrls[] = $imageSrc;
                        }
                    }
    
                    // Gestion des sous-pièces jointes
                    if (isset($post['attachments']['data'][0]['subattachments']['data'])) {
                        foreach ($post['attachments']['data'][0]['subattachments']['data'] as $subattachment) {
                            if (isset($subattachment['media']['image']['src'])) {
                                $imageSrc = $subattachment['media']['image']['src'];
                                $imageId = $subattachment['target']['id'];
                                if (!in_array($imageSrc, $seenImageUrls)) {
                                    $photosFacebook[] = ['url' => $imageSrc, 'id' => $imageId];
                                    $seenImageUrls[] = $imageSrc;
                                }
                            }
                        }
                    }
    
                    // Enregistrer les images si elles n'existent pas déjà
                    foreach ($photosFacebook as $photoData) {
                        $imageExists = $this->photoModel->where('idPhotoFacebook', $photoData['id'])->first();
    
                        if (!$imageExists) {
                            $this->photoModel->save([
                                'idAlbums' => $idAlbums,
                                'photo' => $photoData['url'],
                                'idPhotoFacebook' => $photoData['id'],
                            ]);
                        }
                    }
                }
            }
    
            // Trier les albums photo
            $tri = $this->request->getGet('tri') ?? 'desc';
            $albumsPhotos = $this->albumsPhoto->orderBy('dateAlbums', $tri)->findAll();
    
            // Ajouter l'URL du post Facebook si existant
            foreach ($albumsPhotos as $key => $album) {
                if (isset($facebookMapping[$album['dateAlbums']])) {
                    $albumsPhotos[$key]['postFacebookUrl'] = "https://www.facebook.com/" . $facebookMapping[$album['dateAlbums']];
                } else {
                    $albumsPhotos[$key]['postFacebookUrl'] = "";
                }
            }
    
            // Retourner la vue avec les albums et le tri
            return view('albumsPhoto', ['albumsPhotos' => $albumsPhotos, 'tri' => $tri]);
        } catch (\Exception $e) {
            // Si une erreur se produit, retourner un message d'erreur
            return view('albumsPhoto', [
                'albumsPhotos' => [], // Passer un tableau vide pour ne pas avoir d'erreur dans la vue
                'tri' => 'desc', // Passer un tri par défaut
                'error' => "Une erreur est survenue : " . $e->getMessage()
            ]);
        }
    }
    
    
    

    public function createAlbumsPhoto()
    {
        // Récupérer les données envoyées
        $albumPhotoData = $this->request->getPost();
    
        // Appliquer les règles de validation
        if (!$this->validation->run($albumPhotoData, 'album_photo_rules')) {
            // Si la validation échoue, rediriger avec les erreurs sous la clé 'validation'
            return redirect()->back()->withInput()->with('validation', $this->validation->getErrors());
        }
    
        // Récupérer le fichier photo
        $photo = $this->request->getFile('photo');
    
        // Vérifier si un fichier est téléchargé et valide
        if ($photo && $photo->isValid()) {
            $filePath = FCPATH . 'uploads/albumsPhoto/';
            
            // Vérifier si le dossier existe, sinon le créer
            if (!is_dir($filePath)) {
                mkdir($filePath, 0755, true);  // Créer les sous-dossiers avec permissions restreintes
            }
    
            // Déplacer le fichier téléchargé
            $photo->move($filePath);
    
            // Ajouter le chemin de la photo aux données de l'album
            $albumPhotoData['photo'] = 'uploads/albumsPhoto/' . $photo->getName();
        }
    
        // Insérer l'album photo dans la base de données
        $this->albumsPhoto->insert($albumPhotoData);
    
        // Retourner à la liste des albums avec un message de succès
        return redirect()->route('albumsPhoto')->with('success', "L'album photo a été ajouté avec succès.");
    }
    
    

    public function updateAlbumsPhoto()
    {
        // Récupérer les données envoyées
        $albumPhotoData = $this->request->getPost();
    
        // Appliquer les règles de validation
        if (!$this->validation->run($albumPhotoData, 'album_photo_rules')) {
            // Si la validation échoue, rediriger avec les erreurs sous la clé 'validation'
            return redirect()->back()->withInput()->with('validation', $this->validation->getErrors());
        }
    
        // Récupérer l'ID de l'album
        $idAlbums = $this->request->getPost('idAlbums');
        $album = $this->albumsPhoto->find($idAlbums);
    
        // Récupérer le fichier photo
        $photo = $this->request->getFile('photo');
    
        // Vérifier si un fichier est téléchargé et valide
        if ($photo && $photo->isValid()) {
            $filePath = FCPATH . 'uploads/albumsPhoto/';
            
            // Vérifier si le dossier existe, sinon le créer
            if (!is_dir($filePath)) {
                mkdir($filePath, 0755, true);  // Créer les sous-dossiers avec permissions restreintes
            }
    
            // Déplacer le fichier téléchargé
            $photo->move($filePath);
    
            // Ajouter le chemin de la photo aux données de l'album
            $albumPhotoData['photo'] = 'uploads/albumsPhoto/' . $photo->getName();
    
            // Supprimer l'ancienne photo si elle existe
            if (!empty($album['photo']) && file_exists(FCPATH . $album['photo'])) {
                unlink(FCPATH . $album['photo']);
            }
        }
    
        // Mettre à jour l'album photo dans la base de données
        $this->albumsPhoto->update($idAlbums, $albumPhotoData);
    
        // Retourner à la liste des albums avec un message de succès
        return redirect()->route('albumsPhoto')->with('success', "L'album photo a été modifié avec succès.");
    }
    

    public function albumsPhotoDelete()
    {
        // Récupérer l'instance de la base de données
        $db = \Config\Database::connect();
        
        // Démarrer une transaction
        $db->transBegin();
    
        try {
            // Récupérer l'ID de l'album photo à supprimer
            $idAlbums = $this->request->getPost('idAlbums');
    
            // Vérifier si l'ID est valide
            if (empty($idAlbums) || !is_numeric($idAlbums)) {
                throw new \Exception("ID d'album invalide.");
            }
    
            // Récupérer l'album photo depuis la base de données
            $album = $this->albumsPhoto->find($idAlbums);
    
            // Vérifier si l'album existe
            if (!$album) {
                throw new \Exception("L'album photo n'existe pas.");
            }
    
            // Récupérer les photos associées à l'album
            $photos = $this->photoModel->where('idAlbums', $idAlbums)->findAll();
    
            // Supprimer les photos associées
            foreach ($photos as $photo) {
                if (!empty($photo['photo']) && file_exists(FCPATH . $photo['photo']) && is_writable(FCPATH . $photo['photo'])) {
                    unlink(FCPATH . $photo['photo']);
                }
            }
    
            // Supprimer les entrées des photos associées
            $this->photoModel->where('idAlbums', $idAlbums)->delete();
    
            // Supprimer l'image de l'album si elle existe
            if (!empty($album['photo']) && file_exists(FCPATH . $album['photo'])) {
                unlink(FCPATH . $album['photo']);
            }
    
            // Supprimer l'album photo
            $this->albumsPhoto->delete($idAlbums);
    
            // Commit la transaction
            $db->transCommit();
    
            return redirect()->route('albumsPhoto')->with('success', "L'album photo a été supprimé avec succès.");
        } catch (\Exception $e) {
            // Si une exception se produit, annuler la transaction
            $db->transRollback();
    
            // Retourner avec un message d'erreur
            return redirect()->route('albumsPhoto')->with('error', "Une erreur est survenue : " . $e->getMessage());
        }
    }
    

    public function photo($idAlbums)
    {
        $photos = $this->photoModel->findPhotobyAlbumsPhotoId($idAlbums);

        $album = $this->albumsPhoto->find($idAlbums);

        if (empty($photos)) {
            $photos = [];
        }

        return view('photo', ['photos' => $photos, 'idAlbums' => $idAlbums, 'album' => $album]);
    }
    public function createPhoto()
    {
        $idAlbums = $this->request->getPost('idAlbums');

        $files = $this->request->getFiles();

        if (isset($files['photo'])) {
            foreach ($files['photo'] as $photo) {
                if ($photo->isValid() && !$photo->hasMoved()) {
                    $filePath = FCPATH . 'uploads/photos/';
                    $photo->move($filePath);
                    $data = [
                        'photo' => 'uploads/photos/' . $photo->getName(),
                        'idAlbums' => $idAlbums,
                    ];
                    $this->photoModel->insert($data);
                }
            }
        }
        return redirect()->to("/albums-photo/{$idAlbums}")
            ->with('success', 'Photo(s) ajoutée(s) avec succès.');
    }


    public function photoDelete()
    {
        $idPhoto = $this->request->getPost('idPhoto');
        $photo = $this->photoModel->find($idPhoto);

        if (!empty($photo['photo']) && file_exists(FCPATH . $photo['photo'])) {
            unlink(FCPATH . $photo['photo']);
        }

        $this->photoModel->delete($idPhoto);

        return redirect()->to("/albums-photo/{$photo['idAlbums']}")->with('success', 'Photo supprimée avec succès.');
    }
}
