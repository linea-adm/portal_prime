<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class Cliente  extends Authenticatable
{
    use HasFactory;

    protected $table = 'clientes'; // Nome da tabela no banco de dados

    protected $primaryKey = 'id';

    protected $fillable = [
        'codigo_loja',
        'codigo',
        'loja',
        'email',
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
    public function emails()
    {
        return $this->hasMany(EmailCliente::class);
    }
}
