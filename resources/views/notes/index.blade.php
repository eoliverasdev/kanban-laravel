{{-- resources/views/notes/index.blade.php --}}
@extends('layout')

@section('content')
    <div
        id="kanban-root"
        class="bg-gray-100 min-h-screen"
        data-move-url-template="{{ route('boards.notes.move', ['board' => $board, 'note' => '__NOTE_ID__']) }}"
    >
        <div class="max-w-6xl mx-auto py-8 px-4">
            {{-- Capçalera --}}
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">
                        Tauler: {{ $board->name ?? 'Sense nom' }}
                    </h1>
                    @if(!empty($board->description))
                        <p class="text-gray-600 mt-1 text-sm">
                            {{ $board->description }}
                        </p>
                    @endif
                </div>

                <a
                    href="{{ route('boards.notes.create', $board) }}"
                    class="inline-flex items-center px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded shadow-sm transition"
                >
                    + Nova nota
                </a>
            </div>

            @if (session('success'))
                <div class="mb-4 px-4 py-3 rounded bg-green-100 text-green-800 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 px-4 py-3 rounded bg-red-100 text-red-800 text-sm">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 px-4 py-3 rounded bg-red-100 text-red-800 text-sm">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @php
                // Estils per columna
                $columnStyles = [
                    'pending' => [
                        'header' => 'text-gray-800',
                        'button' => 'bg-gray-500 hover:bg-gray-600',
                        'ring'   => 'focus:ring-gray-300',
                    ],
                    'in_progress' => [
                        'header' => 'text-blue-700',
                        'button' => 'bg-blue-500 hover:bg-blue-600',
                        'ring'   => 'focus:ring-blue-300',
                    ],
                    'done' => [
                        'header' => 'text-green-700',
                        'button' => 'bg-green-500 hover:bg-green-600',
                        'ring'   => 'focus:ring-green-300',
                    ],
                ];
            @endphp

            {{-- GRID KANBAN: 3 columnes --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                @foreach ($columns as $status => $column)
                    @php
                        $style = $columnStyles[$status] ?? $columnStyles['pending'];
                    @endphp

                    <div class="bg-white rounded-lg shadow p-4 flex flex-col">
                        {{-- Títol columna --}}
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center space-x-2">
                                <span class="inline-block w-3 h-3 rounded-full {{ $column['dot_color'] }}"></span>
                                <h2 class="text-xl font-semibold {{ $style['header'] }}">
                                    {{ $column['label'] }}
                                </h2>
                            </div>
                            <span class="text-xs text-gray-500">
                                {{ $column['notes']->count() }} tasques
                            </span>
                        </div>

                        {{-- Formulari crear nota ràpida en aquesta columna --}}
                        <form action="{{ route('boards.notes.store', $board) }}" method="POST" class="mb-4 space-y-2">
                            @csrf
                            <input type="hidden" name="status" value="{{ $status }}">

                            <input
                                type="text"
                                name="title"
                                placeholder="Nova tasca..."
                                class="w-full border border-gray-300 rounded px-2 py-1 text-sm focus:outline-none {{ $style['ring'] }}"
                                required
                            >

                            <textarea
                                name="description"
                                placeholder="Descripció (opcional)"
                                class="w-full border border-gray-300 rounded px-2 py-1 text-sm focus:outline-none {{ $style['ring'] }}"
                                rows="2"
                            ></textarea>

                            <button
                                type="submit"
                                class="w-full {{ $style['button'] }} text-white text-sm font-medium py-1.5 rounded transition"
                            >
                                Afegir
                            </button>
                        </form>

                        {{-- Zona de drop + llista de notes --}}
                        <div
                            class="flex-1 bg-gray-50 rounded-md p-2 space-y-2 min-h-[220px]"
                            data-status="{{ $status }}"
                            ondragover="handleDragOver(event)"
                            ondrop="handleDrop(event)"
                        >
                            @forelse ($column['notes'] as $note)
                                <div
                                    class="bg-white border border-gray-200 rounded p-3 text-sm shadow-sm cursor-move hover:shadow-md transition"
                                    draggable="true"
                                    ondragstart="handleDragStart(event)"
                                    data-note-id="{{ $note->id }}"
                                >
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <div class="font-semibold text-gray-800 break-words">
                                                {{ $note->title }}
                                            </div>
                                            @if($note->description)
                                                <p class="text-gray-600 text-xs mt-1 whitespace-pre-line break-words">
                                                    {{ $note->description }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="mt-3 flex items-center justify-between text-xs text-gray-500">
                                        <span>
                                            Creat: {{ $note->created_at?->format('d/m/Y H:i') }}
                                        </span>
                                        <div class="flex items-center space-x-2">
                                            <a
                                                href="{{ route('boards.notes.edit', [$board, $note]) }}"
                                                class="text-blue-500 hover:underline"
                                            >
                                                Editar
                                            </a>
                                            <form
                                                action="{{ route('boards.notes.destroy', [$board, $note]) }}"
                                                method="POST"
                                                onsubmit="return confirm('Segur que vols eliminar aquesta nota?')"
                                            >
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    type="submit"
                                                    class="text-red-500 hover:underline"
                                                >
                                                    Eliminar
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-xs text-gray-400 italic">
                                    Encara no hi ha tasques en aquesta columna.
                                </div>
                            @endforelse
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>

    {{-- JS: Drag & Drop + crida AJAX a boards.notes.move --}}
    <script>
        let draggedNoteId = null;

        function handleDragStart(event) {
            const card = event.currentTarget;
            draggedNoteId = card.getAttribute('data-note-id');
            event.dataTransfer.effectAllowed = 'move';
            card.classList.add('opacity-60');
        }

        function handleDragOver(event) {
            event.preventDefault(); // necessari per permetre el drop
            event.dataTransfer.dropEffect = 'move';
        }

        function handleDrop(event) {
            event.preventDefault();

            const column = event.currentTarget;
            const newStatus = column.getAttribute('data-status');

            if (!draggedNoteId || !newStatus) return;

            const card = document.querySelector('[data-note-id="' + draggedNoteId + '"]');
            if (!card) return;

            card.classList.remove('opacity-60');
            column.appendChild(card);

            const root = document.getElementById('kanban-root');
            const template = root ? root.getAttribute('data-move-url-template') : null;
            if (!template) {
                console.error('No s\'ha trobat la plantilla d’URL per al move.');
                return;
            }

            const url = template.replace('__NOTE_ID__', draggedNoteId);

            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : null;

            fetch(url, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    ...(csrfToken ? { 'X-CSRF-TOKEN': csrfToken } : {}),
                },
                body: JSON.stringify({ status: newStatus }),
            })
                .then(response => {
                    if (!response.ok) {
                        console.error('Error actualitzant estat.', response.status);
                    }
                    return response.json().catch(() => null);
                })
                .then(data => {
                    // Aquí podries actualitzar comptadors, mostrar toast, etc.
                    // console.log(data);
                })
                .catch(error => {
                    console.error('Error en la petició AJAX:', error);
                })
                .finally(() => {
                    draggedNoteId = null;
                });
        }

        document.addEventListener('dragend', function (event) {
            const card = event.target;
            if (card && card.classList) {
                card.classList.remove('opacity-60');
            }
            draggedNoteId = null;
        });
    </script>
@endsection
