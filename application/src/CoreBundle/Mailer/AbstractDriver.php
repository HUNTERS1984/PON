<?php

namespace CoreBundle\Mailer;

abstract class AbstractDriver
{
    /**
     * Send Email
     * @param $subject
     * @param $sender
     * @param $senderName
     * @param $recipient
     * @param $body
     * @param array $bcc
     * @param string $replyTo
     * @return mixed
     */
    abstract public function sendEmail($subject, $sender, $senderName, $recipient, $body, array $bcc, $replyTo);
}
