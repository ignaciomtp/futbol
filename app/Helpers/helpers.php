<?php

use Illuminate\Support\Str;

if (! function_exists('returnNewClub')) {
    function returnNewClub($clubName) {
    	$slugName = Str::slug($clubName);
    	$html = '<li><input type="checkbox" class="form-check-input mr-4 ml-4" name="clubs[]" id="'. $slugName .'" checked><label class="form-check-label mr-4 ml-4" for="exampleCheck1">'.$clubName.'</label></li>';

    	return $html;
    }

}