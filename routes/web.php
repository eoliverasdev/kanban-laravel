<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NoteController; 
use App\Http\Controllers\BoardController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;

// --- RUTES PÚBLIQUES ---
Route::get('/', function () {
    // 💡 SOLUCIÓ: Comprovem si l'usuari està autenticat
    if (Auth::check()) {
        // Si l'usuari està loguejat, el portem a la seva llista de taulers
        return redirect()->route('boards.index');
    }
    
    // Si l'usuari NO està loguejat, el redirigim a la pàgina de login
    return redirect()->route('login');
});

// --- RUTES D'AUTENTICACIÓ ---
// És CRUCIAL que aquesta ruta tingui el ->name('login')
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Opcional: Registre
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

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