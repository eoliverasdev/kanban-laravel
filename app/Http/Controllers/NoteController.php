<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\Note;
use App\Models\User; // 👈 necessari per als responsables
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
     * (Serveix per LLEGIR: index, etc.)
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
     * Comprova que l’usuari pot GESTIONAR (crear/editar/esborrar/moure) les notes del tauler.
     * Aquí fem la distinció viewer/admin.
     */
    private function ensureCanManage(Board $board)
    {
        // Primer, comprovem propietat / accés al tauler
        $authCheck = $this->checkBoardOwnership($board);
        if ($authCheck) return $authCheck;

        $user = Auth::user();
        $isApi = request()->ajax() || request()->wantsJson();

        // Si no hi ha usuari o el role no és 'admin', bloquegem
        if (!$user || $user->role !== 'admin') {
            if ($isApi) {
                return response()->json(
                    ['error' => 'No tens permisos per modificar aquest tauler.'],
                    403
                );
            }
            abort(403, 'No tens permisos per modificar aquest tauler.');
        }

        return null;
    }

    /**
     * Llista de notes en format Kanban (3 columnes).
     * Viewer i Admin poden veure.
     */
    public function index(Board $board)
    {
        $authCheck = $this->checkBoardOwnership($board);
        if ($authCheck) return $authCheck;

        // Agrupem totes les notes del tauler per estat + responsable
        $grouped = $board->notes()
            ->with('responsible') // 👈 carreguem usuari responsable
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

        // Tots els usuaris disponibles com a responsables
        $users = User::orderBy('name')->get();

        return view('notes.index', [
            'board'   => $board,
            'columns' => $columns,
            'users'   => $users, // 👈 perquè el Blade pugui mostrar el <select>
        ]);
    }

    /**
     * Formulari de creació.
     * Només admins.
     */
    public function create(Board $board)
    {
        $authCheck = $this->ensureCanManage($board);
        if ($authCheck) return $authCheck;

        // Etiquetes d’estat per al select del formulari
        $note_statuses = self::STATUS_LABELS;

        // Usuaris per seleccionar responsable
        $users = User::orderBy('name')->get();

        return view('notes.create', compact('board', 'note_statuses', 'users'));
    }

    /**
     * Desa una nova nota.
     * Només admins.
     */
    public function store(Request $request, Board $board)
    {
        $authCheck = $this->ensureCanManage($board);
        if ($authCheck) return $authCheck;

        $validated = $request->validate([
            'title'          => 'required|string|max:150',
            'description'    => 'nullable|string|max:500',
            'status'         => ['required', 'string', 'in:' . implode(',', self::ALLOWED_STATUSES)],
            'responsible_id' => ['nullable', 'integer', 'exists:users,id'], // 👈 nou
            'priority'      => ['required', 'string', 'in:baix,intermig,alt'], // 👈 nou
        ]);

        $note = $board->notes()->create($validated)->fresh('responsible');

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'message' => 'Nota creada correctament.',
                'note'    => [
                    'id'           => $note->id,
                    'title'        => $note->title,
                    'description'  => $note->description,
                    'status'       => $note->status,
                    'status_label' => $this->statusLabel($note->status),
                    'responsible'  => $note->responsible
                        ? [
                            'id'   => $note->responsible->id,
                            'name' => $note->responsible->name,
                          ]
                        : null,
                ],
            ], 201);
        }

        return redirect()
            ->route('boards.notes.index', $board)
            ->with('success', 'Nota creada correctament.');
    }

    /**
     * Formulari d’edició.
     * Només admins.
     */
    public function edit(Board $board, Note $note)
    {
        $authCheck = $this->ensureCanManage($board);
        if ($authCheck) return $authCheck;

        if ($note->board_id !== $board->id) {
            abort(404);
        }

        $note_statuses = self::STATUS_LABELS;
        $users = User::orderBy('name')->get();

        return view('notes.edit', compact('board', 'note', 'note_statuses', 'users'));
    }

    /**
     * Actualitza una nota (form o AJAX inline).
     * Només admins.
     */
    public function update(Request $request, Board $board, Note $note)
    {
        $authCheck = $this->ensureCanManage($board);
        if ($authCheck) return $authCheck;

        if ($note->board_id !== $board->id) {
            abort(404);
        }

        $validated = $request->validate([
            'title'          => 'required|string|max:150',
            'description'    => 'nullable|string|max:500',
            // status opcional, però si ve ha de ser un dels 3
            'status'         => ['nullable', 'string', 'in:' . implode(',', self::ALLOWED_STATUSES)],
            'responsible_id' => ['nullable', 'integer', 'exists:users,id'],
        ]);

        // Si no ens passa status, no el toquem
        if (array_key_exists('status', $validated) && $validated['status'] === null) {
            unset($validated['status']);
        }

        $note->update($validated);
        $fresh = $note->fresh('responsible');

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'message' => 'Nota actualitzada correctament.',
                'note'    => [
                    'id'           => $fresh->id,
                    'title'        => $fresh->title,
                    'description'  => $fresh->description,
                    'status'       => $fresh->status,
                    'status_label' => $this->statusLabel($fresh->status),
                    'responsible'  => $fresh->responsible
                        ? [
                            'id'   => $fresh->responsible->id,
                            'name' => $fresh->responsible->name,
                          ]
                        : null,
                        'priority'     => $fresh->priority,
                ],
            ], 200);
        }

        return redirect()
            ->route('boards.notes.index', $board)
            ->with('success', 'Nota actualitzada correctament.');
    }

    /**
     * Elimina una nota.
     * Només admins.
     */
    public function destroy(Board $board, Note $note)
    {
        $authCheck = $this->ensureCanManage($board);
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
     * Només admins (viewer només pot mirar).
     */
    public function move(Request $request, Board $board, Note $note)
    {
        $authCheck = $this->ensureCanManage($board);
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
