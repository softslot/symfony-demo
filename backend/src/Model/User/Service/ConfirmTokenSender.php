<?php

declare(strict_types=1);

namespace App\Model\User\Service;

use App\Model\User\Entity\User\UserEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

readonly class ConfirmTokenSender
{
    public function __construct(
        private MailerInterface $mailer,
        private Environment $twig,
        private string $from,
    ) {
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function send(UserEmail $userEmail, string $token): void
    {
        $email = new Email();
        $email->from($this->from);
        $email->to($userEmail->value());
        $email->subject('Time for Symfony Mailer!');
        $email->text('Sending emails is fun again!');
        $email->html($this->twig->render('mail/user/signup.html.twig', [
            'token' => $token,
        ]));

        $this->mailer->send($email);
    }
}
