<?php

declare(strict_types=1);

namespace Seworqs\Laminas\Mail;

class Message extends \Laminas\Mail\Message
{
    private $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function loadFromSeworqsMailConfig($name)
    {
        $config = $this->config[$name];

        if (isset($config['from'])) {
            $this->addReplyTo($config['from']['email'], $config['reply_to']['name']);
        }

        if (isset($config['reply_to'])) {
            $this->addReplyTo($config['reply_to']['email'], $config['reply_to']['name']);
        }

        if (isset($config['to'])) {
            foreach ($config['to'] as $email) {
                $this->addTo($email['email'], $email['name']);
            }
        }

        if (isset($config['cc'])) {
            foreach ($config['cc'] as $email) {
                $this->addCc($email['email'], $email['name']);
            }
        }

        if (isset($config['bcc'])) {
            foreach ($config['bcc'] as $email) {
                $this->addBcc($email['email'], $email['name']);
            }
        }

        if (isset($config['encoding'])) {
            $this->setEncoding($config['encoding']);
        }

        if (isset($config['headers'])) {
            foreach ($config['headers'] as $key => $value) {
                $this->getHeaders()->addHeaderLine($key, $value);
            }
        }
    }
}
