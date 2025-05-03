<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Player;
use App\Models\Club;
use App\Models\Title;

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
        $clubs = Club::orderBy('name')->get();
        $titles = Title::orderBy('name')->get();

        $positions = [
            'Goalkeeper',
            'Defender',
            'Midfielder',
            'Attacker',
        ];

        return view('playerform', compact('clubs', 'positions', 'titles'));
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
            'first_name' => 'max:40',
            'surnames' => 'max:100',
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

        $player->clubs()->attach($request->clubs);

        return redirect()->route('editplayer', ['id' => $player->id]);
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
        $titles = Title::orderBy('name')->get();

        $playerClubs = [];
        $playerTitles = [];

        foreach($player->clubs as $club) {
            array_push($playerClubs, $club->id);
        }

        foreach($player->titles as $title) {
            $playerTitles[$title->id] = $title->pivot->number; 
        }
        
        $positions = [
            'Goalkeeper',
            'Defender',
            'Midfielder',
            'Attacker',
        ];

        return view('playereditform', compact('player', 'clubs', 'playerClubs', 'positions', 'titles', 'playerTitles'));
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
            'first_name' => 'max:40',
            'surnames' => 'max:100',
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

        $player->clubs()->detach();
        $player->clubs()->attach($request->clubs);

        return redirect()->route('home');
    }


    /**
     * Search players in Admin.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function searchPlayerAdmin(Request $request) {

        $playerName = $request->name;

        $players = Player::where('name', 'LIKE', '%' . $playerName . '%')->limit(5)->with('clubs', 'titles')->get();

        foreach($players as $player) {
            $flag = returnCountryFlag($player->country);
            $player->country_flag = 'https://flagcdn.com/w40/'.$flag.'.png';

            if($player->country != $player->birth_country) {
                $flag = returnCountryFlag($player->birth_country);
            }

            $player->birth_country_flag = 'https://flagcdn.com/w40/'.$flag.'.png';
        }

        return view('search-results', compact('players'));

    }
}
