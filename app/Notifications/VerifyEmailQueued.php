<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
// use Illuminate\Notifications\Messages\MailMessage;
// use Illuminate\Notifications\Notification;
use Illuminate\Auth\Notifications\VerifyEmail;

class VerifyEmailQueued extends VerifyEmail implements ShouldQueue
{
    use Queueable;

   //benefit, while redirect to verify email page, the email are been sent from the project to mailtrap in this case
   // basically the user dont get stuck in the regiuster page while waiting for the email to be sent
}
