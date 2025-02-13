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
        // Initialisation des variables
        $albumsPhotos = [];
        $facebookMapping = [];

        try {
            dd($this->albumsPhoto);
            // Récupérer tous les albums photos
            $albumsPhotos = $this->albumsPhoto->findAll();
         

            // Récupérer les posts Facebook via le cache
            $posts = $this->facebookCache->getFacebookPosts();
            if (empty($posts['data'])) {
                throw new \Exception("Aucun post récupéré depuis Facebook.");
            }

            // Récupérer les hashtags liés aux albums
            $hashtags = array_column(
                $this->facebookModel->where('pageName', 'albumsphoto')->findAll(),
                'hashtag'
            );

            // Filtrer les posts Facebook avec les hashtags
            $filteredPosts = array_filter($posts['data'], function ($post) use ($hashtags) {
                return isset($post['message']) && array_filter($hashtags, fn($tag) => strpos($post['message'], $tag) !== false);
            });

            // Gestion des albums depuis Facebook
            foreach ($filteredPosts as $post) {
                $dateAlbums = null;

                // Extraction et validation de la date
                if (preg_match('/(\d{2}\/\d{2}\/\d{4})/', $post['message'], $matches)) {
                    [$day, $month, $year] = explode('/', $matches[1]);
                    if (checkdate($month, $day, $year)) {
                        $dateAlbums = "$year-$month-$day";
                    }
                }
                if (!$dateAlbums) {
                    $dateAlbums = date('Y-m-d', strtotime($post['created_time']));
                }

                // Récupération du titre
                preg_match('/\*(.*?)\*/', $post['message'], $titleMatches);
                $titreAlbums = $titleMatches[1] ?? $dateAlbums;

                // Vérifier et récupérer l'image principale
                $photo = $post['attachments']['data'][0]['media']['image']['src'] ?? null;

                if ($photo) {
                    $facebookMapping[$dateAlbums] = $post['id'];

                    // Vérifier si un album existe déjà
                    $existingAlbum = $this->albumsPhoto->where('dateAlbums', $dateAlbums)->first();
                    $idAlbums = $existingAlbum['idAlbums'] ?? null;

                    if (!$existingAlbum) {
                        // Créer un nouvel album
                        $this->albumsPhoto->save([
                            'dateAlbums' => $dateAlbums,
                            'nom' => $titreAlbums,
                            'photo' => $photo,
                        ]);
                        $idAlbums = $this->albumsPhoto->getInsertID();
                    }

                    // Gestion des photos associées
                    $photosFacebook = [];
                    $seenImageUrls = [];

                    // Ajouter l'image principale
                    if (!in_array($photo, $seenImageUrls)) {
                        $photosFacebook[] = ['url' => $photo, 'id' => $post['attachments']['data'][0]['target']['id']];
                        $seenImageUrls[] = $photo;
                    }

                    // Ajouter les images des sous-pièces jointes
                    if (isset($post['attachments']['data'][0]['subattachments']['data'])) {
                        foreach ($post['attachments']['data'][0]['subattachments']['data'] as $subattachment) {
                            $imageSrc = $subattachment['media']['image']['src'] ?? null;
                            $imageId = $subattachment['target']['id'] ?? null;

                            if ($imageSrc && !in_array($imageSrc, $seenImageUrls)) {
                                $photosFacebook[] = ['url' => $imageSrc, 'id' => $imageId];
                                $seenImageUrls[] = $imageSrc;
                            }
                        }
                    }

                    // Enregistrer les images si elles n'existent pas déjà
                    foreach ($photosFacebook as $photoData) {
                        if (!$this->photoModel->where('idPhotoFacebook', $photoData['id'])->first()) {
                            $this->photoModel->save([
                                'idAlbums' => $idAlbums,
                                'photo' => $photoData['url'],
                                'idPhotoFacebook' => $photoData['id'],
                            ]);
                        }
                    }
                }
            }

            // Gestion du tri
            $tri = $this->request->getGet('tri') ?? 'desc';
            $albumsPhotos = $this->albumsPhoto->orderBy('dateAlbums', $tri)->findAll();

            // Associer les posts Facebook aux albums
            foreach ($albumsPhotos as $key => $album) {
                $albumsPhotos[$key]['postFacebookUrl'] = isset($facebookMapping[$album['dateAlbums']])
                    ? "https://www.facebook.com/" . $facebookMapping[$album['dateAlbums']]
                    : "";
            }

            // Retourner la vue avec les albums
            return view('albumsPhoto', ['albumsPhotos' => $albumsPhotos, 'tri' => $tri]);
        } catch (\Exception $e) {
            log_message('error', $e->getMessage());

            return view('albumsPhoto', [
                'albumsPhotos' => [],
                'tri' => 'desc',
            ]);
        }
    }

    public function createAlbumsPhoto()
    {
        // Récupérer les données envoyées
        $albumPhotoData = $this->request->getPost();

        // Appliquer les règles de validation
        if (!$this->validation->run($albumPhotoData, 'album_photo_rules')) {
            return redirect()->back()->withInput()->with('validation', $this->validation->getErrors());
        }

        // Récupérer le fichier photo
        $photo = $this->request->getFile('photo');

        // Vérifier si un fichier est bien téléchargé et valide
        if (!$photo->isValid() || $photo->hasMoved()) {
            return redirect()->back()->withInput()->with('error', 'Le fichier est invalide ou déjà déplacé.');
        }

        // Vérifier le type de fichier
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
        if (!in_array($photo->getExtension(), $allowedExtensions)) {
            return redirect()->back()->withInput()->with('error', 'Format de fichier non autorisé.');
        }

        // Définir le chemin de destination
        $uploadPath = FCPATH . 'uploads/albumsPhoto/';

        // Vérifier et créer le dossier si nécessaire
        if (!is_dir($uploadPath) && !mkdir($uploadPath, 0755, true) && !is_dir($uploadPath)) {
            return redirect()->back()->withInput()->with('error', 'Impossible de créer le dossier de stockage.');
        }

        // Générer un nom de fichier unique
        $newFileName = $photo->getRandomName();

        // Déplacer le fichier
        if (!$photo->move($uploadPath, $newFileName)) {
            return redirect()->back()->withInput()->with('error', 'Erreur lors du téléchargement de la photo.');
        }

        // Ajouter le chemin de la photo aux données de l'album
        $albumPhotoData['photo'] = 'uploads/albumsPhoto/' . $newFileName;

        // Insérer l'album photo dans la base de données avec gestion des erreurs
        if (!$this->albumsPhoto->insert($albumPhotoData)) {
            return redirect()->back()->withInput()->with('error', "Erreur lors de l'ajout de l'album photo en base.");
        }

        // Succès
        return redirect()->route('albumsPhoto')->with('success', "L'album photo a été ajouté avec succès.");
    }




    public function updateAlbumsPhoto()
    {
        // Récupérer les données envoyées
        $albumPhotoData = $this->request->getPost();

        // Vérifier si l'ID de l'album est bien fourni
        $idAlbums = $this->request->getPost('idAlbums');
        if (!$idAlbums) {
            return redirect()->back()->with('error', 'ID d\'album manquant.');
        }

        // Vérifier si l'album existe
        $album = $this->albumsPhoto->find($idAlbums);
        if (!$album) {
            return redirect()->back()->with('error', 'Album introuvable.');
        }

        // Appliquer les règles de validation
        if (!$this->validation->run($albumPhotoData, 'album_photo_rules')) {
            return redirect()->back()->withInput()->with('validation', $this->validation->getErrors());
        }

        // Récupérer le fichier photo
        $photo = $this->request->getFile('photo');

        if ($photo && $photo->isValid() && !$photo->hasMoved()) {
            // Vérifier l'extension du fichier
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
            if (!in_array($photo->getExtension(), $allowedExtensions)) {
                return redirect()->back()->withInput()->with('error', 'Format de fichier non autorisé.');
            }

            // Définir le chemin de destination
            $uploadPath = FCPATH . 'uploads/albumsPhoto/';

            // Vérifier et créer le dossier si nécessaire
            if (!is_dir($uploadPath) && !mkdir($uploadPath, 0755, true) && !is_dir($uploadPath)) {
                return redirect()->back()->withInput()->with('error', 'Impossible de créer le dossier de stockage.');
            }

            // Générer un nom de fichier unique
            $newFileName = $photo->getRandomName();

            // Déplacer le fichier
            if (!$photo->move($uploadPath, $newFileName)) {
                return redirect()->back()->withInput()->with('error', 'Erreur lors du téléchargement de la photo.');
            }

            // Supprimer l'ancienne photo si elle existe
            if (!empty($album['photo']) && file_exists(FCPATH . $album['photo'])) {
                unlink(FCPATH . $album['photo']);
            }

            // Ajouter le nouveau chemin de la photo aux données de l'album
            $albumPhotoData['photo'] = 'uploads/albumsPhoto/' . $newFileName;
        }

        // Mettre à jour l'album photo dans la base de données
        if (!$this->albumsPhoto->update($idAlbums, $albumPhotoData)) {
            return redirect()->back()->with('error', "Erreur lors de la mise à jour de l'album photo.");
        }

        // Succès
        return redirect()->route('albumsPhoto')->with('success', "L'album photo a été modifié avec succès.");
    }


    public function albumsPhotoDelete()
    {
        // Récupérer l'instance de la base de données
        $db = \Config\Database::connect();
        $db->transBegin(); // Démarrer une transaction

        try {
            // Récupérer l'ID de l'album photo à supprimer
            $idAlbums = $this->request->getPost('idAlbums');

            // Vérification de l'ID
            if (empty($idAlbums) || !ctype_digit($idAlbums)) {
                throw new \Exception("ID d'album invalide.");
            }

            // Récupérer l'album
            $album = $this->albumsPhoto->find($idAlbums);
            if (!$album) {
                throw new \Exception("L'album photo n'existe pas.");
            }

            // Récupérer les photos associées
            $photos = $this->photoModel->where('idAlbums', $idAlbums)->findAll();

            // Supprimer les fichiers des photos associées
            foreach ($photos as $photo) {
                $photoPath = FCPATH . $photo['photo'];
                if (!empty($photo['photo']) && file_exists($photoPath) && is_writable($photoPath)) {
                    unlink($photoPath);
                }
            }

            // Supprimer les photos associées en base de données
            $this->photoModel->where('idAlbums', $idAlbums)->delete();

            // Supprimer la photo de l'album
            $albumPhotoPath = FCPATH . $album['photo'];
            if (!empty($album['photo']) && file_exists($albumPhotoPath) && is_writable($albumPhotoPath)) {
                unlink($albumPhotoPath);
            }

            // Supprimer l'album
            $this->albumsPhoto->delete($idAlbums);

            // Commit de la transaction
            $db->transCommit();

            return redirect()->route('albumsPhoto')->with('success', "L'album photo a été supprimé avec succès.");
        } catch (\Exception $e) {
            $db->transRollback(); // Annulation de la transaction en cas d'erreur
            return redirect()->route('albumsPhoto')->with('error', "Erreur : " . $e->getMessage());
        }
    }



    public function photo($idAlbums)
    {
        // Vérifier si l'ID est valide
        if (!$idAlbums || !is_numeric($idAlbums)) {
            return redirect()->back()->with('error', 'Album invalide.');
        }

        // Récupérer l'album
        $album = $this->albumsPhoto->find($idAlbums);
        if (!$album) {
            return redirect()->back()->with('error', 'Album introuvable.');
        }

        // Récupérer les photos de l'album
        $photos = $this->photoModel->findPhotobyAlbumsPhotoId($idAlbums) ?? [];

        return view('photo', [
            'photos' => $photos,
            'idAlbums' => $idAlbums,
            'album' => $album
        ]);
    }

    public function createPhoto()
    {
        $idAlbums = $this->request->getPost('idAlbums');
        if (!$idAlbums) {
            return redirect()->back()->with('error', 'L\'album est introuvable.');
        }

        $files = $this->request->getFiles();
        if (!isset($files['photo']) || empty($files['photo'])) {
            return redirect()->back()->with('error', 'Aucune photo sélectionnée.');
        }

        $uploadPath = FCPATH . 'uploads/photos/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        // Définition des types et extensions autorisés
        $allowedMimeTypes = [
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/svg+xml',
            'image/bmp',
            'image/webp',
            'image/tiff',
            'image/x-icon'
        ];
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'bmp', 'webp', 'tif', 'tiff', 'ico'];
        $maxFileSize = 10 * 1024 * 1024; // 10 Mo en octets

        foreach ($files['photo'] as $photo) {
            if (!$photo->isValid() || $photo->hasMoved()) {
                return redirect()->back()->with('error', "Le fichier {$photo->getClientName()} est invalide ou déjà déplacé.");
            }

            // Vérification de la taille
            if ($photo->getSize() > $maxFileSize) {
                return redirect()->back()->with('error', "Le fichier {$photo->getClientName()} dépasse la taille maximale autorisée (10 Mo).");
            }

            // Vérification du type MIME
            if (!in_array($photo->getMimeType(), $allowedMimeTypes)) {
                return redirect()->back()->with('error', "Le fichier {$photo->getClientName()} n'est pas un format d'image valide.");
            }

            // Vérification de l'extension
            $ext = strtolower($photo->getExtension());
            if (!in_array($ext, $allowedExtensions)) {
                return redirect()->back()->with('error', "Le fichier {$photo->getClientName()} a une extension non autorisée.");
            }

            // ⚠️ Sécurisation des SVG
            if ($ext === 'svg') {
                $fileContent = file_get_contents($photo->getTempName());
                if (preg_match('/<script|onload|onclick|onerror|onmouseover/i', $fileContent)) {
                    return redirect()->back()->with('error', "Le fichier {$photo->getClientName()} contient du code potentiellement dangereux.");
                }
            }

            // Renommage du fichier avec un nom unique
            $newFileName = $photo->getRandomName();
            if (!$photo->move($uploadPath, $newFileName)) {
                log_message('error', "Erreur lors du déplacement du fichier {$photo->getClientName()}.");
                return redirect()->back()->with('error', "Erreur lors du téléchargement de {$photo->getClientName()}.");
            }

            // Insérer dans la base de données
            $data = [
                'photo' => 'uploads/photos/' . $newFileName,
                'idAlbums' => $idAlbums,
            ];
            if (!$this->photoModel->insert($data)) {
                log_message('error', "Erreur lors de l'insertion en base de {$photo->getClientName()}.");
                return redirect()->back()->with('error', "Erreur lors de l'insertion de {$photo->getClientName()} en base.");
            }
        }

        return redirect()->to("/albums-photo/{$idAlbums}")
            ->with('success', 'Photo(s) ajoutée(s) avec succès.');
    }



    public function photoDelete()
    {
        $idPhoto = $this->request->getPost('idPhoto');

        // Vérifier si l'ID de la photo est valide
        if (!$idPhoto || !is_numeric($idPhoto)) {
            return redirect()->back()->with('error', 'ID de la photo invalide.');
        }

        // Récupérer la photo depuis la base de données
        $photo = $this->photoModel->find($idPhoto);

        // Vérifier si la photo existe bien en base
        if (!$photo) {
            return redirect()->back()->with('error', 'Photo introuvable.');
        }

        $filePath = FCPATH . $photo['photo'];

        // Vérifier que le fichier existe bien avant de le supprimer
        if (!empty($photo['photo']) && file_exists($filePath)) {
            // Protection pour éviter de supprimer des fichiers système par erreur
            if (strpos(realpath($filePath), realpath(FCPATH . 'uploads/photos/')) !== 0) {
                return redirect()->back()->with('error', 'Tentative de suppression non autorisée.');
            }

            // Tentative de suppression
            if (!unlink($filePath)) {
                return redirect()->back()->with('error', 'Erreur lors de la suppression du fichier.');
            }
        }

        // Supprimer l'entrée de la base de données
        if (!$this->photoModel->delete($idPhoto)) {
            return redirect()->back()->with('error', 'Erreur lors de la suppression en base de données.');
        }

        return redirect()->to("/albums-photo/{$photo['idAlbums']}")->with('success', 'Photo supprimée avec succès.');
    }

}
