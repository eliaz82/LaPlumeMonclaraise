<?php

namespace App\Controllers;

class Calendrier extends BaseController
{
    public function calendrier(): string
    {
        return view('calendrier');
    }
}
