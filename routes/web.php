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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/player', [App\Http\Controllers\HomeController::class, 'playerForm'])->name('player');
Route::post('/player', [App\Http\Controllers\HomeController::class, 'addPlayer'])->name('newplayer');
Route::get('/player/edit/{id}', [App\Http\Controllers\HomeController::class, 'playerEditForm'])->name('editplayer');
Route::post('/player/edit', [App\Http\Controllers\HomeController::class, 'playerUpdate'])->name('updateplayer');

Route::post('/player/club/{idPlayer}', [App\Http\Controllers\ClubController::class, 'addClub'])->name('newclub');
Route::post('/player/title/{idPlayer}', [App\Http\Controllers\TitleController::class, 'addTitle'])->name('newtitle');
Route::post('/player/titlesupdate/{idPlayer}', [App\Http\Controllers\TitleController::class, 'updatePlayersTitles'])->name('updatetitles');



