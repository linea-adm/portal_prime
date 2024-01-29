<?php

namespace App\Http\Controllers;

use App\Models\Cliente;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class AgendamentoController extends Controller
{
    public function gerarLink(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'codigo' => 'required',
            'loja' => 'required',
            'cnpj' => 'required',
        ]);

        // Lógica para gerar o link com base nas informações do cliente
        $link = $this->gerarLinkAgendamento($data);

        // Assinar a URL antes de retornar
        $linkAssinado = URL::temporarySignedRoute('agendamento', now()->addMinutes(30), [
            'codigo' => $data['codigo'],
            'loja' => $data['loja'],
            'cnpj' => $data['cnpj'],
        ]);

        return response()->json(['link' => $linkAssinado]);
    }

    private function gerarLinkAgendamento($data)
    {
        // Lógica para gerar o link com base nas informações do cliente
        // Exemplo: Concatenar informações do cliente para criar um link único

        $urlBase = env('APP_URL', 'https://seu-site.com');
        $link = $urlBase . '/agendamento';

        return $link;
    }
    public function processarLink(Request $request)
    {
        // Verifique se a assinatura é válida e se os parâmetros são confiáveis
        if (!$request->hasValidSignature()) {
            abort(403, 'Assinatura inválida na URL.');
        }

        // Obtenha os parâmetros da URL assinada
        $codigo = $request->query('codigo');
        $loja = $request->query('loja');
        $cnpj = $request->query('cnpj');

        // Lógica para processar os parâmetros
        // ...

        return view('agendamento');
    }
    public function testarLinkAssinado()
    {
        $data = [
            'codigo' => '123',
            'loja' => '02',
            'cnpj' => '12345678901234',
        ];

        // Gere um link assinado para os parâmetros
        $linkAssinado = URL::temporarySignedRoute('agendamento', now()->addMinutes(30), $data);

        return response()->json(['link' => $linkAssinado]);
    }

}
