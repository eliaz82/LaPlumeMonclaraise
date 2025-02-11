<?php

namespace App\Models;

use CodeIgniter\Model;

class Photo extends Model
{
    protected $table = 'photo';
    protected $primaryKey = 'idPhoto';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['idPhoto', 'idAlbums', 'photo', 'idPhotoFacebook'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    public function findPhotobyAlbumsPhotoId($idAlbums)
    {
        // Vérifier que l'ID de l'album est bien un nombre entier
        if (!is_numeric($idAlbums) || $idAlbums <= 0) {
            return []; // Retourne un tableau vide si l'ID est invalide
        }

        return $this->select('idPhoto, idAlbums, photo')
            ->where('idAlbums', (int) $idAlbums) // On force en entier pour éviter les injections SQL
            ->findAll();
    }

}
