<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class FeedbackMail extends Mailable
{
    use Queueable, SerializesModels;
    public $request,$view;
   // public $subject;

    /**
     * Create a new message instance.
     *
     * @param $request
     * @param $subject
     * @param null $attach
     * @param string $view
     */
    public function __construct($request, $attach = null, $view = 'emails.feedback')
    {
        $this->request = $request;
        $this->view = $view;
        $this->subject = trans('user.app_title').": ".$request['subject'];
        if(!is_null($attach))
            $this->attach($attach);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($address = env('MAIL_USERNAME'), $name = env('MAIL_FROM_NAME'))
                    ->view($this->view);
    }
}
