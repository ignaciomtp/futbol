<?php

use Illuminate\Support\Str;

use Illuminate\Support\Facades\Storage;

if (! function_exists('returnNewClub')) {
    function returnNewClub($clubName, $clubId) {
    	$slugName = Str::slug($clubName);
    	$html = '<li><input type="checkbox" class="form-check-input mr-4 ml-4" name="clubs[]" id="'. $slugName .'" value="'.$clubId.'" checked><label class="form-check-label mr-4 ml-4" for="'. $slugName .'">'.$clubName.'</label></li>';

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

if (! function_exists('returnCountryData')) {
    function returnCountryData($country) {
        // Construir la URL de la API con el nombre del país
        $url = "https://restcountries.com/v3.1/name/" . urlencode($country);
        
        // Inicializar cURL
        $ch = curl_init();
        
        // Configurar las opciones de cURL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        
        // Ejecutar la solicitud
        $response = curl_exec($ch);
        
        // Verificar si hubo errores
        if (curl_errno($ch)) {
            curl_close($ch);
            return false; // O podrías devolver un mensaje de error específico
        }
        
        // Obtener el código de estado HTTP
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        // Verificar si la solicitud fue exitosa
        if ($httpCode == 200) {
            // Decodificar la respuesta JSON a un array PHP
            $data = json_decode($response, true);
            return $data;
        } else {
            return false; // O podrías manejar diferentes códigos de error
        }
    }
}

if (! function_exists('checkContinent')) {
    function checkContinent($country) {

        $europe = [
            "Albania", 
            "Andorra", 
            "Armenia", 
            "Austria", 
            "Azerbaijan", 
            "Belarus", 
            "Belgium", 
            "Bosnia and Herzegovina", 
            "Bulgaria", 
            "Croatia", 
            "Cyprus", 
            "Czechia", 
            "Denmark", 
            "England", 
            "Estonia", 
            "Faroe Islands", 
            "Finland", 
            "France", 
            "Georgia", 
            "Germany", 
            "Gibraltar", 
            "Greece", 
            "Hungary", 
            "Iceland", 
            "Israel", 
            "Italy", 
            "Kazakhstan", 
            "Kosovo", 
            "Latvia", 
            "Liechtenstein", 
            "Lithuania", 
            "Luxembourg", 
            "Malta", 
            "Moldova", 
            "Monaco", 
            "Montenegro", 
            "Netherlands", 
            "North Macedonia", 
            "Northern Ireland", 
            "Norway", 
            "Poland", 
            "Portugal",
            "Republic of Ireland", 
            "Romania", 
            "Russia", 
            "San Marino", 
            "Scotland", 
            "Serbia", 
            "Slovakia", 
            "Slovenia", 
            "Spain", 
            "Sweden", 
            "Switzerland", 
            "Turkey", 
            "Ukraine", 
            "Wales"
        ];

        if(in_array($country, $europe)) return 'Europe';

        $southAmerica = [
            "Argentina", 
            "Bolivia", 
            "Brazil", 
            "Chile", 
            "Colombia", 
            "Ecuador", 
            "Paraguay", 
            "Peru", 
            "Uruguay", 
            "Venezuela"
        ];

        if(in_array($country, $southAmerica)) return 'South America';

        $africa = [
            "Algeria", 
            "Angola", 
            "Benin", 
            "Botswana", 
            "Burkina Faso", 
            "Burundi", 
            "Cape Verde", 
            "Cameroon", 
            "Central African Republic", 
            "Chad", 
            "Comoros", 
            "Congo", 
            "Democratic Republic of the Congo", 
            "Djibouti", 
            "Egypt", 
            "Equatorial Guinea", 
            "Eritrea", "Eswatini", 
            "Ethiopia", "Gabon", 
            "Gambia", 
            "Ghana", 
            "Guinea", 
            "Guinea-Bissau", 
            "Ivory Coast", 
            "Kenya", 
            "Lesotho", 
            "Liberia", 
            "Libya", 
            "Madagascar", 
            "Malawi", 
            "Mali", 
            "Mauritania", 
            "Mauritius", 
            "Morocco", 
            "Mozambique", 
            "Namibia", 
            "Niger", 
            "Nigeria", 
            "Rwanda", 
            "Sao Tome and Principe", 
            "Senegal", 
            "Seychelles", 
            "Sierra Leone", 
            "Somalia", 
            "South Africa", 
            "South Sudan", 
            "Sudan", 
            "Tanzania", 
            "Togo", 
            "Tunisia", 
            "Uganda", 
            "Zambia", 
            "Zimbabwe"
        ];

        if(in_array($country, $africa)) return 'Africa';

        $northAmerica = [
            "Antigua and Barbuda", 
            "Bahamas", 
            "Barbados", 
            "Belize", 
            "Canada", 
            "Costa Rica", 
            "Cuba", 
            "Dominica", 
            "Dominican Republic", 
            "El Salvador", 
            "Grenada", 
            "Guatemala", 
            "Haiti", 
            "Honduras", 
            "Jamaica", 
            "Mexico", 
            "Nicaragua", 
            "Panama", 
            "Saint Kitts and Nevis", 
            "Saint Lucia", 
            "Saint Vincent and the Grenadines", 
            "Trinidad and Tobago", 
            "United States"
        ];

        if(in_array($country, $northAmerica)) return 'North America';

        $asia = [
            "South Korea",
            "Japan",
            "China"

        ];

        if(in_array($country, $asia)) return 'Asia';

        return false;


    }

}

if (! function_exists('getFootbleNumber')) {
    function getFootbleNumber() {
        $offsetFromDate = new DateTime(config('startdate'));
        $now = new DateTime();
        $interval = $now->diff($offsetFromDate);
        return $interval->days + 1;
    }
}