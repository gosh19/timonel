<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMailable extends Mailable
{
    use Queueable, SerializesModels;
    public $name;
    public $title;
     public $messagepro;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name,$title,$messagepro)
    {
        $this->name = $name;
        $this->title =$title;
        $this->messagepro= $messagepro;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.mail')->subject('Notificaciòn de Timonelbm!');
    }
}
