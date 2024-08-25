<?php

declare(strict_types=1);

namespace Seworqs\Laminas\View\Helper;

use Laminas\View\Helper\AbstractHelper;
use Seworqs\Laminas\Service\CurrentUserService;

class CurrentUser extends AbstractHelper
{

    protected $currentUser;

    public function __construct(CurrentUserService $currentUser)
    {
        $this->currentUser = $currentUser;
    }

    public function __invoke()
    {
        return $this->currentUser;
    }
}
