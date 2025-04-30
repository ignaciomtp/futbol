<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

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
    public function index($lang = 'en')
    {
        $languages = config('languages');
        $lang = in_array($lang, $languages) ? $lang : 'en';

        Session::put('locale', $lang);
        App::setLocale($lang);

        $homeTexts = config('hometexts');

        return view('welcome2', [
            'lang' => $lang,
            'homeTexts' => $homeTexts[$lang]
        ]);
    }


    /**
     * Show the game page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function play()
    {
    
        $footble = getFootbleNumber();

        $player = Player::findOrFail($footble);

        return Inertia::render('Index', ['footble' => $footble, 'player' => $player]);
    }  

    public function index2()
    {
    
        return view('welcome');
        
    }

    /**
     * Show the application Rewind page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function rewind() {
        $footble = getFootbleNumber();
        return Inertia::render('Rewind', ['footble' => $footble]);
    }

    /**
     * Show the application Create page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create() {
        return Inertia::render('Create');
    }

    /**
     * Show the privacy policy page
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function privacy() {
        return Inertia::render('Privacy');
    }

    /*
    *
    * Show custom Footble
    */
    public function custom($idPlayer, $message = null) {
        $decodedIdPlayer = base64_decode($idPlayer);
        $decodedMessage = $message ? base64_decode($message) : '';
        $message = urldecode($decodedMessage);

        $player = Player::find($decodedIdPlayer);

        if($player) {
            return Inertia::render('Custom', ['player' => $player, 'footble' => $player->id, 'message' => $message]);
        }

        return Inertia::render('Error');
    }

    /**
     * Check the guess
     *
     */
    public function checkGuess($idGuess, $idPlayer = null) {

        $guessResult = new stdClass();

        $guessResult->match = false;

        $dayOffset = $idPlayer ? $idPlayer : getFootbleNumber();

        $targetPlayer = Player::find($dayOffset);        

        if($targetPlayer->id == $idGuess) {
            $guessResult->match = true;
            return $guessResult;
        }

        $guessPlayer = Player::find($idGuess);  

        $guessResult->countryguess = $guessPlayer->country;
        $guessResult->countrytarget = $targetPlayer->country;

        if($targetPlayer->country == $guessPlayer->country) {
            $guessResult->country = 'right';
        } else {

            $targetCountryRegion = checkContinent($targetPlayer->country);
            $guessCountryRegion = checkContinent($guessPlayer->country);

            if ($targetCountryRegion === $guessCountryRegion) {                
                $guessResult->country = 'partial';

            } else {
                $guessResult->country = 'wrong';
            }        
        }

        if($targetPlayer->position == $guessPlayer->position) {
            $guessResult->position = 'right';
        } else {
            $guessResult->position = 'wrong';
        }

        $targetFirstSeason = $targetPlayer->debut_season;
        $targetLastSeason = $targetPlayer->last_season ? $targetPlayer->last_season : date('Y');

        $guessFirstSeason = $guessPlayer->debut_season;
        $guessLastSeason = $guessPlayer->last_season ? $guessPlayer->last_season : date('Y');

        if(($guessFirstSeason >= $targetFirstSeason && $guessFirstSeason <= $targetLastSeason) || ($guessFirstSeason < $targetFirstSeason && $guessLastSeason >= $targetFirstSeason)) {


            if($guessFirstSeason < $targetFirstSeason && $guessLastSeason >= $targetFirstSeason) {
                $coincidence = $guessLastSeason - $targetFirstSeason;
            }

            if($guessFirstSeason >= $targetFirstSeason && $guessFirstSeason <= $targetLastSeason) {
                $coincidence = $targetLastSeason - $guessFirstSeason;
            }


            if($coincidence >= 10) {
                $guessResult->active = 'right';
            } else {
                $guessResult->active = 'partial';
            }

            
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
            if($commonTitles->count() == 1) {
                $guessResult->titles = 'partial';
            } else {
                $guessResult->titles = 'right';
            }
            
        } else {
            $guessResult->titles = 'wrong';
        }

        $guessResult->targetName = $targetPlayer->name;
        $guessResult->guessName = $guessPlayer->name;

        return $guessResult;

    }
}
