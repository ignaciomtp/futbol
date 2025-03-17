<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Player;
use App\Models\Club;

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
     * Show the player add form.
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
            'photo'=> 'image|mimes:jpeg,webp,png,jpg,gif,svg|max:2048',
        ]);     

        $player = new Player;

        $player->fill($request->all());

        if($request->hasFile('photo')){
            $image = $request->file('photo');
            $nameImage = $image->getClientOriginalName();
            $image->move(public_path('img/players'), $nameImage);
            $player->photo = $nameImage;
        } 

        $player->save();

        return redirect()->route('home');
    }

    /**
     * Show the player edit form.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function playerEditForm($id)
    {
        $player = Player::find($id);
        $clubs = Club::orderBy('name')->get();
        $playerClubs = [];
        foreach($player->clubs as $club) {
            array_push($playerClubs, $club->id);
        }
        return view('playereditform', compact('player', 'clubs', 'playerClubs'));
    }

    /**
     * Update the player.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function playerUpdate(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:40',
            'first_name' => 'required|max:40',
            'surnames' => 'required|max:100',
            'birth_country' => 'required|max:100',
            'country' => 'required|max:100',
            'photo'=> 'image|mimes:jpeg,webp,png,jpg,gif,svg|max:2048',
        ]);   

        $player = Player::find($request->id);

        $player->fill($request->all());

        if($request->hasFile('photo')){
            $image = $request->file('photo');
            $nameImage = $image->getClientOriginalName();
            $image->move(public_path('img/players'), $nameImage);
            $player->photo = $nameImage;
        } 

        $player->save();

        return redirect()->route('home');
    }

}
