@extends('layout')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-xl mx-auto bg-white p-8 rounded-xl shadow-lg border border-gray-100">
        
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Editar Tauler: {{ $board->title }}</h1>
            {{-- Enllaç per tornar a la llista --}}
            <a href="{{ route('boards.index') }}" 
               class="text-indigo-600 hover:text-indigo-800 text-sm font-medium transition duration-150">
                &larr; Tornar a la llista
            </a>
        </div>

        {{-- El formulari apunta al mètode update del BoardController.
             S'utilitza @method('PUT') ja que els formularis HTML només suporten GET i POST. --}}
        <form action="{{ route('boards.update', $board->id) }}" method="POST">
            @csrf 
            @method('PUT') 
            
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Títol del Tauler <span class="text-red-500">*</span></label>
                {{-- Carrega el valor existent o el valor antic si hi ha error de validació --}}
                <input type="text" name="title" id="title" required maxlength="150"
                       value="{{ old('title', $board->title) }}"
                       class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('title') border-red-500 @enderror">
                
                @error('title')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Descripció (Opcional)</label>
                <textarea name="description" id="description" rows="3" maxlength="500"
                          class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('description') border-red-500 @enderror">{{ old('description', $board->description) }}</textarea>
                
                @error('description')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-6 rounded-lg shadow-md transition duration-150 ease-in-out">
                    Desar Canvis
                </button>
            </div>
        </form>
    </div>
</div>
@endsection