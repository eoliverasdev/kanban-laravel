@extends('layout')

@section('content')
<div class="container mx-auto px-4 py-8">
    
    <div class="flex justify-between items-center mb-6 border-b pb-4">
        <h1 class="text-3xl font-extrabold text-indigo-700">Els Meus Taulers</h1>
        <a href="{{ route('boards.create') }}"
           class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition duration-150 ease-in-out">
            + Nou Tauler
        </a>
    </div>

    @if ($boards->isEmpty())
        <p class="text-center text-gray-500 py-12">Encara no has creat cap tauler. Comença ara!</p>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($boards as $board)
                <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-xl transition duration-300 border-t-4 border-indigo-500">
                    <h2 class="text-xl font-bold text-gray-800 mb-2">{{ $board->title }}</h2>
                    <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $board->description }}</p>
                    
                    <div class="flex justify-between items-center mt-4 border-t pt-3">
                        <span class="text-xs text-gray-400">Creat: {{ $board->created_at->diffForHumans() }}</span>

                        <div class="flex items-center space-x-3">
                            {{-- Enllaç per veure el Kanban --}}
                            <a href="{{ route('boards.notes.index', $board) }}" 
                               class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition duration-150">
                                Veure Kanban &rarr;
                            </a>

                            {{-- Enllaç d'edició del Tauler --}}
                            <a href="{{ route('boards.edit', $board) }}" 
                               class="text-sm font-semibold text-gray-500 hover:text-gray-700 transition duration-150">
                                Editar
                            </a>

                            {{-- 🗑️ FORMULARI D'ELIMINACIÓ AFEGIT AQUÍ --}}
                            <form action="{{ route('boards.destroy', $board) }}" method="POST" 
                                  onsubmit="return confirm('Estàs segur que vols eliminar el tauler «{{ $board->title }}»? Aquesta acció és irreversible.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="text-sm font-semibold text-red-500 hover:text-red-700 transition duration-150 leading-none">
                                    Eliminar
                                </button>
                            </form>
                            {{-- 🗑️ FI DEL FORMULARI D'ELIMINACIÓ --}}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection