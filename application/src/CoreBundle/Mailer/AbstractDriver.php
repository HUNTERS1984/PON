<?php

namespace CoreBundle\Mailer;

abstract class AbstractDriver
{
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
    abstract public function sendEmail($subject, $sender, $recipient, $body, $senderName = null, array $bcc, $replyTo);
}
