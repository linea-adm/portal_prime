<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterarTamanhoDoTokenEmPasswordResets extends Migration
{
    public function up()
    {
        Schema::table('password_resets', function (Blueprint $table) {
            $table->string('token', 500)->change();
        });
    }

    public function down()
    {
        Schema::table('password_resets', function (Blueprint $table) {
            $table->string('token')->change();
        });
    }
}
