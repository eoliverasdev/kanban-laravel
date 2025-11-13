<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    // Definim els estats permesos per reutilitzar-los a la validació
    private const ALLOWED_STATUSES = ['pending', 'in_progress', 'done'];

    /**
     * Display a listing of the resource.
     * Mostra totes les notes per a un tauler específic.
     */
    public function index(Board $board) // Route Model Binding: obtenim el Board
    {
        // Utilitzem la relació 'notes' del model Board per obtenir les notes
        // OPTIONAL: Podries ordenar per 'status' i després per 'position' aquí
        $notes = $board->notes()->get();
        
        // Retorna la vista de llistat de notes
        return view('notes.index', compact('board', 'notes'));
    }

    /**
     * Show the form for creating a new resource.
     * Mostra el formulari de creació de nota per a un tauler específic.
     */
    public function create(Board $board)
    {
        $statuses = self::ALLOWED_STATUSES;
        return view('notes.create', compact('board', 'statuses'));
    }

    /**
     * Store a newly created resource in storage.
     * Crea i desa una nova nota associada al tauler.
     */
    public function store(Request $request, Board $board)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'description' => 'nullable|string|max:500',
            // VALIDACIÓ D'ESTAT: ha de ser un dels valors permesos
            'status' => ['nullable', 'string', 'in:' . implode(',', self::ALLOWED_STATUSES)],
        ]);

        // Si l'estat no es proporciona (p. ex., al formulari de creació), assignem 'pending' per defecte
        if (!isset($validated['status'])) {
            $validated['status'] = 'pending';
        }

        // Creem la nota a través de la relació del tauler
        $note = $board->notes()->create($validated);

        return redirect()->route('boards.notes.index', $board)
                         ->with('success', "Nota '{$note->title}' creada correctament al tauler '{$board->title}'.");
    }

    /**
     * Display the specified resource.
     * Mostra una nota individual. (Ometem per simplicitat)
     */
    public function show(Board $board, Note $note)
    {
        return redirect()->route('boards.notes.index', $board);
    }

    /**
     * Show the form for editing the specified resource.
     * Mostra el formulari d'edició d'una nota.
     */
    public function edit(Board $board, Note $note)
    {
        $statuses = self::ALLOWED_STATUSES;
        return view('notes.edit', compact('board', 'note', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     * Actualitza una nota existent.
     */
    public function update(Request $request, Board $board, Note $note)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'description' => 'nullable|string|max:500',
            // VALIDACIÓ D'ESTAT
            'status' => ['required', 'string', 'in:' . implode(',', self::ALLOWED_STATUSES)],
        ]);

        $note->update($validated);

        return redirect()->route('boards.notes.index', $board)
                         ->with('success', "Nota '{$note->title}' actualitzada correctament.");
    }

    /**
     * Remove the specified resource from storage.
     * Elimina una nota.
     */
    public function destroy(Board $board, Note $note)
    {
        $title = $note->title;
        $note->delete();

        return redirect()->route('boards.notes.index', $board)
                         ->with('success', "Nota '{$title}' eliminada correctament.");
    }
}