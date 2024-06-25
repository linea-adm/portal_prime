<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class EmailCliente extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'emails_clientes';
    protected $fillable = [
        'cliente_id',
        'email',
        'password',
    ];

    // Ocultar campos na serialização
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Cast de tipos para campos específicos
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Relacionamento com o model Cliente.
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}
