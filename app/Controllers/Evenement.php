<?php

namespace App\Controllers;

class Evenement extends BaseController
{
    public function evenement(): string
    {
        return view('evenements');
    }
}
