<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    // ✅ Estats disponibles al Kanban (ELS MATEIXOS QUE A LA BD)
    private const ALLOWED_STATUSES = ['pending', 'in_progress', 'done'];

    private const STATUS_LABELS = [
        'pending'     => 'Per fer',
        'in_progress' => 'En curs',
        'done'        => 'Fet',
    ];

    private const STATUS_DOT_COLORS = [
        'pending'     => 'bg-gray-400',
        'in_progress' => 'bg-blue-400',
        'done'        => 'bg-green-500',
    ];

    private function statusLabel(string $status): string
    {
        return self::STATUS_LABELS[$status] ?? strtoupper($status);
    }

    private function statusDotColor(string $status): string
    {
        return self::STATUS_DOT_COLORS[$status] ?? 'bg-gray-400';
    }

    /**
     * Comprova que el tauler és de l’usuari autenticat.
     */
    private function checkBoardOwnership(Board $board)
    {
        $isApi = request()->ajax() || request()->wantsJson();

        if (!Auth::check()) {
            if ($isApi) {
                return response()->json(['error' => 'Sessió no iniciada.'], 403);
            }
            return redirect()->route('login')->with('error', 'Inicia sessió per continuar.');
        }

        if ($board->owner_id !== Auth::id()) {
            if ($isApi) {
                return response()->json(['error' => 'Accés denegat a aquest tauler.'], 403);
            }
            abort(403, 'Accés denegat a aquest tauler.');
        }

        return null;
    }

    /**
     * Llista de notes en format Kanban (3 columnes).
     */
    public function index(Board $board)
    {
        $authCheck = $this->checkBoardOwnership($board);
        if ($authCheck) return $authCheck;

        // Agrupem totes les notes del tauler per estat
        $grouped = $board->notes()
            ->orderBy('created_at', 'asc')
            ->get()
            ->groupBy('status');

        // Preparem les columnes amb etiqueta, color i col·lecció de notes
        $columns = [];
        foreach (self::ALLOWED_STATUSES as $status) {
            $columns[$status] = [
                'key'        => $status,
                'label'      => $this->statusLabel($status),
                'dot_color'  => $this->statusDotColor($status),
                'notes'      => $grouped->get($status) ?? collect(),
            ];
        }

        return view('notes.index', [
            'board'   => $board,
            'columns' => $columns,
        ]);
    }

    /**
     * Formulari de creació.
     */
    public function create(Board $board)
    {
        $authCheck = $this->checkBoardOwnership($board);
        if ($authCheck) return $authCheck;

        // Etiquetes d’estat per al select del formulari
        $note_statuses = self::STATUS_LABELS;

        return view('notes.create', compact('board', 'note_statuses'));
    }

    /**
     * Desa una nova nota.
     */
    public function store(Request $request, Board $board)
    {
        $authCheck = $this->checkBoardOwnership($board);
        if ($authCheck) return $authCheck;

        $validated = $request->validate([
            'title'       => 'required|string|max:150',
            'description' => 'nullable|string|max:500',
            'status'      => ['required', 'string', 'in:' . implode(',', self::ALLOWED_STATUSES)],
        ]);

        $note = $board->notes()->create($validated)->fresh();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'message' => 'Nota creada correctament.',
                'note'    => [
                    'id'           => $note->id,
                    'title'        => $note->title,
                    'description'  => $note->description,
                    'status'       => $note->status,
                    'status_label' => $this->statusLabel($note->status),
                ],
            ], 201);
        }

        return redirect()
            ->route('boards.notes.index', $board)
            ->with('success', 'Nota creada correctament.');
    }

    /**
     * Formulari d’edició.
     */
    public function edit(Board $board, Note $note)
    {
        $authCheck = $this->checkBoardOwnership($board);
        if ($authCheck) return $authCheck;

        if ($note->board_id !== $board->id) {
            abort(404);
        }

        $note_statuses = self::STATUS_LABELS;

        return view('notes.edit', compact('board', 'note', 'note_statuses'));
    }

    /**
     * Actualitza una nota (form o AJAX inline).
     */
    public function update(Request $request, Board $board, Note $note)
    {
        $authCheck = $this->checkBoardOwnership($board);
        if ($authCheck) return $authCheck;

        if ($note->board_id !== $board->id) {
            abort(404);
        }

        $validated = $request->validate([
            'title'       => 'required|string|max:150',
            'description' => 'nullable|string|max:500',
            // status opcional, però si ve ha de ser un dels 3
            'status'      => ['nullable', 'string', 'in:' . implode(',', self::ALLOWED_STATUSES)],
        ]);

        // Si no ens passa status, no el toquem
        if (array_key_exists('status', $validated) && $validated['status'] === null) {
            unset($validated['status']);
        }

        $note->update($validated);
        $fresh = $note->fresh();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'message' => 'Nota actualitzada correctament.',
                'note'    => [
                    'id'           => $fresh->id,
                    'title'        => $fresh->title,
                    'description'  => $fresh->description,
                    'status'       => $fresh->status,
                    'status_label' => $this->statusLabel($fresh->status),
                ],
            ], 200);
        }

        return redirect()
            ->route('boards.notes.index', $board)
            ->with('success', 'Nota actualitzada correctament.');
    }

    /**
     * Elimina una nota.
     */
    public function destroy(Board $board, Note $note)
    {
        $authCheck = $this->checkBoardOwnership($board);
        if ($authCheck) return $authCheck;

        if ($note->board_id !== $board->id) {
            abort(404);
        }

        $note->delete();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'message' => 'Nota eliminada correctament.',
            ], 200);
        }

        return redirect()
            ->route('boards.notes.index', $board)
            ->with('success', 'Nota eliminada correctament.');
    }

    /**
     * Mou nota entre columnes (Drag & Drop).
     * Espera AJAX (PATCH/POST) amb 'status' => pending|in_progress|done
     */
    public function move(Request $request, Board $board, Note $note)
    {
        $authCheck = $this->checkBoardOwnership($board);
        if ($authCheck) return $authCheck;

        if ($note->board_id !== $board->id) {
            return response()->json(['error' => 'Nota no trobada en aquest tauler.'], 404);
        }

        $validated = $request->validate([
            'status' => ['required', 'string', 'in:' . implode(',', self::ALLOWED_STATUSES)],
        ]);

        $note->status = $validated['status'];
        $note->save();

        $fresh = $note->fresh();

        return response()->json([
            'message' => 'Nota moguda correctament.',
            'note'    => [
                'id'           => $fresh->id,
                'title'        => $fresh->title,
                'description'  => $fresh->description,
                'status'       => $fresh->status,
                'status_label' => $this->statusLabel($fresh->status),
            ],
        ], 200);
    }
}
