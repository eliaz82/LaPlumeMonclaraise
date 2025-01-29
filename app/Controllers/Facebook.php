<?php

namespace App\Controllers;

use App\Libraries\CallApi;

class Facebook extends BaseController
{
    private $associationModel;
    private $facebookModel;
    private $callApi;

    public function __construct()
    {
        $this->associationModel = model('Association');
        $this->facebookModel = model('Facebook');
        $this->callApi = new CallApi();
    }

    public function login()
    {
        $clientId = '603470049247384';
        $redirectUri = base_url();
        $scope = 'public_profile,user_posts';
        $url = "https://www.facebook.com/v21.0/dialog/oauth?"
            . "client_id={$clientId}&redirect_uri={$redirectUri}&scope={$scope}&response_type=code";
        return redirect()->to($url);
    }

}
