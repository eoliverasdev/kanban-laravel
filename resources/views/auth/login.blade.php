<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inici de Sessió</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f7f9fb;
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md p-8 space-y-6 bg-white shadow-xl rounded-2xl border border-gray-100">
        <h2 class="text-3xl font-extrabold text-center text-gray-900">
            Kanban CAT 
        </h2>
        <p class="text-center text-sm text-gray-600">
            Inicia sessió per accedir als teus taulers.
        </p>

        <form class="mt-8 space-y-6" action="{{ route('login') }}" method="POST">
            @csrf @error('email')
                <div class="p-3 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
                    {{ $message }}
                </div>
            @enderror
            
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Adreça de correu</label>
                <div class="mt-1">
                    <input id="email" name="email" type="email" autocomplete="email" required
                           value="{{ old('email') }}"
                           class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Contrasenya</label>
                <div class="mt-1">
                    <input id="password" name="password" type="password" autocomplete="current-password" required
                           class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
            </div>

            <div>
                <button type="submit"
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-indigo-500 group-hover:text-indigo-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2V7a3 3 0 00-6 0v2h6z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    Iniciar Sessió
                </button>
            </div>
        </form>

        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600">
                No tens un compte?
                <a href="{{ route('register') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                    Registra't
                </a>
            </p>
        </div>
        
    </div>
</body>
</html>