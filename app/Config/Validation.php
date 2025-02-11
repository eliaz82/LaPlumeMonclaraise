<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Validation\StrictRules\CreditCardRules;
use CodeIgniter\Validation\StrictRules\FileRules;
use CodeIgniter\Validation\StrictRules\FormatRules;
use CodeIgniter\Validation\StrictRules\Rules;

class Validation extends BaseConfig
{
    // --------------------------------------------------------------------
    // Setup
    // --------------------------------------------------------------------

    /**
     * Stores the classes that contain the
     * rules that are available.
     *
     * @var list<string>
     */
    public array $ruleSets = [
        Rules::class,
        FormatRules::class,
        FileRules::class,
        CreditCardRules::class,
    ];

    /**
     * Specifies the views that are used to display the
     * errors.
     *
     * @var array<string, string>
     */
    public array $templates = [
        'list' => 'CodeIgniter\Validation\Views\list',
        'single' => 'CodeIgniter\Validation\Views\single',
    ];

    // --------------------------------------------------------------------
    // Rules
    // --------------------------------------------------------------------
    public $album_photo_rules = [
        'dateAlbums' => 'valid_date[Y-m-d]',
        'nom' => 'required|min_length[3]|max_length[128]',
        'photo' => 'uploaded[photo]|is_image[photo]|max_size[photo,10240]',
    ];
    public $evenements_rules = [
        'titre' => 'required|string|min_length[3]|max_length[255]',
        'message' => 'required|string|min_length[5]',
        'date' => 'required|valid_date[Y-m-d]',
        'image' => 'permit_empty|uploaded[image]|is_image[image]|max_size[image,2048]|mime_in[image,image/jpeg,image/png,image/gif]'
    ];

}
