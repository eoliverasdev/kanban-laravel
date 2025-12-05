<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- 🚨 ADDICIÓ CLAU 1: CSRF Token necessari per a les peticions AJAX a Laravel (Desar/Eliminar) --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Tauler Kanban</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">
    
    {{-- BARRA DE NAVEGACIÓ BÀSICA --}}
    <nav class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex">
                    <a href="{{ route('boards.index') }}" class="flex-shrink-0 flex items-center">
                        <span class="text-2xl font-bold text-indigo-600">🧠 Kanban CAT</span>
                    </a>
                </div>
                
                <div class="hidden sm:ml-6 sm:flex sm:items-center space-x-4">
                    @auth
                        {{-- Enllaç a Taulers (si està loguejat) --}}
                        <a href="{{ route('boards.index') }}" class="text-gray-500 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">
                            Taulers
                        </a>

                        {{-- Formulari de Tancament de Sessió (Logout) --}}
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="text-sm font-semibold text-red-500 hover:text-white px-3 py-1 rounded-lg transition duration-150 ease-in-out border border-red-500 hover:bg-red-600">
                                Tancar Sessió
                            </button>
                        </form>
                    @else
                        {{-- Enllaç a Login (si no està loguejat) --}}
                        <a href="{{ route('login') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800 px-3 py-1 rounded-lg border border-indigo-600">
                            Iniciar Sessió
                        </a>
                        {{-- Opcionalment, afegir enllaç a registre aquí --}}
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- CONTINGUT PRINCIPAL INJECTAT --}}
    <main>
        @yield('content')
    </main>

    {{-- 🚨 ADDICCIÓ CLAU 2: SortableJS per a la funcionalitat de Drag & Drop --}}
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

    {{-- Aquí es carregarà l'script de la vista (p. ex., notes/index.blade.php) --}}
    @yield('scripts')

</body>
</html>