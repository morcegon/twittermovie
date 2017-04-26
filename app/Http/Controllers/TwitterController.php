<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Abraham\TwitterOAuth\TwitterOAuth;
use App\Config;

class TwitterController extends Controller
{
    protected $access_token        = env('TWITTER_ACCESS_TOKEN');
    protected $access_token_secret = env('TWITTER_ACCESS_TOKEN_SECRET');
    protected $consumer_key        = env('TWITTER_CONSMER_KEY');
    protected $consumer_secret     = env('TWITTER_CONSMER_SECRET');

    function __construct()
    {
        $this->conn = new TwitterOAuth($this->consumer_key, $this->consumer_secret, $this->access_token, $this->access_token_secret);
    }

    public function index()
    {

    }

    public function changeBanner($banner)
    {
        $data = [
            'banner' => $banner
        ];

        $status = $this->conn->post("account/update_profile_banner", $data);

        if ($this->conn->getLastHttpCode() == 200) {
            return true;
        }else{
            dd($status);
        }
    }

    public function auth()
    {

        $token = $this->conn->oauth("oauth/request_token");
        $url   = $this->conn->url("oauth/authorize", ["oauth_token" => $token["oauth_token"]]);

        return redirect($url);
    }

    public function saveToken()
    {
        if (request('oauth_token') AND request('oauth_verifier')) {

            Config::create([
                'oauth_token'    => request('oauth_token'),
                'oauth_verifier' => request('oauth_verifier')
            ]);
        }
    }
}
