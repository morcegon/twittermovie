<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Abraham\TwitterOAuth\TwitterOAuth;
use App\Config;

class TwitterController extends Controller
{
    private $consumer_key;
    private $consumer_secret;
    private $connection;

    function __construct()
    {
        $this->consumer_key    = env('TWITTER_CONSUMER_KEY');
        $this->consumer_secret = env('TWITTER_CONSUMER_SECRET');
    }

    public function index()
    {
        if (request('oauth_token') AND request('oauth_token') == session()->get('oauth_token')) {
            $this->accessToken(request('oauth_verifier'));
        }else{
            echo $this->auth();
        }
    }

    public function accessToken($oauth_verifier)
    {
        try {
            $this->connection = new TwitterOAuth($this->consumer_key, $this->consumer_secret, session()->get('oauth_token'), session()->get('oauth_token_secret'));
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        try {
            $access_token = $this->connection->oauth("oauth/access_token", ["oauth_verifier" => $oauth_verifier]);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        Config::create([
            'oauth_token'        => $access_token['oauth_token'],
            'oauth_token_secret' => $access_token['oauth_token_secret']
        ]);
    }

    public function requestToken()
    {
        $this->connection = new TwitterOAuth($this->consumer_key, $this->consumer_secret);

        try{
            $request_token = $this->connection->oauth('oauth/request_token', ['oauth_callback' => url('twitter/')]);
        }
        catch(\Exception $e){
            throw new \Exception($e->getMessage());
        }

        session()->put('oauth_token', $request_token['oauth_token']);
        session()->put('oauth_token_secret', $request_token['oauth_token_secret']);

        return true;
    }

    public function auth()
    {
        try {
            if ($config = Config::all()->last()) {
                $this->connection = new TwitterOAuth($this->consumer_key, $this->consumer_secret, $config->oauth_token, $config->oauth_token_secret);
            }
        } catch (\Exception $e) {
            try {
                $this->newAuth();
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
        }
    }

    public function newAuth()
    {
        try {
            $this->requestToken();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        if ($url = $this->connection->url("oauth/authorize", ["oauth_token" => session()->get('oauth_token')])) {
            return redirect($url)->content();
        }
    }

    public function changeBanner($banner)
    {
        try {
            $this->auth();
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

        $data = ['banner' => $banner];

        try {
            $status = $this->connection->post("account/update_profile_banner", $data);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

        echo "Alterado com sucesso!";
    }
}
