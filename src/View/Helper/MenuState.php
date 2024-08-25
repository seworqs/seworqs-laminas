<?php

declare(strict_types=1);

namespace Seworqs\Laminas\View\Helper;

use Laminas\View\Helper\AbstractHelper;
use Seworqs\Laminas\Service\CurrentUserService;
use Seworqs\Laminas\Service\MenuStateService;

class MenuState extends AbstractHelper
{

    protected $menuState;

    public function __construct(MenuStateService $menuState)
    {
        $this->menuState = $menuState;
    }

    public function __invoke()
    {
        return $this->menuState;
    }
}
