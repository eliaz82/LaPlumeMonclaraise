<?php

namespace App\Controllers;

class Partenaires extends BaseController
{
    private $partenairesModel;

    public function __construct()
    {
        $this->partenairesModel = model('Partenaires');
    }       
    public function partenaires(): string
    {
        return view('partenaires');
    }

    public function partenairesSumbit()
    {
        $partenaire = $this->request->getPost();
        $this->partenairesModel->insert($partenaire);
        return redirect()->to("partenaires");
    }
}
