<?php

declare(strict_types=1);

namespace Seworqs\Laminas\View\Helper;

use Laminas\View\Helper\AbstractHelper;

class Acl extends AbstractHelper
{
    protected $acl;

    public function __construct(\Seworqs\Laminas\Permission\Acl $acl)
    {
        $this->acl = $acl;
    }

    public function __invoke()
    {
        return $this->acl;
    }
}
