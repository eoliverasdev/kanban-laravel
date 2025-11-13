<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NoteController; 

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\BoardController;

// La ruta original: Route::resource('boards', BoardController::class)->middleware('auth');
// La nueva ruta SIN autenticación:
Route::resource('boards', BoardController::class);


Route::resource('boards', BoardController::class); 

// Rutes anidades per a les notes:
// La sintaxi 'boards.notes' crea rutes com /boards/{board}/notes
Route::resource('boards.notes', NoteController::class);