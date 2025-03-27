<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class LanguageController extends Controller
{
    public function changeLocale($locale)
    {
        // Validar el locale (opcional)
        if (in_array($locale, config('languages'))) {
            App::setLocale($locale);
        }


        // Redirigir o devolver una respuesta
        return view('welcome');
    }
}
