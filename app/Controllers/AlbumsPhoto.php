<?php

namespace App\Controllers;

class AlbumsPhoto extends BaseController
{
    private $albumsPhoto;
    private $photoModel;

    public function __construct()
    {
        $this->albumsPhoto = model('AlbumsPhoto');
        $this->photoModel = model('Photo');
    }

    public function albumsPhoto(): string
    {
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

        if (!empty($album['photo']) && file_exists(FCPATH . $album['photo'])) {
            unlink(FCPATH . $album['photo']);
        }

        $this->albumsPhoto->delete($idAlbums);
        return redirect()->route('albumsPhoto')->with('success', "L'album photo a été supprimé avec succès.");
    }

       // Affichage des photos dans un album
       public function photo($idAlbums)
       {
           // Récupère les photos de l'album en utilisant la méthode du modèle
           $photos = $this->photoModel->findPhotobyAlbumsPhotoId($idAlbums);
       
           // Récupère les informations de l'album
           $album = $this->albumsPhoto->find($idAlbums); // Assure-toi que tu as un modèle `Albums` ou `AlbumsPhoto` pour récupérer les infos de l'album
       
           // Si aucune photo n'est trouvée, on renvoie un tableau vide
           if (empty($photos)) {
               $photos = [];
           }
       
           // Passe les photos et l'album à la vue
           return view('photo', ['photos' => $photos, 'idAlbums' => $idAlbums, 'album' => $album]);
       }
    public function createPhoto()
    {
        // Récupère les données du formulaire
        $idAlbums = $this->request->getPost('idAlbums');
        $photoData = $this->request->getPost();
        $photo = $this->request->getFile('photo');

        if ($photo && $photo->isValid()) {
            $filePath = FCPATH . 'uploads/albumsPhoto/';
            $photo->move($filePath);
            $photoData['photo'] = 'uploads/albumsPhoto/' . $photo->getName();
            $photoData['idAlbums'] = $idAlbums; // Associe l'album à la photo
        }

        // Insère la photo dans la base de données
        $this->photoModel->insert($photoData);

        // Redirige vers la page des photos de l'album avec un message de succès
        return redirect()->to('/albums-photo/' . $idAlbums)->with('success', 'Photo ajoutée avec succès.');
    }

    public function photoDelete()
    {
        $idPhoto = $this->request->getPost('idPhoto');
        // Récupère la photo à supprimer
        $photo = $this->photoModel->find($idPhoto);

        // Supprime le fichier photo du serveur
        if (!empty($photo['photo']) && file_exists(FCPATH . $photo['photo'])) {
            unlink(FCPATH . $photo['photo']);
        }

        // Supprime la photo de la base de données
        $this->photoModel->delete($idPhoto);

        // Redirige vers la page des photos de l'album
        return redirect()->to('/albums-photo/' . $photo['idAlbums'])->with('success', 'Photo supprimée avec succès.');
    }
}
