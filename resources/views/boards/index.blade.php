@extends('layout')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Els Meus Taulers (Boards)</h1>
        
        {{-- Botó per CREAR un nou tauler --}}
        <a href="{{ route('boards.create') }}" 
           class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg shadow transition duration-150 ease-in-out">
            + Nou Tauler
        </a>
    </div>

    {{-- Llista de Taulers --}}
    @if ($boards->isEmpty())
        <div class="text-center py-12 border-2 border-dashed border-gray-300 rounded-xl bg-gray-50">
            <p class="text-xl text-gray-500">Encara no teniu taulers creats.</p>
            <p class="text-gray-400 mt-2">Feu clic a "Nou Tauler" per començar el vostre projecte.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($boards as $board)
                <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition duration-300 ease-in-out border border-gray-100">
                    <div class="p-6">
                        {{-- Enllaç per veure els detalls del tauler --}}
                        <a href="{{ route('boards.show', $board->id) }}" class="text-xl font-semibold text-indigo-600 hover:text-indigo-800 transition duration-150">
                            {{ $board->title }}
                        </a>
                        
                        {{-- S'usa 'Sense descripció.' com a text per defecte --}}
                        <p class="text-gray-500 mt-2 line-clamp-3">{{ $board->description ?? 'Sense descripció.' }}</p>
                        
                        <div class="mt-4 flex justify-between items-center text-sm text-gray-400">
                            <span>Creat: {{ $board->created_at->diffForHumans() }}</span>
                            
                            {{-- Enllaç per EDITAR el tauler --}}
                            <a href="{{ route('boards.edit', $board->id) }}" class="text-xs text-indigo-500 hover:text-indigo-700 font-medium">
                                Editar
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection