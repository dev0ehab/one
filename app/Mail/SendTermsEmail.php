<?php

namespace App\Mail;

use App\Models\Page;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendTermsEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;
    public $terms;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
        $this->terms = Page::where('slug', 'terms')->first();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): SendTermsEmail
    {
        $fromAddress = config('mail.from.address');
        $fromName    = config('mail.from.name');

        return $this->from($fromAddress, $fromName)
                    ->subject('الشروط والأحكام - Terms and Conditions')
                    ->view('emails.terms')
                    ->with([
                        'user' => $this->user,
                        'terms' => $this->terms
                    ]);
    }
}
