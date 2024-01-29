<?php


namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use GuzzleHttp\Client;
use App\Models\Cliente;

class SyncClientes extends Command
{
    protected $signature = 'sync:clientes';
    protected $description = 'Synchronize clients with Protheus';

    public function handle()
    {
        $client = new Client();
        $response = $client->get('https://gobi-api.lineaalimentos.com.br/v1/reports/519/data?streaming=true&format=json', [
            'headers' => [
                'Authorization' => 'Bearer 8cyDzzuR_xFSj5hzezzS',
            ],
        ]);

        $clientes = json_decode($response->getBody(), true);

        foreach ($clientes as $clienteData) {
            // Construa a combinação de campos que garante a integridade do cadastro do cliente
            $identificadorProtheus = [
                'codigo' => $clienteData['Codigo'] ?? null,
                'loja' => $clienteData['Loja'] ?? null,
                'cnpj' => $clienteData['cnpj'] ?? null,
                'grupo_cliente' => $clienteData['Grupo Cliente'] ?? null,
            ];

            // Verifica se as chaves necessárias existem no array
            if (isset($identificadorProtheus['codigo'], $identificadorProtheus['loja'], $identificadorProtheus['cnpj'], $identificadorProtheus['grupo_cliente'])) {
                $dtCadastro = isset($clienteData['DT Cadastro']) ? Carbon::createFromFormat('d/m/Y', $clienteData['DT Cadastro'])->format('Y-m-d') : null;

                $cliente = Cliente::updateOrCreate($identificadorProtheus, [
                    'pessoa' => $clienteData['Pessoa'] ?? null,
                    'razao' => $clienteData['Razao'] ?? null,
                    'codigo' => $clienteData['Codigo'],
                    'loja' => $clienteData['Loja'],
                    'codigo_loja' => $clienteData['Codigo'].$clienteData['Loja'],
                    'fantasia' => $clienteData['Fantasia'],
                    'codigo_nome' => $clienteData['Codigo e Nome'],
                    'endereco' => $clienteData['Endereco'],
                    'complemento' => $clienteData['Complemento'],
                    'uf' => $clienteData['uf'],
                    'codigo_municipio' => $clienteData['Codigo Municipio'],
                    'municipio' => $clienteData['Municipio'],
                    'bairro' => $clienteData['Bairro'],
                    'cep' => $clienteData['cep'],
                    'cnpj' => $clienteData['cnpj'],
                    'vendedor' => $clienteData['Vendedor'],
                    'tabela' => $clienteData['Tabela'],
                    'regiao' => $clienteData['Regiao'],
                    // 'dt_primeira_compra' => $clienteData['DT Primeira Compra'],
                    'transportadora' => $clienteData['Transportadora'],
                    'rota' => $clienteData['Rota'],
                    'cod_segmento' => $clienteData['Cod Segmento'],
                    'segmento' => $clienteData['Segmento'],
                    'canal' => $clienteData['Canal'],
                    'cod_regional' => $clienteData['Cod Regional'],
                    'regional' => $clienteData['Regional'],
                    'bloqueado' => $clienteData['Bloqueado'],
                    'grupo_cliente' => $clienteData['Grupo Cliente'],
                    'desc_grupo' => $clienteData['Desc. Grupo'],
                    'dt_cadastro' => $dtCadastro,
                ]);
            }
        }

        $this->info('Clientes sincronizados com sucesso.');
    }
}
