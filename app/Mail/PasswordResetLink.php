<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;

class PasswordResetLink extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;
    /**
     * Create a new message instance.
     */
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
        return $this->subject('Password Reset Link')
            ->markdown('emails.password_reset')
            ->with([
                'resetLink' => $this->generateResetLink(),
                'user' => $this->user
            ]);
    }

    /**
     * Generate the password reset link.
     *
     * @return string
     */
    protected function generateResetLink()
    {
        // Generate the password reset link using the route with the user's OTP token
        return route('reset.password', ['token' => $this->user->otp_token]);
    }
}
