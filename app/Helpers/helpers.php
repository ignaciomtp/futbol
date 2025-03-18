<?php

use Illuminate\Support\Str;

if (! function_exists('returnNewClub')) {
    function returnNewClub($clubName) {
    	$slugName = Str::slug($clubName);
    	$html = '<li><input type="checkbox" class="form-check-input mr-4 ml-4" name="clubs[]" id="'. $slugName .'" checked><label class="form-check-label mr-4 ml-4" for="'. $slugName .'">'.$clubName.'</label></li>';

    	return $html;
    }

}

if (! function_exists('returnNewTitle')) {
    function returnNewTitle($titleName, $titleId, $numTitles) {
        $slugName = Str::slug($titleName);
        $html = '<li><label class="form-check-label mr-4 ml-4 smallfont" for="'. $slugName .'">'.$titleName.'</label><input type="number" class="numtitles" id="num-titles-'. $slugName .'" data-title-id="'.$titleId.'" value="'.$numTitles.'"></li>';

        return $html;
    }

}