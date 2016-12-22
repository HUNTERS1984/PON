<?php

namespace CoreBundle\Queue\Consumer;

use CoreBundle\Mailer\Client;
use Monolog\Logger;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;

class EmailConsumer implements ConsumerInterface
{
    /**
     * @var Client
     */
    private $client;


    /**
     * @var Logger
    */
    private $logger;

    /**
     * {@inheritdoc}
     */
    public function execute(AMQPMessage $msg)
    {
        $data = unserialize($msg->getBody());
        $subject = !empty($data['subject']) ? $data['subject'] : null;
        $sender = !empty($data['sender']) ? $data['sender'] : null;
        $senderName = !empty($data['sender_name']) ? $data['sender_name'] : null;
        $recipient = !empty($data['recipient']) ? $data['recipient'] : null;
        $body = !empty($data['body']) ? $data['body'] : null;
        $bcc = !empty($data['bcc']) ? $data['bcc'] : [];
        $replyTo = !empty($data['reply_to']) ? $data['reply_to'] : null;
        try {
            $result = $this->process($subject, $sender, $senderName, $recipient, $body, $bcc, $replyTo);

            if(!$result) {
                $this->logger->info(sprintf("Sending Email has error"));
            }else{
                $this->logger->info(sprintf("Finished Send Email Job Successfully"));
            }
        } catch (\Exception $e) {
            $this->logger->error($e->getTraceAsString());
            $this->logger->error(sprintf("Send Email Job Was Failed"));
            $this->logger->error($e->getMessage());
        }
        die();
    }

    public function process($subject, $sender, $senderName, $recipient, $body, array $bcc = [], $replyTo)
    {
        return $this->client->sendEmail(
            $subject,
            $sender,
            $senderName,
            $recipient,
            $body,
            $bcc,
            $replyTo
        );
    }

    /**
     * @param Client $client
     * @return EmailConsumer
     */
    public function setClient(Client $client): EmailConsumer
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @param Logger $logger
     * @return $this
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;
        return $this;
    }
}