<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Curl\Curl;

class TmdbController extends Controller
{
    private $api_key;
    private $base_url;
    private $image_base_url;

    public function __construct()
    {
        $this->api_key        = env('TMDB_API_ID');
        $this->base_url       = "https://api.themoviedb.org/3";
        $this->image_base_url = "https://image.tmdb.org/t/p/w1280";

        $this->curl = new Curl;
    }

    /**
     * retrieve the movie banner
     * @return [type] [description]
     */
    public function getBanner($movie_id)
    {

        if ($movie = $this->movie($movie_id)) {

            $img_url = $this->image_base_url.$movie->backdrop_path;
            $type    = pathinfo($img_url, PATHINFO_EXTENSION);
            $data    = file_get_contents($img_url);
            $base64  = base64_encode($data);

            return $base64;
        }

        return false;
    }

    public function movie($movie_id)
    {
        if ($movie_id) {

            $url = "{$this->base_url}/movie/{$movie_id}?api_key={$this->api_key}";
            $this->curl->get($url);

            if (!$this->curl->error) {
                return $this->curl->response;
            }
        }

        return false;
    }
}
