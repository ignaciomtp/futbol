<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Player;

use DateTime;
use stdClass;

class PublicController extends Controller
{
    /**
     * Show the application Home page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
    
        return view('welcome');
    }


    /**
     * Check the guess
     *
     */
    public function checkGuess($idGuess) {

        $guessResult = new stdClass();

        $guessResult->match = false;

        $offsetFromDate = new DateTime('2025-03-20');
        $now = new DateTime();
        $interval = $now->diff($offsetFromDate);
        $dayOffset = $interval->days + 1;

        $targetPlayer = Player::find($dayOffset);        

        if($targetPlayer->id == $idGuess) {
            $guessResult->match = true;
            return $guessResult;
        }

        $guessPlayer = Player::find($idGuess);  

        if($targetPlayer->country == $guessPlayer->country) {
            $guessResult->country = 'right';
        } else {
            $guessResult->country = 'wrong';
        }

        if($targetPlayer->position == $guessPlayer->position) {
            $guessResult->position = 'right';
        } else {
            $guessResult->position = 'wrong';
        }

        if(($guessPlayer->debut_season >= $targetPlayer->debut_season && $guessPlayer->debut_season <= $targetPlayer->last_season) || ($targetPlayer->debut_season >= $guessPlayer->debut_season && $targetPlayer->debut_season <= $guessPlayer->last_season)) {
            $guessResult->active = 'right';
        } else {
            $guessResult->active = 'wrong';
        }


        $guessPlayerClubs = $guessPlayer->clubs();
        $targetPlayerClubs = $targetPlayer->clubs();

        $commonClubs = $guessPlayerClubs->pluck('id')->intersect($targetPlayerClubs->pluck('id'));

        if ($commonClubs->isNotEmpty()) {
            $guessResult->clubs = 'right';
        } else {
            $guessResult->clubs = 'wrong';
        }

        $guessPlayerTitles = $guessPlayer->titles();
        $targetPlayerTitles = $targetPlayer->titles();

        $commonTitles = $guessPlayerTitles->pluck('id')->intersect($targetPlayerTitles->pluck('id'));

        if ($commonTitles->isNotEmpty()) {
            $guessResult->titles = 'right';
        } else {
            $guessResult->titles = 'wrong';
        }

        $guessResult->targetName = $targetPlayer->name;
        $guessResult->guessName = $guessPlayer->name;

        return $guessResult;

    }
}
