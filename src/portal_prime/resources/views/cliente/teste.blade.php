<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Teste de Login - Linea Alimentos</title>
    @vite(['resources/sass/app.scss'])
    @vite(['resources/js/app.js'])
</head>
<body class="antialiased">
    <div class="min-h-screen flex items-center justify-center bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <h2 class="text-center text-3xl font-bold text-gray-900">Login bem-sucedido! Você está autenticado.</h2>
            <form method="POST" action="{{ route('email_clientes.logout') }}">
                @csrf
                <button type="submit" class="w-full bg-red-500 text-white py-2 px-4 rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Logout
                </button>
            </form>
        </div>
    </div>
</body>
</html>
