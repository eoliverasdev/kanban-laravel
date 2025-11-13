<?php

namespace App\Http\Controllers;

use App\Models\Board; 
use Illuminate\Http\Request;

class BoardController extends Controller
{
    /**
     * Display a listing of the resource.
     * Muestra una lista de todos los taulers disponibles.
     */
    public function index()
    {
        // Obtenir TOTS els taulers, sense filtre d'usuari
        $boards = Board::all(); 

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
     * Almacena un nuevo tablero en la base de datos.
     */
    public function store(Request $request)
    {
        // 1. Validació: Assegura que el títol és present i que les dades tenen la mida correcta.
        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'description' => 'nullable|string|max:500',
        ]);

        // 2. Creació del Tauler, assignant TEMPORALMENT l'owner_id = 1
        // AQUEST ÉS EL FIX: La base de dades requereix owner_id.
        $board = Board::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'owner_id' => 1, 
        ]);

        // 3. Redirecció a la pàgina d'índex amb un missatge de confirmació en català.
        return redirect()->route('boards.index')
                         ->with('success', "Tauler '{$board->title}' creat correctament!");
    }

    /**
     * Show the form for editing the specified resource.
     * Muestra el formulario para editar un tablero existente.
     */
    public function edit(Board $board)
    {
        // El tauler ja ha estat trobat per Laravel ($board)
        return view('boards.edit', compact('board'));
    }

    /**
     * Update the specified resource in storage.
     * Actualiza un tablero específico a la base de dades.
     */
    public function update(Request $request, Board $board)
    {
        // 1. Validació
        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'description' => 'nullable|string|max:500',
        ]);

        // 2. Actualització del Tauler
        $board->update($validated);

        // 3. Redirecció a la pàgina d'índex amb un missatge de confirmació
        return redirect()->route('boards.index')
                         ->with('success', "Tauler '{$board->title}' actualitzat correctament!");
    }

    /**
     * Remove the specified resource from storage.
     * Elimina un tauler de la base de dades.
     */
    public function destroy(Board $board)
    {
        $title = $board->title;
        
        // 1. Eliminació del Tauler
        $board->delete();

        // 2. Redirecció a la pàgina d'índex amb un missatge de confirmació
        return redirect()->route('boards.index')
                         ->with('success', "Tauler '{$title}' eliminat correctament.");
    }
}