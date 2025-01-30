<?php

namespace App\Controllers;
use App\Libraries\CallApi;
class Calendrier extends BaseController
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
    public function calendrier(): string
    {
        $tokenFacebook = $this->associationModel->find(1);
        $posts = $this->callApi->callApi("https://graph.facebook.com/me/feed?fields=id,message,created_time,permalink_url&access_token={$tokenFacebook['tokenFacebook']}");

        $hashtags = $this->facebookModel->where('pageName', 'calendrier')->findAll();
        $hashtagList = array_column($hashtags, 'hashtag');

        $filteredPosts = array_filter($posts['data'], function ($post) use ($hashtagList) {
            if (isset($post['message'])) {
                foreach ($hashtagList as $hashtag) {
                    if (strpos($post['message'], $hashtag) !== false) {
                        return true;
                    }
                }
            }
            return false;
        });
        return view('calendrier', ['posts' => $filteredPosts]);
    }
}
