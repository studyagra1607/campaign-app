<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CampaignEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $subject;
    public $template;

    public function __construct($subject, $template)
    {
        $this->subject = $subject;
        $this->template = $template;
    }

    public function build()
    {
        return $this->subject($this->subject)->html($this->template);
    }
}
