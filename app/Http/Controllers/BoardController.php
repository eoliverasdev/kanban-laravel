<?php

namespace App\Http\Controllers;

use App\Models\Board; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- NECESSARI per a l'autenticació

class BoardController extends Controller
{
    /**
     * Display a listing of the resource.
     * Muestra una lista de los taulers del usuario autenticado.
     */
    public function index()
    {
        // IMPORTANT: Ara recuperem NOMÉS els taulers de l'usuari autenticat
        // Assumim que la relació 'boards' existeix al model User
        $boards = Auth::user()->boards; 

        // Aquí es retorna la vista (template) 'resources/views/boards/index.blade.php'
        return view('boards.index', compact('boards'));
    }

    /**
     * Show the form for creating a new resource.
     * Muestra el formulario para crear un nuevo tablero.
     */
    public function create()
    {
        // Retorna la vista que conté el formulari de creació
        return view('boards.create');
    }

    /**
     * Store a newly created resource in storage.
     * Almacena un nuevo tablero en la base de datos, assignant l'usuari actual com a propietari.
     */
    public function store(Request $request)
    {
        // 1. Validació
        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'description' => 'nullable|string|max:500',
        ]);

        // 2. Creació del Tauler
        $board = Board::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            // FIX CRÍTIC: Assignem l'ID de l'usuari autenticat com a propietari
            'owner_id' => Auth::id(), 
        ]);

        // 3. Redirecció a la pàgina d'índex amb un missatge de confirmació en català.
        return redirect()->route('boards.index')
                         ->with('success', "Tauler '{$board->title}' creat correctament!");
    }

    /**
     * Show the form for editing the specified resource.
     * Inclou comprovació de propietat.
     */
    public function edit(Board $board) 
    {
        // COMPROVACIÓ DE SEGURETAT
        if ($board->owner_id !== Auth::id()) {
            abort(403, 'Accés denegat. Aquest tauler no et pertany.');
        }
        return view('boards.edit', compact('board'));
    }

    /**
     * Update the specified resource in storage.
     * Inclou comprovació de propietat.
     */
    public function update(Request $request, Board $board) 
    {
        // COMPROVACIÓ DE SEGURETAT
        if ($board->owner_id !== Auth::id()) {
            abort(403, 'Accés denegat. Aquest tauler no et pertany.');
        }
        
        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'description' => 'nullable|string|max:500',
        ]);

        $board->update($validated);

        return redirect()->route('boards.index')
                         ->with('success', "Tauler '{$board->title}' actualitzat correctament!");
    }

    /**
     * Remove the specified resource from storage.
     * Inclou comprovació de propietat.
     */
    public function destroy(Board $board) 
    {
        // COMPROVACIÓ DE SEGURETAT
        if ($board->owner_id !== Auth::id()) {
            abort(403, 'Accés denegat. Aquest tauler no et pertany.');
        }

        $title = $board->title;
        
        $board->delete();

        return redirect()->route('boards.index')
                         ->with('success', "Tauler '{$title}' eliminat correctament.");
    }
}