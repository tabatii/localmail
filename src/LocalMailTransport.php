<?php

namespace Tabatii\LocalMail;

use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mime\MessageConverter;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Tabatii\LocalMail\Models\recipient;

class LocalMailTransport extends AbstractTransport
{
    public function __construct()
    {
        parent::__construct();
    }

    public function __toString(): string
    {
        return 'localmail';
    }

    protected function doSend(SentMessage $message): void
    {
        $email = MessageConverter::toEmail($message->getOriginalMessage());
        $this->saveEmailsToDatabase($email, $this->getEmailAttachments($email));
    }

    protected function saveEmailsToDatabase(Email $email, array $attachments): void
    {
        foreach ($email->getFrom() as $from) {
            foreach ($email->getTo() as $to) {
                $this->saveEmailToDatabase($email, $attachments, $from, $to);
            }
            foreach ($email->getCc() as $cc) {
                $this->saveEmailToDatabase($email, $attachments, $from, $cc);
            }
            foreach ($email->getBcc() as $bcc) {
                $this->saveEmailToDatabase($email, $attachments, $from, $bcc);
            }
        }
    }

    protected function saveEmailToDatabase(Email $email, array $attachments, Address $from, Address $to): void
    {
        $recipient = Recipient::firstOrCreate(['address' => $to->getAddress()]);
        $recipient->messages()->create([
            'from_address' => $from->getAddress(),
            'from_name' => $from->getName(),
            'subject' => $email->getSubject(),
            'html_body' => $email->getHtmlBody() ?: $email->getTextBody(),
            'text_body' => $email->getTextBody(),
            'attachments' => $attachments,
            'headers' => $email->getPreparedHeaders()->toArray(),
        ]);
        $recipient->touch();
    }

    protected function getEmailAttachments(Email $email): array
    {
        return collect($email->getAttachments())->map(fn ($attachment) => ['name' => $attachment->getName(), 'type' => $attachment->getContentType()])->toArray();
    }
}
