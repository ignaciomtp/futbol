<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class LanguageController extends Controller
{
    public function changeLocale(Request $request)
    {
        // Validar el locale (opcional)
        if (in_array($request->locale, config('languages'))) {
            App::setLocale($request->locale);
        }

        return response()->json(['message' => 'locale changed']);
    }
}
