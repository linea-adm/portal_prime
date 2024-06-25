<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Agendamento de Entregas - Linea Alimentos</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    @vite(['resources/sass/app.scss'])
    @vite(['resources/js/app.js'])

    <style>
        body {
            background-image: url({{ asset('/images/bg-linea.png') }});
            background-repeat: no-repeat;
            background-position: center bottom;
            background-size: cover;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            margin: 0;
        }

        .header-bg {
            /* background-color: #000; */
            color: #000000;
            padding: 1rem;
            width: 100%;
            text-align: center;
            position: fixed;
            top: 0;
        }

        .footer-bg {
            /* background-color: #000; */
            color: #000;
            text-align: center;
            width: 100%;
            padding: 0.5rem;
            position: fixed;
            bottom: 0;
        }

        .header-text {
            color: #000;
            font-size: 1.8em;
        }

        #box-login {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 0.8rem;
            border-radius: 0.5rem;
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            margin: 3rem 0;
        }

        .title-login {
            padding: 0.5rem;
            background-color: #425789;
            color: white;
            text-align: center;
            border-radius: 0.5rem 0.5rem 0 0;
            font-size: 1.5rem;
        }

        form button[type="submit"] {
            margin-top: 1rem;
            background-color: #425789;
        }

        @media (max-width: 768px) {
            #box-login {
                width: 90%;
            }
        }
    </style>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
        integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="antialiased">

    <div id="topo" class="header-bg">
        <div class="flex items-center justify-between">
            {{-- <img src="{{ asset('/images/logo_linea.png') }}" alt="Logo" class="h-6" height="40"> --}}
            <div class="h-6" height="40">&nbsp;</div>
            <h1 class="text-2xl font-bold header-text">Agendamento de Entregas - Linea Alimentos</h1>
            <div></div>
        </div>
    </div>

    <div id="app" class="flex flex-col justify-center items-center">
        <div id="box-login">
            <h2 class="title-login">
                <i class="fas fa-user mr-2"></i> Login do Cliente
            </h2>
            <form class="p-2" method="POST" action="{{ route('email_clientes.login.submit') }}">
                @csrf
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 font-semibold mb-2">E-mail</label>
                    <input type="email" name="email" id="email" required
                        class="rounded-md shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 p-2">
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-gray-700 font-semibold mb-2">Senha</label>
                    <input type="password" name="password" id="password" required
                        class="rounded-md shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 p-2">
                </div>
                <div>
                    <button type="submit"
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-800 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Login</button>
                </div>
            </form>
        </div>
    </div>

    <div class="footer-bg">
        <p>&copy; Linea Alimentos 2024 </p>
    </div>

</body>

</html>
