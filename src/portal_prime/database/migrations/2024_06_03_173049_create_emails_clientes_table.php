<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailsClientesTable extends Migration
{
    public function up()
    {
        Schema::create('emails_clientes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cliente_id');
            $table->string('email')->unique();
            $table->string('password');
            $table->timestamps();

            $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('emails_clientes');
    }
}
