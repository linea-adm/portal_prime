<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Programação de Entregas - Linea Alimentos</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    {{-- <link rel="stylesheet" href="{{ asset('css/app.css') }}"> --}}

    @vite(['resources/sass/app.scss'])
    @vite(['resources/js/app.js'])

    <style>
        .header-bg {
            /* background-color: #4a90e2; */
            /* background-color:#425789; */
            background-color: #000;
            color: #fff;
            padding: 1rem;
        }

        .topo {
            background-color: #4a90e2;
            background-color: #425789;

            background-color: #000;
            color: #fff;
            text-align: center;
            position: fixed;
            width: 100%;
            padding: 0.5rem;
            z-index: 1000;
            /* Adicionei z-index para garantir que o topo fique sobre outros elementos */
        }

        .footer-bg {
            /* background-color: #4a90e2; */
            background-color: #425789;

            background-color: #000;
            color: #fff;
            text-align: center;
            position: fixed;
            bottom: 0;
            width: 100%;
            padding: 0.5rem;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            margin-bottom: 130px;
        }

        .header-text {
            color: #fff;
        }

        .table th {
            background-color: #edf2f7;
        }

        .table th,
        .table td {
            padding: 0.75rem;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }

        .table tbody tr:hover {
            background-color: #f8fafc;
        }

        .pagination {
            margin-top: 1em;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-bg {
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: #fff;
            padding: 1.5rem;
            border-radius: 0.375rem;
            margin: auto;
        }

        /* Adicione ou ajuste estilos CSS conforme necessário */
        .modal-content table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1rem;
        }

        .modal-content th,
        .modal-content td {
            border: 1px solid #e2e8f0;
            padding: 8px;
            text-align: left;
        }

        .modal-content th {
            background-color: #edf2f7;
        }

        .dados-cliente {
            margin-top: 80px;
        }

        .progress {
            height: 20px;
            margin-bottom: 20px;
            overflow: hidden;
            background-color: #f5f5f5;
            border-radius: 4px;
        }

        .progress-bar {
            float: left;
            width: 0;
            height: 100%;
            font-size: 12px;
            line-height: 20px;
            color: #fff;
            text-align: center;
            background-color: #337ab7;
            box-shadow: inset 0 -1px 0 rgba(0, 0, 0, .15);
            transition: width .6s ease;
        }
        /* Estilos do preload */
        .loader {
            border: 16px solid #f3f3f3;
            /* Light grey */
            border-top: 16px solid #3498db;
            /* Blue */
            border-radius: 50%;
            width: 120px;
            height: 120px;
            animation: spin 2s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
        integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="antialiased">

    <div id="topo" class=" topo bg-blue-500 p-5 mb-7 header-bg">


        <div class="flex items-center justify-between">
            <img src="{{ asset('/images/logo_linea.png') }}" alt="Logo" class="h-6" height="40">
            <h1 class="text-2xl font-bold header-text">Programação de Entregas - Linea Alimentos</h1>
            <div></div>
        </div>
    </div>

    <div id="app">

        <!-- Preload -->
        <div id="preloader" class="flex items-center justify-center h-screen">
            <div class="loader"></div>
        </div>
        
        <agendamento :dados-cliente='@json($dadosCliente)' :dados-notas-fiscais='@json($dadosNotasFiscais)'  logout-url="{{ $logout }}"></agendamento>
    </div>

    <!-- Rodapé com Copyright -->
    <div class="footer-bg">
        <p>&copy; Linea Alimentos 2024 </p>
    </div>

</body>

</html>
