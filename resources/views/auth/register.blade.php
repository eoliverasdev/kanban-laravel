<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registre</title>
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
        <h2 class="text-3xl font-extrabold text-center text-indigo-700">
            Crea el teu Compte Kanban CAT
        </h2>

        <form class="mt-8 space-y-4" action="{{ route('register') }}" method="POST">
            @csrf 

            @if ($errors->any())
                <div class="p-3 text-sm text-red-700 bg-red-100 rounded-lg">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Nom</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Adreça de correu</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Contrasenya</label>
                <input id="password" name="password" type="password" required
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirma la Contrasenya</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div>
                <button type="submit"
                        class="w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700">
                    Registra't
                </button>
            </div>
        </form>
        
        <div class="text-center text-sm">
            <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                Ja tens un compte? Inicia sessió aquí
            </a>
        </div>
    </div>
</body>
</html>