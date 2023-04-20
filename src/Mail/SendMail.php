<?php

namespace BossmanFilamentApp\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMail extends Mailable
{

    use Queueable, SerializesModels;

    public $details;
    public $formContent;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($details,$formContent)
    {
        $this->details = $details;
        $this->formContent = $formContent;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Mail from '.env('APP_NAME'))
            ->view($this->details['mailView']);
    }
}
