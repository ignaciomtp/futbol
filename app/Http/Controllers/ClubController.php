<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Club;

class ClubController extends Controller
{
    /**
     * Add new club
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function addClub(Request $request) {
        $this->validate($request, [
            'name' => 'required|max:80',
            'badge'=> 'image|mimes:jpeg,webp,png,jpg,gif,svg|max:2048',
        ]);   


        $club = new Club;
        $club->fill($request->all());

        if($request->hasFile('badge')){
            $image = $request->file('badge');
            $nameImage = $image->getClientOriginalName();
            $image->move(public_path('img/clubs'), $nameImage);
            $club->badge = $nameImage;
        } 

        $club->save();

        return returnNewClub($club->name);
    }


    public function clubbb() {
        return '<input type="checkbox" class="form-check-input" name="clubs" id="fc-barcelona"><label class="form-check-label" for="exampleCheck1">F.C. Barcelona</label>';
    }



}
