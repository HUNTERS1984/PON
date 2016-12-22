<?php

namespace CoreBundle\Mailer;

abstract class AbstractEmail
{
    /**
     * @var string
    */
    private $subject;

    /**
     * @var string
    */
    private $sender;

    /**
     * @var string
    */
    private $senderName;

    /**
     * @var string
    */
    private $recipient;

    /**
     * @var string
    */
    private $body;

    /**
     * @var array
    */
    private $bcc;

    /**
     * @var string
    */
    private $replyTo;

    /**
     * Send Email
     * @return mixed
     */
    abstract public function sendEmail();

    /**
     * @param string $subject
     * @return AbstractEmail
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param string $sender
     * @return AbstractEmail
     */
    public function setSender($sender)
    {
        $this->sender = $sender;

        return $this;
    }

    /**
     * @return string
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * @param string $senderName
     * @return AbstractEmail
     */
    public function setSenderName($senderName)
    {
        $this->senderName = $senderName;

        return $this;
    }

    /**
     * @return string
     */
    public function getSenderName()
    {
        return $this->senderName;
    }

    /**
     * @param string $recipient
     * @return AbstractEmail
     */
    public function setRecipient($recipient)
    {
        $this->recipient = $recipient;

        return $this;
    }

    /**
     * @return string
     */
    public function getRecipient()
    {
        return $this->recipient;
    }

    /**
     * @param string $body
     * @return AbstractEmail
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param array $bcc
     * @return AbstractEmail
     */
    public function setBcc($bcc)
    {
        $this->bcc = $bcc;

        return $this;
    }

    /**
     * @return array
     */
    public function getBcc()
    {
        return $this->bcc;
    }

    /**
     * @param string $replyTo
     * @return $this
     */
    public function setReplyTo($replyTo)
    {
        $this->replyTo = $replyTo;

        return $this;
    }

    /**
     * @return string
     */
    public function getReplyTo()
    {
        return $this->replyTo;
    }
}
