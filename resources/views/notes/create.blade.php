@extends('layout') {{-- Utilitzem el layout base anomenat 'layout' --}}

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-xl mx-auto bg-white p-8 rounded-xl shadow-lg border border-gray-100">
        
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Crear Nova Nota per a: {{ $board->title }}</h1>
            {{-- Enllaç per tornar al tauler (index de notes) --}}
            <a href="{{ route('boards.notes.index', $board) }}" 
               class="text-indigo-600 hover:text-indigo-800 text-sm font-medium transition duration-150">
                &larr; Tornar al tauler
            </a>
        </div>

        {{-- El formulari envia les dades al mètode store del NoteController, amb l'ID del tauler --}}
        <form action="{{ route('boards.notes.store', $board) }}" method="POST">
            @csrf 
            
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Títol de la Nota <span class="text-red-500">*</span></label>
                <input type="text" name="title" id="title" required maxlength="150"
                       value="{{ old('title') }}"
                       class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('title') border-red-500 @enderror">
                
                @error('title')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Descripció (Opcional)</label>
                <textarea name="description" id="description" rows="3" maxlength="500"
                          class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                
                @error('description')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- NOU CAMP: SELECCIÓ D'ESTAT KANBAN --}}
            <div class="mb-6">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Estat de la Nota</label>
                <select name="status" id="status"
                        class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('status') border-red-500 @enderror">
                    
                    <option value="pending" @selected(old('status', 'pending') == 'pending')>Pendent</option>
                    <option value="in_progress" @selected(old('status') == 'in_progress')>En Progrés</option>
                    <option value="done" @selected(old('status') == 'done')>Finalitzat</option>
                </select>

                @error('status')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-6 rounded-lg shadow-md transition duration-150 ease-in-out">
                    Crear Nota
                </button>
            </div>
        </form>
    </div>
</div>
@endsection