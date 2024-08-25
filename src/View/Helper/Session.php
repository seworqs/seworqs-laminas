<?php

namespace Seworqs\Laminas\View\Helper;

use Laminas\Session\Container;
use Laminas\View\Helper\AbstractHelper;

class Session extends AbstractHelper
{
    protected $session;

    public function __construct(Container $sessionContainer)
    {
        $this->session = $sessionContainer;
    }

    public function __invoke()
    {
        return $this->session;
    }
}
