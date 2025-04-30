<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/




Route::get('/play', [App\Http\Controllers\PublicController::class, 'play'])->name('playgame');
Route::get('/home27887', [App\Http\Controllers\PublicController::class, 'index2'])->name('homeapp2');


Route::get('/rewind', [App\Http\Controllers\PublicController::class, 'rewind'])->name('rewind');
Route::get('/create', [App\Http\Controllers\PublicController::class, 'create'])->name('create');
Route::get('/privacy', [App\Http\Controllers\PublicController::class, 'privacy'])->name('privacy');

Route::get('/custom/{idPlayer}/{message?}', [App\Http\Controllers\PublicController::class, 'custom'])->name('custom');

Route::get('/{lang?}', [App\Http\Controllers\PublicController::class, 'index'])->name('homeapp');

Route::get('/checkresult/{idGuess}/{idPlayer?}', [App\Http\Controllers\PublicController::class, 'checkGuess'])->name('checkresult');

Route::post('/change-locale', [App\Http\Controllers\LanguageController::class, 'changeLocale'])->name('change.locale');

Auth::routes();

Route::prefix('admin')->group(function () {
	Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
	Route::get('/player', [App\Http\Controllers\HomeController::class, 'playerForm'])->name('player');
	Route::post('/player', [App\Http\Controllers\HomeController::class, 'addPlayer'])->name('newplayer');
	Route::get('/player/edit/{id}', [App\Http\Controllers\HomeController::class, 'playerEditForm'])->name('editplayer');
	Route::post('/player/edit', [App\Http\Controllers\HomeController::class, 'playerUpdate'])->name('updateplayer');	
});


Route::post('/player/search', [App\Http\Controllers\PlayerController::class, 'searchPlayer'])->name('searchplayer');
Route::get('/getplayer/{idPlayer}', [App\Http\Controllers\PlayerController::class, 'getPlayerData'])->name('getplayer');

Route::post('/player/club/{idPlayer}', [App\Http\Controllers\ClubController::class, 'addClub'])->name('newclub');
Route::post('/player/title/{idPlayer}', [App\Http\Controllers\TitleController::class, 'addTitle'])->name('newtitle');
Route::post('/player/titlesupdate/{idPlayer}', [App\Http\Controllers\TitleController::class, 'updatePlayersTitles'])->name('updatetitles');



