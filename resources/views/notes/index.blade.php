@extends('layout')

@section('content')
<div class="container mx-auto px-4 py-8">
    
    <div class="flex justify-between items-center mb-6 border-b pb-4">
        <h1 class="text-3xl font-extrabold text-indigo-700">Tauler Kanban: {{ $board->title }}</h1>
        <div class="space-x-3">
            {{-- Enllaç per tornar a l'índex de Taulers --}}
            <a href="{{ route('boards.index') }}" 
               class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 transition duration-150">
                &larr; Tots els Taulers
            </a>
            {{-- Botó de Creació de Nota --}}
            <a href="{{ route('boards.notes.create', $board) }}"
               class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition duration-150 ease-in-out inline-flex items-center text-sm">
                + Afegir Nota
            </a>
        </div>
    </div>

    {{-- Missatges de sessió (Success/Error) --}}
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif
    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    {{-- ESTRUCTURA KANBAN --}}
    {{-- Configuració de 3 columnes amb espai (gap) --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        @foreach ($statuses as $status)
            {{-- Determinem el títol i color de la columna (variables locals) --}}
            @php
                $title = match($status) {
                    'pending' => 'Pendent',
                    'in_progress' => 'En Progrés',
                    'done' => 'Finalitzat',
                    default => 'Altres'
                };
                $color = match($status) {
                    'pending' => 'bg-red-50 ring-red-300',
                    'in_progress' => 'bg-yellow-50 ring-yellow-300',
                    'done' => 'bg-green-50 ring-green-300',
                    default => 'bg-gray-50 ring-gray-300'
                };
            @endphp

            {{-- COLUMNA KANBAN INDIVIDUAL --}}
            <div class="flex flex-col h-full rounded-xl shadow-lg ring-1 {{ $color }}">
                {{-- Capçalera de la columna --}}
                <div class="p-4 border-b border-opacity-50">
                    <h2 class="text-xl font-bold text-gray-800 flex justify-between items-center">
                        <span>{{ $title }}</span>
                        {{-- Comptador de notes a la columna --}}
                        <span class="text-sm font-medium rounded-full px-3 py-1 bg-white text-gray-600 shadow-sm">
                            {{ $groupedNotes[$status]->count() }}
                        </span>
                    </h2>
                </div>

                {{-- Contingut de la columna (contenidor de notes) --}}
                <div class="p-4 flex-grow space-y-4 overflow-y-auto" style="min-height: 400px; max-height: 80vh;">
                    
                    {{-- Iterem sobre les notes agrupades per l'estat actual ($groupedNotes[$status]) --}}
                    @forelse ($groupedNotes[$status] as $note)
                        <div class="bg-white p-4 rounded-lg shadow-md border border-gray-200 hover:shadow-lg transition duration-150">
                            <h3 class="font-semibold text-lg text-gray-800 truncate">{{ $note->title }}</h3>
                            <p class="text-gray-600 text-sm mt-1 mb-3 line-clamp-2">
                                {{ $note->description ?: 'Sense descripció.' }}
                            </p>
                            
                            {{-- Enllaç d'edició (Corregit i només aquest) --}}
                            <div class="flex justify-end">
                                <a href="{{ route('boards.notes.edit', ['board' => $board, 'note' => $note]) }}"
                                   class="text-sm font-medium text-indigo-500 hover:text-indigo-700 transition duration-150">
                                    Editar &rarr;
                                </a>
                            </div>
                        </div>
                    @empty
                        {{-- Missatge si la columna està buida --}}
                        <div class="text-center py-6 text-gray-500 bg-gray-100 rounded-lg border-dashed border-2 border-gray-300">
                            No hi ha notes a {{ $title }}.
                        </div>
                    @endforelse

                </div>
            </div>
        @endforeach

    </div>
</div>
@endsection