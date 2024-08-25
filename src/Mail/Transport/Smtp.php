<?php

declare(strict_types=1);

namespace Seworqs\Laminas\Mail\Transport;

use Laminas\Mail\Transport\SmtpOptions;

class Smtp extends \Laminas\Mail\Transport\Smtp
{
    private $config;
    public function __construct($config)
    {
        parent::__construct(new SmtpOptions());

        $this->config = $config;
    }

    public function loadFromSeworqsMailConfig($name)
    {
        $options = new SmtpOptions();
        $options->setFromArray($this->config[$name]);
        $this->setOptions($options);
    }
}
