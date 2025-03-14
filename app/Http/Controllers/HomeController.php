<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Player;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $players = Player::paginate(12);
        return view('home', compact('players'));
    }


    /**
     * Show the player add/edit form.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function playerForm()
    {
        return view('playerform');
    }

    /**
     * Create new player
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function addPlayer(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:40',
            'first_name' => 'required|max:40',
            'surnames' => 'required|max:100',
            'birth_country' => 'required|max:100',
            'country' => 'required|max:100',

        ]);     

        $player = new Player;

        $player->fill($request->all());

        $player->save();

        return redirect()->route('home');
    }


}
