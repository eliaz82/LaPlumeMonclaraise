<?php

namespace App\Controllers;

class Association extends BaseController
{
    public function histoire(): string
    {
        return view('histoire');
    }
    public function contact(): string
    {
        return view('contact');
    }
    public function fichierInscription(): string
    {
        return view('inscription');
    }
}
