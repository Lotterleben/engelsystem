<?php

namespace Engelsystem\Mail;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class Mailer
{
    /** @var MailerInterface */
    protected $mailer;

    /** @var string */
    protected $fromAddress = '';

    /** @var string */
    protected $fromName = null;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Send the mail
     *
     * @param string|string[] $to
     */
    public function send(string|array $to, string $subject, string $body): void
    {
        $message = (new Email())
            ->to(...(array)$to)
            ->from(sprintf('%s <%s>', $this->fromName, $this->fromAddress))
            ->subject($subject)
            ->text($body);

        $this->mailer->send($message);
    }

    public function getFromAddress(): string
    {
        return $this->fromAddress;
    }

    public function setFromAddress(string $fromAddress): void
    {
        $this->fromAddress = $fromAddress;
    }

    public function getFromName(): string
    {
        return $this->fromName;
    }

    public function setFromName(string $fromName): void
    {
        $this->fromName = $fromName;
    }
}
