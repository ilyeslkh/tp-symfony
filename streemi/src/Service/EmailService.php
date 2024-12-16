<?php


namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class EmailService
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendForgotPasswordEmail(string $to, string $resetToken): void
    {
        $email = (new Email())
            ->from('no-reply@example.com')
            ->to($to)
            ->subject('Password Reset Request')
            ->html('<p>To reset your password, please click the following link: <a href="https://example.com/reset?token=' . $resetToken . '">Reset Password</a></p>');

        $this->mailer->send($email);
    }
}