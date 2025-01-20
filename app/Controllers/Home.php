<?php

namespace App\Controllers;

class Home extends BaseController
{
    private $associationModel;

    public function __construct()
    {
        $this->associationModel = model('Association');
    }
    public function index(): string
    {
        $logo = $this->associationModel->find(1);
        return view('accueil', ['logo' => $logo]);
    }
}
