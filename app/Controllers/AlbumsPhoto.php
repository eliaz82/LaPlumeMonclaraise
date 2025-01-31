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
