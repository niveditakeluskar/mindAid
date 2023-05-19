<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ResetMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        // return $this->from('Renova@gmail.com', 'Mailtrap')
        //     ->subject('Mailtrap Confirmation')
        //     ->markdown('mails.exmpl')
        //     ->with([
        //         'name' => 'New Mailtrap User',
        //         'link' => 'https://mailtrap.io/inboxes'
        //     ]);
         return $this->view('auth/passwords/email');
    }
}
