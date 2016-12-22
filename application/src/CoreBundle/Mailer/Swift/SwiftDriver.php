<?php

namespace CoreBundle\Mailer\Swift;


use CoreBundle\Mailer\AbstractDriver;

class SwiftDriver extends AbstractDriver
{
    /**
     * @var \Swift_Mailer
     */
    private $swiftMailerManager;

    public function __construct(\Swift_Mailer $manager)
    {
        $this->swiftMailerManager = $manager;
    }


    /**
     * Send Email
     * @param $subject
     * @param $sender
     * @param $recipient
     * @param $body
     * @param $senderName
     * @param array $bcc
     * @param string $replyTo
     * @return mixed
     */
    public function sendEmail($subject, $sender, $recipient, $body, $senderName = null, array $bcc = [], $replyTo = null)
    {
        $service = new Email($this->swiftMailerManager);
        $service
            ->setSender($sender)
            ->setSenderName($senderName)
            ->setRecipient($recipient)
            ->setBody($body)
            ->setBcc($bcc)
            ->setReplyTo($replyTo)
            ->setSubject($subject);

        return $service->sendEmail();
    }
}
