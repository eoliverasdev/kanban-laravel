<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 

// Utilitzem la referència completa (Controller) per garantir l'herència.
class NoteController extends \App\Http\Controllers\Controller
{
    private const ALLOWED_STATUSES = ['pending', 'in_progress', 'done'];

    // S'HA ELIMINAT EL CONSTRUCTOR PER EVITAR L'ERROR DE MIDDLEWARE

    /**
     * FUNCIÓ INTERNA PER COMPROVAR LA PROPIETAT DEL TAULER
     */
    private function checkBoardOwnership(Board $board)
    {
        // 1. Assegurem-nos d'estar autenticats
        if (!Auth::check()) {
            // Si no està autenticat, redirigim a l'índex de taulers
            return redirect()->route('boards.index')->with('error', 'Inicia sessió per continuar.');
        }

        // 2. Si l'usuari actual no és el propietari, denega l'accés
        if ($board->owner_id !== Auth::id()) {
            abort(403, 'Accés denegat. Aquest tauler no et pertany.');
        }
        
        return null; // Comprovació correcta
    }

    /**
     * Mostra totes les notes per a un tauler específic. (La vista de 3 columnes)
     */
    public function index(Board $board) 
    {
        // 1. COMPROVACIÓ DE SEGURETAT
        $authCheck = $this->checkBoardOwnership($board);
        if ($authCheck) {
            return $authCheck; 
        }
        
        // Lògica de la vista Kanban (sense canvis)
        $notesByStatus = $board->notes()
             ->orderByRaw("FIELD(status, 'pending', 'in_progress', 'done')") 
             ->orderBy('position', 'asc')
             ->get()
             ->groupBy('status');

        $statuses = self::ALLOWED_STATUSES;
        $groupedNotes = collect($statuses)->mapWithKeys(function ($status) use ($notesByStatus) {
            return [$status => $notesByStatus->get($status, collect())];
        });

        return view('notes.index', compact('board', 'groupedNotes', 'statuses'));
    }

    /**
     * Mostra el formulari de creació de nota.
     */
    public function create(Board $board)
    {
        $authCheck = $this->checkBoardOwnership($board);
        if ($authCheck) return $authCheck;

        $statuses = self::ALLOWED_STATUSES;
        return view('notes.create', compact('board', 'statuses'));
    }

    /**
     * Crea i desa una nova nota.
     */
    public function store(Request $request, Board $board)
    {
        $authCheck = $this->checkBoardOwnership($board);
        if ($authCheck) return $authCheck;
        
        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'description' => 'nullable|string|max:500',
            'status' => ['required', 'string', 'in:' . implode(',', self::ALLOWED_STATUSES)],
        ]);

        $note = $board->notes()->create(array_merge($validated, ['position' => 0]));

        return redirect()->route('boards.notes.index', $board)
                          ->with('success', "Nota '{$note->title}' creada correctament.");
    }

    /**
     * Mètode show redirigit (no s'utilitza a Kanban).
     */
    public function show(Board $board, Note $note)
    {
        // Si per error s'accedeix a la ruta show, el millor és enviar-lo a l'índex de notes (la vista de 3 columnes)
        // en lloc d'eliminar el mètode i trencar les rutes resource.
        return redirect()->route('boards.notes.index', $board); 
    }

    /**
     * Mostra el formulari d'edició.
     */
    public function edit(Board $board, Note $note)
    {
        $authCheck = $this->checkBoardOwnership($board);
        if ($authCheck) return $authCheck;

        if ($note->board_id !== $board->id) abort(404); 
        
        $statuses = self::ALLOWED_STATUSES;
        return view('notes.edit', compact('board', 'note', 'statuses'));
    }

    /**
     * Actualitza una nota existent.
     */
    public function update(Request $request, Board $board, Note $note)
    {
        $authCheck = $this->checkBoardOwnership($board);
        if ($authCheck) return $authCheck;
        
        if ($note->board_id !== $board->id) abort(404); 
        
        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'description' => 'nullable|string|max:500',
            'status' => ['required', 'string', 'in:' . implode(',', self::ALLOWED_STATUSES)],
        ]);

        $note->update($validated);

        return redirect()->route('boards.notes.index', $board)
                          ->with('success', "Nota '{$note->title}' actualitzada correctament.");
    }

    /**
     * Elimina una nota.
     */
    public function destroy(Board $board, Note $note)
    {
        $authCheck = $this->checkBoardOwnership($board);
        if ($authCheck) return $authCheck;
        
        if ($note->board_id !== $board->id) abort(404); 
        
        $title = $note->title;
        $note->delete();

        return redirect()->route('boards.notes.index', $board)
                          ->with('success', "Nota '{$title}' eliminada correctament.");
    }
}