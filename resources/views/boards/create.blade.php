@extends('layout')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-xl mx-auto bg-white p-8 rounded-xl shadow-lg border border-gray-100">
        
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Crear Nou Tauler</h1>
            {{-- Enllaç per tornar a la llista (assumeix que boards.index existeix) --}}
            <a href="{{ route('boards.index') }}" 
               class="text-indigo-600 hover:text-indigo-800 text-sm font-medium transition duration-150">
                &larr; Tornar a la llista
            </a>
        </div>

        {{-- El formulari apunta al mètode store del BoardController --}}
        <form action="{{ route('boards.store') }}" method="POST">
            @csrf 
            
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Títol del Tauler <span class="text-red-500">*</span></label>
                {{-- La funció old('title') manté el valor si hi ha un error de validació --}}
                <input type="text" name="title" id="title" required maxlength="150"
                       value="{{ old('title') }}"
                       class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('title') border-red-500 @enderror">
                
                {{-- Mostra l'error de validació del títol --}}
                @error('title')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Descripció (Opcional)</label>
                <textarea name="description" id="description" rows="3" maxlength="500"
                          class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                
                {{-- Mostra l'error de validació de la descripció --}}
                @error('description')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-6 rounded-lg shadow-md transition duration-150 ease-in-out">
                    Crear Tauler
                </button>
            </div>
        </form>
    </div>
</div>
@endsection