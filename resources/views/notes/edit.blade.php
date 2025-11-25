@extends('layout') 

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-white p-8 rounded-xl shadow-lg border border-gray-100">
        
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">
                Editar Nota: {{ Str::limit($note->title, 20) }}
            </h1>

            <a href="{{ route('boards.notes.index', $board->id) }}" 
               class="text-indigo-600 hover:text-indigo-800 text-sm font-medium transition duration-150">
                &larr; Tornar a {{ $board->title }}
            </a>
        </div>
        
        <p class="text-sm text-gray-500 mb-4 border-b pb-4">
            Editant nota per al tauler: 
            <span class="font-semibold text-indigo-700">{{ $board->title }}</span>
        </p>

        {{-- FORMULARI --}}
        <form action="{{ route('boards.notes.update', ['board' => $board->id, 'note' => $note->id]) }}" method="POST">
            @csrf 
            @method('PUT')

            {{-- Títol --}}
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                    Títol de la Nota <span class="text-red-500">*</span>
                </label>

                <input type="text" 
                       name="title" 
                       id="title" 
                       required 
                       maxlength="150"
                       value="{{ old('title', $note->title) }}"
                       class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm 
                              focus:border-indigo-500 focus:ring-indigo-500 
                              @error('title') border-red-500 @enderror">

                @error('title')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Estat --}}
            <div class="mb-4">

                @error('status')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Descripció --}}
            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                    Contingut de la Nota (Opcional)
                </label>

                <textarea name="description" 
                          id="description" 
                          rows="5" 
                          maxlength="500"
                          class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm 
                                 focus:border-indigo-500 focus:ring-indigo-500 
                                 @error('description') border-red-500 @enderror">{{ old('description', $note->description) }}</textarea>

                @error('description')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Botó final --}}
            <div class="flex justify-end">
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg shadow-md transition duration-150 ease-in-out">
                    Desar Canvis
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
