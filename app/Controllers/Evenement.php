<?php

namespace App\Controllers;

class Evenement extends BaseController
{
    public function evenement(): string
    {
        $data = [
            'pageName' => 'evenements',  // Assure-toi de définir une valeur pour $pageName ici
        ];
    
        // Charge la vue avec la variable passée
        return view('evenements', $data);
    }

    
}

