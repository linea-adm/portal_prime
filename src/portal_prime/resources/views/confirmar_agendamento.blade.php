<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Confirmação de Agendamento - Linea Alimentos</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .header-bg, .footer-bg {
            background-color: #000;
            color: #fff;
            text-align: center;
            padding: 1rem;
        }
    </style>
</head>

<body class="antialiased">

    <div class="header-bg">
        <h1 class="text-2xl font-bold">Confirmação de Agendamento - Linea Alimentos</h1>
    </div>

    <div class="container">
        <h2 class="text-xl font-semibold mb-4">Cliente: {{ $dadosCliente['nomeFantasia'] }}</h2>
        <p><strong>Município-UF:</strong> {{ $dadosCliente['municipioEstado'] }}</p>
        <p><strong>CNPJ:</strong> {{ $dadosCliente['cnpj'] }}</p>
        <p><strong>E-mail:</strong> {{ $dadosCliente['email'] }}</p>
        <h2 class="text-xl font-semibold mt-6 mb-4">Notas Fiscais Selecionadas</h2>
        <ul>
            @foreach ($notasSelecionadas as $nota)
                <li>{{ $nota['f2_doc'] }} - {{ $nota['c5_num'] }}</li>
            @endforeach
        </ul>
        <h2 class="text-xl font-semibold mt-6 mb-4">Data e Hora do Agendamento</h2>
        <p><strong>Data:</strong> {{ $dataAgendamento }}</p>
        <p><strong>Hora:</strong> {{ $horaAgendamento }}</p>

        <form action="{{ route('agendamento.finalizar') }}" method="POST">
            @csrf
            <input type="hidden" name="notasSelecionadas" value="{{ json_encode($notasSelecionadas) }}">
            <input type="hidden" name="dataAgendamento" value="{{ $dataAgendamento }}">
            <input type="hidden" name="horaAgendamento" value="{{ $horaAgendamento }}">
            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-md font-bold">
                Confirmar Agendamento
            </button>
        </form>
    </div>

    <div class="footer-bg">
        <p>&copy; Linea Alimentos 2024</p>
    </div>
</body>

</html>
