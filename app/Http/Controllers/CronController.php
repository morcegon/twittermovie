<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\TraktController as Trakt;
use App\Http\Controllers\TmdbController as Tmdb;
use App\Http\Controllers\TwitterController as Twitter;

class CronController extends Controller
{
    public function index()
    {
        $this->trakt   = new Trakt;
        $this->tmdb    = new Tmdb;
        $this->twitter = new Twitter;

        if ($movie = $this->trakt->lastWatchedMovie()) {

            $movie_id = $movie->movie->ids->tmdb;

            if ($movie_banner = $this->tmdb->getBanner($movie_id)) {

                $this->twitter->changeBanner($movie_banner);
            }
        }
    }
}
