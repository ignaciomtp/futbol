<?php

use Illuminate\Support\Str;

if (! function_exists('returnNewClub')) {
    function returnNewClub($clubName) {
    	$slugName = Str::slug($clubName);
    	$html = '<input type="checkbox" class="form-check-input" name="clubs" id="'. $slugName .'"><label class="form-check-label" for="exampleCheck1">'.$clubName.'</label>';

    	return $html;
    }

}