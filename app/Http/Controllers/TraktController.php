<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Curl\Curl;

class TraktController extends Controller
{
    protected $client_id     = env('TRAKT_CLIENT_ID');
    protected $client_secret = env('TRAKT_SECRET');
    protected $trakt_code    = env('TRAKT_CLIENT_CODE');
    protected $trakt_url     = "https://api.trakt.tv";
    protected $trakt_user    = env('TRAKT_USER');

    function __construct()
    {
        $this->curl = new Curl;
        $this->curl->setHeader('Content-Type', 'application/json');
        $this->curl->setHeader('trakt-api-version', '2');
        $this->curl->setHeader('trakt-api-key', $this->client_id);
    }

    public function index()
    {
        //
    }

    /**
     * get the last movie watched
     * @return array collection about the movie
     */
    public function lastWatchedMovie()
    {
        $request_url = "{$this->trakt_url}/users/{$this->trakt_user}/history/movies";
        $request = $this->curl->get($request_url);

        if (!$this->curl->error) {
            $movie = $this->curl->response[0];
            return $movie;
        }

        return false;
    }
}
