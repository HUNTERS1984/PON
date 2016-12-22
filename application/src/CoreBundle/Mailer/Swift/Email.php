<?php

namespace CoreBundle\Mailer\Swift;


use CoreBundle\Mailer\AbstractEmail;
use CoreBundle\Mailer\Swift\Request\EmailRequest;

class Email extends AbstractEmail
{
    /**
     * @var EmailRequest
     */
    protected $request;

    /**
     * Construct
     *
     * @param \Swift_Mailer $client
     */
    public function __construct(\Swift_Mailer $client)
    {
        $this->initialize($client);
    }

    /**
     * Initialize the Email
     *
     * @param \Swift_Mailer $client
     */
    public function initialize(\Swift_Mailer $client)
    {
        $this->request = new EmailRequest($client);
    }

    /**
     * Send Email
     */
    public function sendEmail()
    {
        $this->request
            ->setSubject($this->getSubject())
            ->setSenderName($this->getSenderName())
            ->setRecipient($this->getRecipient())
            ->setBody($this->getBody())
            ->setBcc($this->getBcc())
            ->setSender($this->getSender())
            ->setReplyTo($this->getReplyTo());
        $response = $this->request->send();
        return $response->getResult();
    }
}