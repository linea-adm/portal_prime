<?php
// app/Models/Cliente.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'clientes'; // Nome da tabela no banco de dados

    protected $fillable = [
        'codigo_loja',
        'codigo',
        'loja',
        'pessoa',
        'razao',
        'fantasia',
        'codigo_nome',
        'endereco',
        'complemento',
        'uf',
        'codigo_municipio',
        'municipio',
        'bairro',
        'cep',
        'cnpj',
        'vendedor',
        'tabela',
        'regiao',
        'dt_primeira_compra',
        'transportadora',
        'rota',
        'cod_segmento',
        'segmento',
        'canal',
        'cod_regional',
        'regional',
        'bloqueado',
        'grupo_cliente',
        'desc_grupo',
        'dt_cadastro',
    ];

    // Se necessário, adicione relacionamentos, mutators, accessors, etc.
}
