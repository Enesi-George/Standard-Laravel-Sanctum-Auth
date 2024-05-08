<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyEmail extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Verify Your Email')
            ->markdown('emails.verify')
            ->with([
                'emailVerificationLink' => $this->generateVerifyEmailLink(),
                'user' => $this->user
            ]);
    }

    /**
     * Generate the email verification link.
     *
     * @return string
     */

    protected function generateVerifyEmailLink()
    {
        return route('verify.email', ['token' => $this->user->otp_token]);
    }
}
