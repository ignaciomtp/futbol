<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Player;

class PlayerController extends Controller
{
    /**
     * Search the player.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function searchPlayer(Request $request) {

        $playerName = $request->name;

        $players = Player::where('name', 'LIKE', '%' . $playerName . '%')->limit(5)->with('clubs', 'titles')->get();

        foreach($players as $player) {
            $flag = returnCountryFlag($player->country);
            $player->country_flag = 'https://flagcdn.com/w40/'.$flag.'.png';
        }

        return $players;

    }
}
