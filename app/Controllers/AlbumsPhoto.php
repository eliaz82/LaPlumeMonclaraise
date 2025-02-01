<?php

namespace App\Controllers;
use App\Libraries\CallApi;
class AlbumsPhoto extends BaseController
{
    private $albumsPhoto;
    private $photoModel;
    private $associationModel;
    private $facebookModel;
    private $callApi;

    public function __construct()
    {
        $this->albumsPhoto = model('AlbumsPhoto');
        $this->photoModel = model('Photo');
        $this->associationModel = model('Association');
        $this->facebookModel = model('Facebook');
        $this->callApi = new CallApi();
    }

    public function albumsPhoto(): string
    {
        $albumsPhotos = $this->albumsPhoto->findAll();

        // Ne pas créer un nouvel album si un album existe déjà
        $tokenFacebook = $this->associationModel->find(1);
        // Appels à l'API pour récupérer les posts et les images
        //$posts = $this->callApi->callApi("https://graph.facebook.com/me/feed?fields=id,message,created_time,permalink_url,attachments&access_token={$tokenFacebook['tokenFacebook']}");
        $jsonFile = file_get_contents(base_url('/posts.json'));
        $posts = json_decode($jsonFile, true);  // Décoder en tableau associatif

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

        foreach ($filteredPosts as $post) {
            // Extraire la date de publication
            $dateAlbums = date('Y-m-d', strtotime($post['created_time']));

            // Vérifier s'il y a des pièces jointes (images)
            if (isset($post['attachments']['data'][0]['media']['image']['src'])) {
                // Récupérer la première image
                $photo = $post['attachments']['data'][0]['media']['image']['src'];

                // Vérifier si un album avec la même date existe déjà
                $existingAlbum = $this->albumsPhoto->where('dateAlbums', $dateAlbums)
                    ->first();

                // Si l'album n'existe pas déjà, on crée un nouvel album
                if (!$existingAlbum) {
                    // Créer un nouvel album photo
                    $this->albumsPhoto->save([
                        'dateAlbums' => $dateAlbums,
                        'nom' => null, // Pas de nom pour l'album
                        'photo' => $photo, // Première photo du post
                    ]);

                    // Récupérer l'ID de l'album nouvellement créé
                    $idAlbums = $this->albumsPhoto->getInsertID();
                } else {
                    // Si l'album existe déjà, on récupère l'ID
                    $idAlbums = $existingAlbum['idAlbums'];
                }

                // Initialiser un tableau pour stocker les URLs des photos à ajouter
                $photosFacebook = [];

                // Vérifier les pièces jointes principales (attachments)
                if (isset($post['attachments']['data'][0]['media']['image']['src'])) {
                    $imageSrc = $post['attachments']['data'][0]['media']['image']['src'];
                    if (!in_array($imageSrc, $photosFacebook)) {
                        $photosFacebook[] = $imageSrc;
                    }
                }

                // Vérifier les subattachments (sous-pièces jointes)
                if (isset($post['attachments']['data'][0]['subattachments']['data'])) {
                    foreach ($post['attachments']['data'][0]['subattachments']['data'] as $subattachment) {
                        if (isset($subattachment['media']['image']['src'])) {
                            $imageSrc = $subattachment['media']['image']['src'];
                            if (!in_array($imageSrc, $photosFacebook)) {
                                $photosFacebook[] = $imageSrc;
                            }
                        }
                    }
                }

                foreach ($photosFacebook as $photoUrl) {
                    // Vérifier si l'image existe déjà dans la base de données
                    $imageExists = $this->photoModel->where('photo', $photoUrl)->first();
                    if (!$imageExists) {
                        // Enregistrer l'image si elle n'existe pas déjà
                        $this->photoModel->save([
                            'idAlbums' => $idAlbums,
                            'photo' => $photoUrl,
                        ]);
                    }
                }




            }

        }
        $albumsPhotos = $this->albumsPhoto->findAll();
        return view('albumsPhoto', ['albumsPhotos' => $albumsPhotos]);
    }

    public function createAlbumsPhoto()
    {
        $albumPhotoData = $this->request->getPost();
        $photo = $this->request->getFile('photo');

        if ($photo && $photo->isValid()) {
            $filePath = FCPATH . 'uploads/albumsPhoto/';
            $photo->move($filePath);
            $albumPhotoData['photo'] = 'uploads/albumsPhoto/' . $photo->getName();
        }

        $this->albumsPhoto->insert($albumPhotoData);
        return redirect()->route('albumsPhoto')->with('success', "L'album photo a été ajouté avec succès.");
    }

    public function updateAlbumsPhoto()
    {
        $idAlbums = $this->request->getPost('idAlbums');
        $data = $this->request->getPost();

        $album = $this->albumsPhoto->find($idAlbums);
        $photo = $this->request->getFile('photo');

        if ($photo && $photo->isValid()) {
            $filePath = FCPATH . 'uploads/albumsPhoto/';
            $photo->move($filePath);
            $photoUrl = 'uploads/albumsPhoto/' . $photo->getName();

            if (!empty($album['photo']) && file_exists(FCPATH . $album['photo'])) {
                unlink(FCPATH . $album['photo']);
            }
            $data['photo'] = $photoUrl;
        }

        $this->albumsPhoto->update($idAlbums, $data);
        return redirect()->route('albumsPhoto')->with('success', "L'album photo a été modifié avec succès.");
    }

    public function albumsPhotoDelete()
    {
        $idAlbums = $this->request->getPost('idAlbums');
        $album = $this->albumsPhoto->find($idAlbums);

        $photos = $this->photoModel->where('idAlbums', $idAlbums)->findAll();
        foreach ($photos as $photo) {
            if (!empty($photo['photo']) && file_exists(FCPATH . $photo['photo']) && is_writable(FCPATH . $photo['photo'])) {
                unlink(FCPATH . $photo['photo']);
            }
        }
        $this->photoModel->where('idAlbums', $idAlbums)->delete();

        if (!empty($album['photo']) && file_exists(FCPATH . $album['photo'])) {
            unlink(FCPATH . $album['photo']);
        }

        $this->albumsPhoto->delete($idAlbums);
        return redirect()->route('albumsPhoto')->with('success', "L'album photo a été supprimé avec succès.");
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
        $photoData = $this->request->getPost();
        $photo = $this->request->getFile('photo');

        if ($photo && $photo->isValid()) {
            $filePath = FCPATH . 'uploads/photos/';
            $photo->move($filePath);
            $photoData['photo'] = 'uploads/photos/' . $photo->getName();
            $photoData['idAlbums'] = $idAlbums;
        }

        $this->photoModel->insert($photoData);

        return redirect()->to("/albums-photo/{$idAlbums}")->with('success', 'Photo ajoutée avec succès.');
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
