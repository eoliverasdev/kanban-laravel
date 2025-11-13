@extends('layout')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-5xl mx-auto">

        {{-- Capçalera del Tauler --}}
        <div class="bg-indigo-50 p-6 rounded-xl shadow-md border-b-4 border-indigo-600 mb-8">
            <div class="flex items-center justify-between">
                <h1 class="text-3xl font-extrabold text-indigo-900">Tauler: {{ $board->title }}</h1>
                <a href="{{ route('boards.index') }}" 
                   class="text-sm font-medium text-gray-600 hover:text-gray-800 transition duration-150">
                    &larr; Tornar a Tots els Taulers
                </a>
            </div>
            <p class="text-indigo-700 mt-2">{{ $board->description ?? 'Sense descripció.' }}</p>
        </div>

        {{-- Missatges d'èxit --}}
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-6" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        {{-- Acció de Creació de Nova Nota --}}
        <div class="flex justify-end mb-6">
            {{-- La ruta de creació necessita el ID del tauler --}}
            <a href="{{ route('boards.notes.create', $board->id) }}" 
               class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition duration-150 ease-in-out text-sm">
                + Afegir Nova Nota
            </a>
        </div>

        {{-- Llistat de Notes --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            
            @forelse ($notes as $note)
                <div class="bg-white p-5 rounded-xl shadow-lg border-t-4 border-gray-300 flex flex-col justify-between h-full">
                    
                    <div>
                        <h2 class="text-xl font-bold text-gray-800 mb-2">{{ $note->title }}</h2>
                        <p class="text-gray-600 text-sm mb-4">{{ Str::limit($note->description, 150) }}</p>
                    </div>

                    {{-- Accions de Nota --}}
                    <div class="mt-4 flex justify-end space-x-3 border-t pt-3">
                        
                        {{-- Botó d'Edició --}}
                        <a href="{{ route('boards.notes.edit', ['board' => $board->id, 'note' => $note->id]) }}"
                           class="text-sm font-medium text-blue-600 hover:text-blue-800 transition duration-150 p-2 rounded-lg hover:bg-blue-50">
                            Editar
                        </a>

                        {{-- Formulari d'Eliminació --}}
                        <form action="{{ route('boards.notes.destroy', ['board' => $board->id, 'note' => $note->id]) }}" method="POST"
                              onsubmit="return confirm('Estàs segur que vols eliminar la nota «{{ $note->title }}»?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="text-sm font-medium text-red-600 hover:text-red-800 transition duration-150 p-2 rounded-lg hover:bg-red-50">
                                Eliminar
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="md:col-span-3 text-center py-10 bg-gray-50 rounded-lg shadow-inner">
                    <p class="text-gray-500">Aquest tauler no té cap nota. Fes clic a "Afegir Nova Nota" per començar.</p>
                </div>
            @endforelse
        </div>
        
    </div>
</div>
@endsection