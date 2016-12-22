<?php

namespace CoreBundle\Mailer\Request;

use CoreBundle\Mailer\Swift\Response\EmailResponse;
use CoreBundle\Mailer\Swift\SwiftRequest;

class EmailRequest extends SwiftRequest
{
    /**
     * @var \Swift_Mailer
     */
    protected $client;

    /**
     * EmailRequest constructor.
     *
     * @param \Swift_Mailer $client
     */
    public function __construct(\Swift_Mailer $client)
    {
        $this->client = $client;
    }

    /**
     * Send Email
     *
     * @return mixed
     * @throws \Exception
     */
    public function send()
    {
        try {
            $message = \Swift_Message::newInstance();
            $message
                ->setSubject($this->getSubject())
                ->setFrom($this->getSender(), $this->getSenderName())
                ->setTo($this->getRecipient())
                ->setBcc($this->getBcc())
                ->setBody($this->getBody(), 'text/html', 'utf-8');

            if ($this->getReplyTo()) {
                $message->setReplyTo($this->getReplyTo());
            }

            $this->client->getTransport()->start();
            $response = $this->client->send($message);
            $this->client->getTransport()->stop();
            return new EmailResponse($response);
        } catch(\Exception $e) {
            throw new \Exception(sprintf("SwiftMailer Driver With ErrorCode: %s and Message Code %s", $e->getCode(), $e->getMessage()));
        }


    }
}
