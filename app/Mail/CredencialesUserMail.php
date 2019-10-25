<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Model\User;


class CredencialesUserMail extends Mailable
{
    use Queueable, SerializesModels;
    public $usuario;
    public $contrasenia;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct( User $usuario, $contrasenia)
    {
        $this->usuario = $usuario;
        $this->contrasenia=$contrasenia;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('Mail.prueba');
    }
}
