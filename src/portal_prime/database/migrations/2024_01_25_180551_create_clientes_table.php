<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientesTable extends Migration
{
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_loja')->unique(); // Ex: A1_COD + A1_LOJA
            $table->string('codigo');
            $table->string('loja')->nullable();
            $table->string('email')->nullable();
            $table->string('pessoa')->nullable();
            $table->string('razao')->nullable();
            $table->string('fantasia')->nullable();
            $table->string('codigo_nome')->nullable();
            $table->string('endereco')->nullable();
            $table->string('complemento')->nullable();
            $table->string('uf')->nullable();
            $table->string('codigo_municipio')->nullable();
            $table->string('municipio')->nullable();
            $table->string('bairro')->nullable();
            $table->string('cep')->nullable();
            $table->string('cnpj')->nullable();
            $table->string('vendedor')->nullable();
            $table->string('tabela')->nullable();
            $table->string('regiao')->nullable();
            $table->string('transportadora')->nullable();
            $table->string('rota')->nullable();
            $table->string('cod_segmento')->nullable();
            $table->string('segmento')->nullable();
            $table->string('canal')->nullable();
            $table->string('cod_regional')->nullable();
            $table->string('regional')->nullable();
            $table->string('bloqueado')->nullable();
            $table->string('grupo_cliente')->nullable();
            $table->string('desc_grupo')->nullable();
            $table->date('dt_cadastro')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('clientes');
    }
}
