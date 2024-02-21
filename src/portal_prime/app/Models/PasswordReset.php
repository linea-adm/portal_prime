<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    use HasFactory;

    protected $table = 'password_resets'; // Nome da tabela no banco de dados

    protected $fillable = [
        'email',
        'token',
        'created_at',
        'updated_at',
    ];

    // Se necessário, adicione relacionamentos ou métodos personalizados aqui
}
