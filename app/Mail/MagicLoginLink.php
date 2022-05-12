<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

//pour verifier si le mail est bien arrive sur le compte mailtrap voici les infos du compte
//moenah@hotmail.com mdp : Mo&181504

class MagicLoginLink extends Mailable
{
    use Queueable, SerializesModels;

    public $plaintextToken;
    public $expiresAt;
    public $user_email;

    public function __construct($plaintextToken, $expiresAt)
    {
        $this->plaintextToken = $plaintextToken;
        $this->expiresAt = $expiresAt;
    }

    public function build()
    {
        return $this->subject('Your Daily Assistant ' . '- Login Verification')
            ->markdown('emails.magic-login-link', [
                'url' => "http://localhost:8000/initialisation/" . $this->plaintextToken
            ]);
    }
}
