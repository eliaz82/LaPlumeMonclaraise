<?php

namespace App\Controllers;
use App\Libraries\FacebookCache;
class Calendrier extends BaseController
{
    private $facebookModel;
    private $facebookCache;
    public function __construct()
    {
        $this->facebookModel = model('Facebook');
        $this->facebookCache = new FacebookCache();
    }
    public function calendrier(): string
    {
        $posts = $this->facebookCache->getFacebookPosts();

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
