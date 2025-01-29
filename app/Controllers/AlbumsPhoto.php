<?php

namespace App\Controllers;

class AlbumsPhoto extends BaseController
{
    private $albumsPhoto;

    public function __construct()
    {
        $this->albumsPhoto = model('AlbumsPhoto');
    }
    public function AlbumsPhoto(): string
    {
        $albumsPhotos = $this->albumsPhoto->findAll();
        return view('albumsPhoto', ['albumsPhotos' => $albumsPhotos]);
    }

    public function createAlbumsPhoto()
    {
        $albumPhoto = $this->request->getPost();
        $dateAlbums = $this->request->getPost('dateAlbums');
        $photo = $this->request->getFile('photo');

        $filePath = FCPATH . 'uploads/albumsPhoto/';
        $photo->move($filePath);
        $photoUrl = 'uploads/albumsPhoto/' . $photo->getName();

        $this->albumsPhoto->insert($albumPhoto);

        $this->albumsPhoto->update($dateAlbums, ['photo' => $photoUrl]);
        return redirect()->route('albumsPhoto')->with('success', 'L\'album photo a été ajouté avec succès.');
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
    public function AlbumsPhotoDelete()
    {
        $idAlbums = $this->request->getPost('idAlbums');
        $album = $this->albumsPhoto->find($idAlbums);
        if (!empty($album['photo']) && file_exists(FCPATH . $album['photo'])) {
            unlink(FCPATH . $album['photo']);
        }
        $this->albumsPhoto->delete($idAlbums);
        return redirect()->route('albumsPhoto')->with('success', "L'album photo a été supprimé avec succès.");
    }


    public function photo($dateAlbums)
    {
        return view('photo');
    }
}
