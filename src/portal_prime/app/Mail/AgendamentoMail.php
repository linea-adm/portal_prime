<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AgendamentoMail extends Mailable
{
    use Queueable, SerializesModels;

    public $detalhes;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($detalhes)
    {
        $this->detalhes = $detalhes;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Confirmação de Agendamento de Entrega - Linea Alimentos')
                    ->view('emails.agendamento');
    }
}
