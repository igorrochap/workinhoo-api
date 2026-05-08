<?php

namespace App\Listeners;

use App\Events\RecuperarSenhaEvent;
use App\Mail\RecuperarSenhaMailable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class RecuperarSenhaListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(RecuperarSenhaEvent $evento): void
    {
        $email = new RecuperarSenhaMailable($evento->email, $evento->nome, $evento->codigo);
        Mail::to($evento->email)->send($email);
    }
}
