<?php

use Illuminate\Support\Str;

use Illuminate\Support\Facades\Storage;

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

if (! function_exists('returnCountryFlag')) {
    function returnCountryFlag($country) {
/*
        // Leer el archivo JSON desde storage/app/data
        $jsonContent = Storage::get('public/codes.json');

        // Convertir el JSON a un array asociativo
        $paises = json_decode($jsonContent, true);  */

        // Cargar los países desde el caché o el archivo JSON
        $paises = Cache::remember('paises', 60 * 60 * 24, function () {
            $jsonContent = Storage::get('public/codes.json');
            return json_decode($jsonContent, true);
        });

        // Buscar el código correspondiente al nombre del país
        $codigo = array_search($country, $paises);

        return $codigo ? $codigo : 'error';

    }
}
