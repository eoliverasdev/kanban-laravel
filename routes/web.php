<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NoteController; 
use App\Http\Controllers\BoardController;
use App\Http\Controllers\AuthController; // <-- NOU: Importació correcta del teu controlador
use Illuminate\Support\Facades\Auth;    // <-- CORRECTE: Importació de la Façana Auth de Laravel

// --- RUTES PÚBLIQUES ---

// Ruta de benvinguda
Route::get('/', function () {
    return view('welcome');
});

// --- RUTES D'AUTENTICACIÓ ---

// 1. LOGIN: Mostra el formulari i processa la sessió
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login'); // <-- Ara AuthController::class funciona
Route::post('/login', [AuthController::class, 'login']);

// 2. LOGOUT: Tanca la sessió
Route::post('/logout', function () {
    Auth::logout();
    // Tanca la sessió i redirigeix a la pàgina principal.
    return redirect('/');

})->name('logout');
// REGISTRE: Mostra el formulari i processa el nou usuari
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register'); // <-- Ara AuthController::class funciona
Route::post('/register', [AuthController::class, 'register']);


// --- GRUP DE RUTES PROTEGIDES ---
// Totes les rutes d'aquí dins requereixen que l'usuari estigui autenticat ('middleware auth').
Route::middleware(['auth'])->group(function () {
    
    // Rutes de Taulers (boards.index, boards.create, etc.)
    Route::resource('boards', BoardController::class);

    // Rutes Anidades per a les Notes (boards.notes.index, etc.)
    Route::resource('boards.notes', NoteController::class);
});