<?php

namespace App\Controllers;

class Actualite extends BaseController
{
    public function actualite(): string
    {
        return view('faitMarquant');
    }
}
