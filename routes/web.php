<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NoteController; 
use App\Http\Controllers\BoardController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;

// --- RUTES PÚBLIQUES ---
Route::get('/', function () {
    return view('welcome');
});

// --- RUTES D'AUTENTICACIÓ ---
// Aquí van les teves rutes d'autenticació, login, registre, etc.
// Route::get('/login', ...);
// Route::post('/login', ...);
// ...

// --- RUTES PROTEGIDES (usuari autenticat) ---
Route::middleware(['auth'])->group(function () {

    // Taulers
    Route::resource('boards', BoardController::class);

    // 🔥 DRAG & DROP — primer, per evitar conflicte amb resource()
    // Fem servir PATCH perquè el JS fa fetch amb method: 'PATCH'
    Route::patch('/boards/{board}/notes/{note}/move', [NoteController::class, 'move'])
        ->name('boards.notes.move');

    // Notes (CRUD complet, nested resource)
    Route::resource('boards.notes', NoteController::class);
});
