<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Title;
use App\Models\Player;

class TitleController extends Controller
{
    /**
     * Add new Title
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function addTitle(Request $request, $idPlayer) {

        $player = Player::findOrFail($idPlayer);

        $this->validate($request, [
            'name' => 'required|max:80',
        ]);   

        $title = new Title;

        $title->name = $request->name;

        $title->save();

        $player->titles()->attach($title->id, ['number' => $request->number]);

        return returnNewTitle($title->name, $title->id, $request->number);
    }


    /**
     * Update a player's titles
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function updatePlayersTitles(Request $request, $idPlayer) {

        $player = Player::findOrFail($idPlayer);

        $player->titles()->detach();

        foreach($request->titles as $title) {
            $player->titles()->attach($title['id'], ['number' => $title['number']]);

        }

        return 'Títulos actualizados';

    }



}
